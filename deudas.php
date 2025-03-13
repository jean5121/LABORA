<?php
// Instanciar mPDF (asegúrate de tenerlo instalado vía Composer)
require_once __DIR__ . '/vendor/autoload.php';
use Mpdf\Mpdf;
$mpdf = new Mpdf();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idcliente'])) {
    // Obtener y sanitizar el ID del cliente
    $idCliente = intval($_POST['idcliente']);

    // Conexión a la base de datos (ajusta los parámetros)
    include_once 'conect.php';
    $con = mysqli_connect($host, $user_db, $contra_db, $db);
    if ($con->connect_error) {
        die("Error de conexión: " . $con->connect_error);
    }

    // QUERY 1: Obtener boletas y sus detalles (productos)
    $sql1 = "SELECT 
                b.idboleta,
                b.fecha_crea,
                cl.nombre_cli,
                db.cantidad,
                db.sub_total,
                pr.nombre_pro
             FROM boleta b
             INNER JOIN cliente cl ON cl.idcliente = b.idcliente
             INNER JOIN detalle_boleta db ON db.idvoleta = b.idboleta
             INNER JOIN producto pr ON pr.idproducto = db.idproducto
             WHERE b.estado_pago = 0 AND b.idcliente = $idCliente";
    $result1 = $con->query($sql1);
    if (!$result1) {
        die("Error en la consulta de boletas: " . $con->error);
    }
    $boletas = [];
    while ($row = $result1->fetch_assoc()) {
        $idBoleta = $row['idboleta'];
        if (!isset($boletas[$idBoleta])) {
            $boletas[$idBoleta] = [
                'fecha_crea' => $row['fecha_crea'],
                'nombre_cli' => $row['nombre_cli'],
                'detalles'   => []
            ];
        }
        $boletas[$idBoleta]['detalles'][] = [
            'nombre_pro' => $row['nombre_pro'],
            'cantidad'   => $row['cantidad'],
            'sub_total'  => $row['sub_total']
        ];
    }

    // QUERY 2: Obtener pagos para las boletas del cliente
    $sql2 = "SELECT idvoleta, fecha_pago, cantidad_pago
             FROM pagos
             WHERE idvoleta IN (
                 SELECT idboleta FROM boleta 
                 WHERE estado_pago = 0 AND idcliente = $idCliente
             )";
    $result2 = $con->query($sql2);
    if (!$result2) {
        die("Error en la consulta de pagos: " . $con->error);
    }
    $pagos = [];
    while ($row = $result2->fetch_assoc()) {
        $idBoleta = $row['idvoleta'];
        if (!isset($pagos[$idBoleta])) {
            $pagos[$idBoleta] = [];
        }
        $pagos[$idBoleta][] = [
            'fecha_pago'    => $row['fecha_pago'],
            'cantidad_pago' => $row['cantidad_pago']
        ];
    }
    $con->close();

    // Combinar pagos con cada boleta y calcular totales
    foreach ($boletas as $idBoleta => &$data) {
        $totalBoleta = 0;
        foreach ($data['detalles'] as $detalle) {
            $totalBoleta += $detalle['sub_total'];
        }
        $data['total_boleta'] = $totalBoleta;
        $data['pagos'] = isset($pagos[$idBoleta]) ? $pagos[$idBoleta] : [];
    }
    unset($data);

    // Calcular la deuda pendiente de cada boleta y el total global
    $totalDeudaGlobal = 0;
    foreach ($boletas as $idBoleta => $data) {
        $totalAdelantos = 0;
        if (!empty($data['pagos'])) {
            foreach ($data['pagos'] as $pago) {
                $totalAdelantos += $pago['cantidad_pago'];
            }
        }
        $deudaPendiente = $data['total_boleta'] - $totalAdelantos;
        $boletas[$idBoleta]['total_adelantos'] = $totalAdelantos;
        $boletas[$idBoleta]['deuda_pendiente'] = $deudaPendiente;
        $totalDeudaGlobal += $deudaPendiente;
    }

    // Nombre del cliente para el encabezado
    $clientName = isset($boletas[array_key_first($boletas)]) ? $boletas[array_key_first($boletas)]['nombre_cli'] : $idCliente;

    // Construir el HTML del reporte usando CSS Grid para dos columnas
    $html = '<html>
    <head>
      <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 0; }
        .header { text-align: center; padding: 4px 0; border-bottom: 1px solid #007BFF; }
        .header h1 { color: #007BFF; margin: 0; font-size: 14px; }
        .header p { margin: 2px 0 0; font-size: 10px; }
        /* Contenedor con CSS Grid para dos columnas */
        .invoice-container { 
          display: grid;
          grid-template-columns: repeat(2, 1fr); /* Dos columnas de igual tamaño */
          gap: 10px; /* Espacio entre las boletas */
          width: 100%; /* Ocupar todo el ancho disponible */
        }
        .invoice { 
          border: 1px solid #ddd; 
          padding: 4px; 
          box-sizing: border-box;
          width: 100%; /* Ocupar el 50% del contenedor */
          page-break-inside: avoid; /* Evitar que las boletas se corten entre páginas */
        }
        .invoice-header { background-color: #f2f2f2; padding: 3px; }
        .invoice-header h2 { margin: 0; font-size: 12px; }
        .invoice-header p { margin: 1px 0; font-size: 9px; }
        .section-title { margin: 4px 0; font-size: 11px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 3px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 2px 4px; font-size: 9px; }
        th { background-color: #007BFF; color: #fff; text-align: left; }
        .invoice-summary { text-align: right; font-size: 10px; font-weight: bold; margin-top: 3px; }
        .grand-total { text-align: right; font-size: 12px; font-weight: bold; margin: 4px; page-break-before: avoid; }
        .footer { text-align: center; font-size: 8px; margin-top: 4px; color: #888; }
      </style>
    </head>
    <body>
      <div class="header">
        <h1>Reporte de Deudas Pendientes</h1>
        <p><strong>Cliente:</strong> ' . $clientName . '</p>
      </div>
      <div class="invoice-container">';
    
    // Generar HTML para cada boleta
    foreach ($boletas as $idBoleta => $data) {
        $html .= '<div class="invoice">';
        $html .= '<div class="invoice-header">';
        $html .= '<h2>Boleta #' . $idBoleta . '</h2>';
        $html .= '<p><strong>Fecha:</strong> ' . $data['fecha_crea'] . '</p>';
        $html .= '<p><strong>Total:</strong> $' . number_format($data['total_boleta'], 2) . '</p>';
        $html .= '</div>';
        // Detalles de la boleta
        $html .= '<div class="section-title">Detalles</div>';
        $html .= '<table>
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Cant.</th>
                        <th>Sub Total</th>
                      </tr>
                    </thead>
                    <tbody>';
        foreach ($data['detalles'] as $detalle) {
            $html .= '<tr>';
            $html .= '<td>' . $detalle['nombre_pro'] . '</td>';
            $html .= '<td>' . $detalle['cantidad'] . '</td>';
            $html .= '<td>$' . number_format($detalle['sub_total'], 2) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        // Adelantos de la boleta
        $html .= '<div class="section-title">Adelantos</div>';
        $html .= '<table>
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Monto</th>
                      </tr>
                    </thead>
                    <tbody>';
        if (!empty($data['pagos'])) {
            foreach ($data['pagos'] as $pago) {
                $html .= '<tr>';
                $html .= '<td>' . $pago['fecha_pago'] . '</td>';
                $html .= '<td>$' . number_format($pago['cantidad_pago'], 2) . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="2" style="text-align:center;">No hay adelantos</td></tr>';
        }
        $html .= '</tbody></table>';
        // Resumen de la boleta (mostrar Total Adelantos solo si es mayor que 0)
        $html .= '<div class="invoice-summary">';
        $html .= '<p>Total Boleta: $' . number_format($data['total_boleta'], 2) . '</p>';
        if ($data['total_adelantos'] > 0) {
            $html .= '<p>Total Adelantos: $' . number_format($data['total_adelantos'], 2) . '</p>';
        }
        $html .= '<p>Deuda: $' . number_format($data['deuda_pendiente'], 2) . '</p>';
        $html .= '</div>';
        $html .= '</div>'; // Fin invoice
    }
    
    $html .= '</div>'; // Fin invoice-container
    
    // Total global de deuda pendiente
    $html .= '<div class="grand-total"><p>Total Deuda Pendiente: $' . number_format($totalDeudaGlobal, 2) . '</p></div>';
    $html .= '<div class="footer"><p>Reporte generado el ' . date("d/m/Y H:i") . '</p></div>';
    $html .= '</body></html>';
    
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit();
}
?>