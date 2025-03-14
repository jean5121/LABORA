
<?php    

if(isset($_GET['funcion']) && !empty($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
    
    //En función del parámetro que nos llegue ejecutamos una función u otra
    switch($funcion) {
        case 'llenarElementos': 
            $cantidad = $_GET['cantidad'];
            CrearElements($cantidad);
            break;
        case 'llenarClinicas':
            crearClinicas();
            break;
        case 'llenarOdonto':      
            crearOdonto();
            break;
        case 'detalles_pro':
            $cidpro = $_GET['idproduc'];        
            devol_detalles_pruduc($cidpro);
            break;
        case 'cambio_estenvio':
            $idbo = $_GET['cod'];        
            cambio_est_envio($idbo);
            break;
        case 'salir':        
            salirphp();
            break;
        case 'llenar_med_pago':        
            llenar_php_med_pagos();
            break;
        case 'cargar_pago':
            $boletaid = $_GET['cod'];
            $mdpago = $_GET['imedio'];
            $monto = $_GET['mot'];    
            $iduse = $_GET['idus'];
            cargar_pagos_php($boletaid,$mdpago,$monto,$iduse);
            break;
        case 'cambio_f_entrega':
            $boletaid = $_GET['cod'];
            $fentrega = $_GET['f'];            
            cambiar_fecha_enrega($boletaid,$fentrega);
            break;
///REPORTES
        case 'extrae_datos_bar': 
            $a = $_GET['cc'];
            $m = $_GET['dd'];           
            extrae_datos_barr($a,$m);
            break;
        
    }
}

function cargar_pagos_php($b,$mp,$monto,$idusse){
    include_once 'conect.php';
    
    $Jrespu = array(); 
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "UPDATE boleta SET deuda = (deuda - $monto) WHERE idboleta =$b AND deuda >= $monto;    ";
    $resultado = mysqli_query($con,$query);
    if ($resultado && $con->affected_rows > 0) {
        // Actualizar otra tabla
        $con->query("INSERT INTO pagos (cantidad_pago, fecha_pago, idmedio_pago,idusuario,idvoleta) 
                        VALUES ($monto, NOW(), $mp,$idusse,$b);");

            if ($resultado && $con->affected_rows > 0) {
                $Jrespu['success'] = true;
                $Jrespu['mensaje'] = 'El pago se realizo correctamente.';
            } else {
                $Jrespu['success'] = false;
                $Jrespu['mensaje'] = 'Error al insertar el pago: '. mysqli_error($con);
            }
    } else {
        $Jrespu['success'] = false;
        $Jrespu['mensaje'] = 'No se pudo realizar el pago: VERIFICAR MONTO';
    }
    $Jrespu['idboleta'] = $b;
    echo json_encode($Jrespu);
    mysqli_close($con);
    
}

function cambiar_fecha_enrega($b,$f){
    include_once 'conect.php';
    $Jrespu = array(); 
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "UPDATE boleta SET fecha_entrega = '$f' WHERE idboleta =$b ;    ";
    $resultado = mysqli_query($con,$query);
    if ($resultado && $con->affected_rows > 0) {
        
            $Jrespu['success'] = true;
            $Jrespu['mensaje'] = 'Se cambio la fecha correctamente.';
            
    } else {
        $Jrespu['success'] = false;
        $Jrespu['mensaje'] = 'No se pudo cambiar la fecha'. mysqli_error($con);
    }
    $Jrespu['idboleta'] = $b;
    echo json_encode($Jrespu);
    mysqli_close($con);
}




function llenar_php_med_pagos(){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idmedio_pago,cmedio from medio_pago where est_med =1 ORDER BY cmedio ASC";
    $respuesta = mysqli_query($con,$query);
        
    $clinicas ='';

    while ($row = mysqli_fetch_assoc($respuesta)) {
        $clinicas.='<option value="'.$row["idmedio_pago"].'">'.$row["cmedio"].'</option>';
    }
    echo $clinicas;
    mysqli_close($con);
}

function cambio_est_envio($id){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "UPDATE boleta SET estado_entrega = 1 WHERE idboleta=$id";
    $respuesta = mysqli_query($con,$query);
    mysqli_close($con);
}

function CrearElements($canti){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idproducto,nombre_pro,precio_promedio from producto where estado_pro =1 ORDER BY nombre_pro asc";
    $respuesta = mysqli_query($con,$query);
    $productos ='';
    $cont =1;
    while ($row = mysqli_fetch_assoc($respuesta)) {
        $productos.='<option value="'.$row["idproducto"].'">'.$row["nombre_pro"].'</option>';
        ($cont++);
    }

    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $querycolor = "SELECT idtono_color,ctono from tono_color where vigente =1 order by idtono_color asc";
    $respuestacolor = mysqli_query($con,$querycolor);
    $colores ='';
    while ($row = mysqli_fetch_assoc($respuestacolor)) {
        $colores.='<option value="'.$row["idtono_color"].'">'.$row["ctono"].'</option>';
    }


    for ($i=1; $i <=$canti ; $i++) { 
        
        echo'
        <div class="row gx-3 gy-3 p-3" style="color:#FFFFFF">
    <p>'.($i).'.-</p> 
    <div class="form-group col-12 col-sm-6 col-md-3">
        <label>Producto</label>
        <select id="producto'.($i).'" name="producto'.($i).'" onchange="clik_producto($(`#producto'.($i).'`).val(),'.($i).')" class="form-control select2 select2-success" required style="width: 100%;font-size: 15px;">
        <option value="" disabled selected>Seleccione producto</option>     
        '.$productos.'                     
        </select>
    </div>
    <div class="form-group col-6 col-sm-4 col-md-1">
        <label for="cantidad'.($i).'">Cantidad</label>
        <input id="cantidad'.($i).'" name="cantidad'.($i).'" onchange="cambio_cant_precio('.($i).')" value="1" required type="number" min="1" max="60000" class="form-control">
    </div>
    <div class="form-group col-6 col-sm-4 col-md-2">
        <label>Color</label>
        <select id="color'.($i).'" name="color'.($i).'" class="form-control  select2 select2-success" required style="width: 100%;">     
            '.$colores.'                      
        </select>
    </div>                      
    <div class="form-group col-6 col-sm-6 col-md-1">
        <label for="precioU'.($i).'">$</label>
        <input id="precioU'.($i).'" name="precioU'.($i).'" onchange="cambio_cant_precio('.($i).')" required type="number" min="1" class="form-control">
    </div>
    <div class="form-group col-12 col-sm-6 col-md-2">
        <label for="descripcion'.($i).'">Especificaciones</label>
        <textarea id="descripcion'.($i).'" name="descripcion'.($i).'" maxlength="200" rows="1" class="form-control"></textarea>
    </div>
        <div class="mini-checkboxes form-group col-12 col-sm-6 col-md-1"">
        <label><input type="checkbox" name="opciones'.($i).'[]" value="M" CHECKED> MD</label>
        <label><input type="checkbox" name="opciones'.($i).'[]" value="A" CHECKED> AT</label>
        <label><input type="checkbox" name="opciones'.($i).'[]" value="C"> CB</label>
    </div>
    <div class="form-group col-12 col-sm-6 col-md-2" style="max-width: 12.5%;">

        <label for="subtotal'.($i).'">Sub total</label>
    <input id="subtotal'.($i).'" name="subtotal'.($i).'" value="0" required type="number" 
        class="form-control" 
        style="color:salmon; font-weight: bold; font-size: 15px; text-align: right; overflow-x: auto; white-space: nowrap; width: 100%;" 
        readonly>    </div>  
</div>
                ';
    }
    mysqli_close($con);
}
function crearClinicas(){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idcliente,nombre_cli from cliente where estado_cli =1 ORDER BY nombre_cli ASC";
    $respuesta = mysqli_query($con,$query);
        
    $clinicas ='<option value="" disabled selected>Elige un cliente</option>';

    while ($row = mysqli_fetch_assoc($respuesta)) {
        $clinicas.='<option value="'.$row["idcliente"].'">'.$row["nombre_cli"].'</option>';
    }
    echo $clinicas;
    mysqli_close($con);
}
function crearOdonto(){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idodontologo,nombre_odo from odontologo where est_odo =1 ORDER BY nombre_odo ASC";
    $respuesta = mysqli_query($con,$query);
        
    $odontologos ='<option value="" disabled selected>Elige un Odontologo</option>';

    while ($row = mysqli_fetch_assoc($respuesta)) {
        $odontologos.='<option value="'.$row["idodontologo"].'">'.$row["nombre_odo"].'</option>';
    }
    echo $odontologos;
    mysqli_close($con);
}

function devol_detalles_pruduc($idproduc){
    include_once 'conect.php';

    $Jdetalle_pro = array();
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT precio_promedio,nombre_pro from producto where idproducto = $idproduc";
    $respuesta = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($respuesta)) {
        $Jdetalle_pro['status'] = 'ok';
        $Jdetalle_pro['resultado'] = $row;
    }
    echo json_encode($Jdetalle_pro);
    mysqli_close($con);
}

function salirphp(){
    session_start();
    session_destroy();
}



//////REPORTES
function extrae_datos_barr($a,$m){
    include_once 'conect.php'; 
    header('Content-Type: application/json'); // Asegura que la respuesta sea de tipo JSON
    $con = mysqli_connect($host, $user_db, $contra_db, $db);
    // Verifica si la conexión fue exitosa
    if (!$con) {
        die(json_encode(["error" => "Error en la conexión a la base de datos: " . mysqli_connect_error()]));
    }
    $anio = $a;
    $mes = $m;
    $query = "SELECT 
        cl.nombre_cli, 
        SUM(bl.precio_total - bl.deuda) AS monto_total,
        SUM(db.cantidad) AS cantidad
    FROM boleta bl
    JOIN cliente cl ON bl.idcliente = cl.idcliente
    LEFT JOIN (
        SELECT idvoleta, SUM(cantidad) AS cantidad
        FROM detalle_boleta
        GROUP BY idvoleta
    ) db ON db.idvoleta = bl.idboleta
    WHERE 
        ($anio = 1 OR YEAR(bl.fecha_crea) = $anio) 
        AND ($mes = 0 OR MONTH(bl.fecha_crea) = $mes)
    GROUP BY cl.idcliente, cl.nombre_cli
    ORDER BY monto_total DESC;";
    
    $resultado = mysqli_query($con, $query);
    if (!$resultado) {
        die(json_encode(["error" => "Error en la consulta: " . mysqli_error($con)]));
    }
    $data = [];
    if ($resultado->num_rows > 0) {
        // Obtener cada fila y agregarla al array
        while ($row = $resultado->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    // Cerrar la conexión a la base de datos
    $con->close();
    
    // Convertir el array a formato JSON y mostrarlo
    echo json_encode($data);
}


function extrae_datos_dona($a,$m){
    include_once 'conect.php';
    
    header('Content-Type: application/json'); // Asegura que la respuesta sea de tipo JSON

    $con = mysqli_connect($host, $user_db, $contra_db, $db);

    // Verifica si la conexión fue exitosa
    if (!$con) {
        die(json_encode(["error" => "Error en la conexión a la base de datos: " . mysqli_connect_error()]));
    }
    $anio = $a;
    $mes = $m;
    $query = "SELECT cl.nombre_cli, SUM(dbl.cantidad) AS cantidad_pieza 
                FROM detalle_boleta dbl 
                INNER JOIN boleta bl	on dbl.idvoleta = bl.idboleta
                INNER JOIN clinica cl ON bl.idclinica = cl.idclinica 
                WHERE ($anio = 1 OR YEAR(bl.fecha_crea) = $anio) 
                AND ($mes = 0 OR MONTH(bl.fecha_crea) = $mes) 
                GROUP BY cl.nombre_cli 
                ORDER BY cantidad_pieza DESC";
    
    $resultado = mysqli_query($con, $query);
    
    if (!$resultado) {
        die(json_encode(["error" => "Error en la consulta: " . mysqli_error($con)]));
    }

    $data = [];
    
    if ($resultado->num_rows > 0) {
        // Obtener cada fila y agregarla al array
        while ($row = $resultado->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    // Cerrar la conexión a la base de datos
    $con->close();
    
    // Convertir el array a formato JSON y mostrarlo
    echo json_encode($data);
}


?>
