
<?php 
if(isset($_REQUEST['contenedor_clinicas'])){
    $IDCLINIC = $_POST['contenedor_clinicas'];
    $IDODO = $_POST['contenedor_odontologo'];
    $FECHA_ENTRE = $_POST['f_entrega'];
    
    $TOTAL = $_POST['total'];
    $NUM_ELEMENT = $_POST['num_elemetos'];
    $iduser = $_SESSION['id'];

    include_once 'conect.php';

    ////INGRESAR DATOS A LA TD BOLETA
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "INSERT INTO boleta (fecha_crea,precio_total,fecha_entrega,idclinica,idusuario_creador,idodontologo) 
                                VALUES(now(),".$TOTAL.",'".$FECHA_ENTRE."',".$IDCLINIC.",".$iduser.",".$IDODO.")";
    $respuesta = mysqli_query($con,$query);
    ////OBTENER EL ID DE LA BOLETA INGRESADA
    $query2 = "SELECT LAST_INSERT_ID() AS IDMAX FROM boleta  WHERE idusuario_creador=".$iduser."";
    $respuesta2 =mysqli_query($con,$query2);
    $row2 = mysqli_fetch_assoc($respuesta2);
    

    /////INSERTAR LOS DETALLES
    $queryInsert = 'INSERT INTO detalle_boleta(cantidad,sub_total,descripcion,idproducto,precio_unidad,idvoleta) VALUES';
    for ($i=1; $i <=$NUM_ELEMENT ; $i++) {
        $temp_canti =  $_POST['cantidad'.$i];
        $temp_subtotal =  $_POST['subtotal'.$i];
        $temp_decrip=  $_POST['descripcion'.$i];
        $temp_idpro=  $_POST['producto'.$i];
        $temp_precioU=  $_POST['precioU'.$i];

        $query_elemntos = "(".$temp_canti.",".$temp_subtotal.",'".$temp_decrip."',".$temp_idpro.",".$temp_precioU.",".$row2['IDMAX']."),";
        $queryInsert = $queryInsert.$query_elemntos;
    }
    $queryInsert=substr($queryInsert, 0, -1);
    $resp_element =mysqli_query($con,$queryInsert);
    mysqli_close($con);
}

    
?>

<div class="content-wrapper">
    


<p><?php  //echo $row2['IDMAX']   ?> fasdf</p>

<p><?php echo $queryInsert   ?></p>
</div>