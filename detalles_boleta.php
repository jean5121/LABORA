
<?php 
if(isset($_REQUEST['contenedor_clinicas'])){
    $IDCLINIC = $_POST['contenedor_clinicas'];
    $ADELANTO = $_POST['adelanto'];
    $TOTAL = $_POST['total'];

    $NUM_ELEMENT = $_POST['num_elemetos'];
    
    $iduser = $_SESSION['id'];

    include_once 'conect.php';

    ///INGRESAR DATOS A LA TD BOLETA
    // $con =mysqli_connect($host,$user_db,$contra_db,$db);
    // $query = "INSERT INTO boleta (fecha_crea,precio_total,adelanto,idclinica,idusuario_creador) 
    //                             VALUES(now(),".$TOTAL.",".$ADELANTO.",".$IDCLINIC.",".$iduser.")";
    // $respuesta = mysqli_query($con,$query);
    
    // $query2 = "SELECT LAST_INSERT_ID() AS IDMAX FROM boleta  WHERE idusuario_creador=".$iduser."";
    // $respuesta2 =mysqli_query($con,$query2);
    // $row2 = mysqli_fetch_assoc($respuesta2);

    // $queryInsert = 'INSERT INTO detalle_boleta(cantidad,subtotal,descripcion,idproducto,precio_unidad,idvoleta) VALUES';
    // for ($i=1; $i <=$NUM_ELEMENT ; $i++) {
    //     $temp_canti =  $_POST['cantidad'.$i];
    //     $temp_subtotal =  $_POST['subtotal'.$i];
    //     $temp_decrip=  $_POST['descripcion'.$i];
    //     $temp_idpro=  $_POST['producto'.$i];
    //     $temp_precioU=  $_POST['precioU'.$i];

    //     $query_elemntos = "(".$temp_canti.",".$temp_subtotal.",".$temp_decrip.",".$temp_idpro.",".$temp_precioU.",".$row2['IDMAX']."),";
        
    // }

    // $resp_element =mysqli_query($con,$queryInsert.$query_elemntos);
}

    //mysqli_close($con);
?>

<div class="content-wrapper">
    


<p><?php echo $row2['IDMAX'];   ?> fasdf</p>
</div>