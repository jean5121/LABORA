<!DOCTYPE html>
<html>
<head>
<style>
        /* Estilos para ocultar elementos al imprimir */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<?php
    // Obtener las variables de la URL
    $bol = isset($_GET['bole']) ? htmlspecialchars($_GET['bole']) : '1';
    
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $queryBOLE = "SELECT idboleta,DATE_FORMAT(fecha_crea, '%d-%m-%Y %H:%i:%s')f_crea,precio_total,estado_pago,deuda,estado_entrega,DATE_FORMAT(fecha_entrega, '%d-%m-%Y') AS fentrega ,b.idcliente,b.idusuario_creador,
	c.nombre_cli,c.telefono_cli,c.direccion,c.documento,
    u.nombre_usuario,tu.ctipouser
    FROM boleta b
    inner JOIN cliente c 	ON c.idcliente 		= b.idcliente
    inner JOIN usuario u 	ON u.idusuario 		= b.idusuario_creador
    inner JOIN tipo_usuario tu ON tu.idtipo_usuario = u.tipo_usuario
    inner JOIN detalle_boleta de    on b.idboleta = de.idvoleta
    
    where idboleta = $bol";
    $respuesta = mysqli_query($con,$queryBOLE);
    $row = mysqli_fetch_assoc($respuesta);
    mysqli_close($con);

    ?>
    <div class="ticket">
        <pre>

BOLETA C-<?php echo $bol;?> <?php echo PHP_EOL; ?>
FECHA ENTREGA:<?php echo $row['fentrega'];?> <?php echo PHP_EOL; ?>
Client:<?php echo $row['nombre_cli']."(".$row['telefono_cli'].")";?>
<?php echo PHP_EOL; ?>

--------------------
<?php    
include_once 'conect.php';
$con =mysqli_connect($host,$user_db,$contra_db,$db);
$queryDETA = "SELECT cantidad, descripcion, sub_total, pro.nombre_pro,precio_unidad,tono.ctono
FROM detalle_boleta deta
INNER JOIN producto pro 	ON deta.idproducto = pro.idproducto
inner join tono_color tono        on deta.tono_color = tono.idtono_color                
where deta.idvoleta = $bol;  ";   
$respuestaDETA = mysqli_query($con,$queryDETA);
?>

<?php while ($rowDETA=mysqli_fetch_assoc($respuestaDETA)) {?>
<?php echo $rowDETA['cantidad']. "-" . $rowDETA['nombre_pro']." (".$rowDETA['ctono'].") " .$rowDETA['descripcion']  ?> 
--------------------
<?php  }   mysqli_close($con);   ?>
--------------------
--------------------

        </pre>
    </div>
    <button class="no-print" id="btnPrint">Imprimir</button>
    <script>
        document.getElementById('btnPrint').addEventListener('click', function () {
            window.print();
        });
    </script>
</body>
</html>
