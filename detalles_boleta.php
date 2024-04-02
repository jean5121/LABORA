
<?php 
    if(isset($_REQUEST['contenedor_clinicas'])){
    $IDCLINIC = $_POST['contenedor_clinicas'];
    $ADELANTO = $_POST['adelanto'];
    $TOTAL = $_POST['total'];

    $NUM_ELEMENT = $_POST['num_elemetos'];
    
    $iduser = $_SESSION['id'];

    include_once 'conect.php';

    ///INGRESAR DATOS A LA TD BOLETA
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "INSERT INTO boleta (fecha_crea,precio_total,adelanto,idclinica,idusuario_creador) 
                                VALUES(now(),".$TOTAL.",".$ADELANTO.",".$IDCLINIC.",".$iduser."); SELECT LAST_INSERT_ID()";
    $respuesta = mysqli_query($con,$query);
    
    echo $respuesta;
    }

    mysqli_close($con);
?>

<div class="content-wrapper">
    


<p>fasdf</p>
</div>