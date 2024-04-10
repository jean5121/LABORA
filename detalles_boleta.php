<script>

function info_pago(f,h){
    
    Swal.fire({
    title: "<b> "+h+"DETALLES DE PAGO "+f+"  </b>",
    icon: "info",
    html: `
    <b>Se pago el dia: </b>,
    <a href="#">links</a>,
    `,
    showCloseButton: true,
    showCancelButton: false,
    focusConfirm: false,
});
    }  
</script>
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
    $query = "INSERT INTO boleta (fecha_crea,precio_total,deuda,fecha_entrega,idclinica,idusuario_creador,idodontologo) 
                                VALUES(now(),".$TOTAL.",".$TOTAL.",'".$FECHA_ENTRE."',".$IDCLINIC.",".$iduser.",".$IDODO.")";
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


    echo '<script type="text/javascript"> window.location.href="inicio.php?modulo=detalles_boleta&idBole='.$row2['IDMAX'].'";</script>';
}

    ////OBTENER DETALLES DE BOLETA
if (isset($_REQUEST['idBole'])) {
    $IDBOLE= $_GET['idBole'];

    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $queryBOLE = "SELECT idboleta,fecha_crea,precio_total,estado_pago,estado_entrega,fecha_entrega,b.idclinica,b.idusuario_creador,b.idodontologo,
	c.nombre_cli,c.telefono_cli,c.direccion_cli,c.referencia_cli,c.ruc_cli,o.nombre_odo,o.telefono,o.dni_odo,o.ruc_odonto,
    u.nombre_usuario,tu.ctipouser
    FROM boleta b
    LEFT JOIN clinica c 	ON c.idclinica 		= b.idclinica
    LEFT JOIN odontologo o 	ON o.idodontologo 	= b.idodontologo
    LEFT JOIN usuario u 	ON u.idusuario 		= b.idusuario_creador
    LEFT JOIN tipo_usuario tu ON tu.idtipo_usuario = u.tipo_usuario 
    where idboleta = $IDBOLE";
    $respuesta = mysqli_query($con,$queryBOLE);
    $row = mysqli_fetch_assoc($respuesta);

    ////OBTENER EL CONTADOR DE PAGOS (MONTO TOTAL PAGADO)
    $queryPAGOTOTAL = "SELECT SUM(cantidad_pago) AS PAGOTOTAL FROM `pagos` WHERE idvoleta  = $IDBOLE";
    $respuestaPAGOTOTAL = mysqli_query($con,$queryPAGOTOTAL);
    $rowPAGOTOTAL = mysqli_fetch_assoc($respuestaPAGOTOTAL);

    $porcentaje_calculado = ( 100* $rowPAGOTOTAL['PAGOTOTAL']) /$row['precio_total'];
    mysqli_close($con);

}

    
?>

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-1">
                    <span class="badge badge-warning"><h5>C-<?php echo $row['idboleta'];?></h5></span>
                </div>
            <div class="col-sm-5">
            <span class="badge badge-success"><h5><?php echo $row['nombre_odo'];?></H5></span>
            </div>
            <div class="col-sm-5">
            <span class="badge badge-success"><h5><?php echo $row['nombre_cli'];?></H5></span>
            </div>
            </div>
        </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Detalles de Boleta --<?php  echo $row['estado_pago'];  ?></h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
                </button>
            </div>
            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                <div class="row">
                    <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                        <span class="info-box-text text-center text-muted"><b>FECHA DE CREACION</b></span>
                        <span class="info-box-number text-center text-muted mb-0"><p style="color: #28a745;font-size: 19px"><?php echo $row['fecha_crea'];?></p></span>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                        <span class="info-box-text text-center text-muted"><b>FECHA DE ENTREGA</b></span>
                        <span class="info-box-number text-center text-muted mb-0"><p style="color: salmon;font-size: 19px"><?php echo $row['fecha_entrega'];?></p></span>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 col-sm-4">
                    <div class="info-box bg-light" >
                        <div class="info-box-content">
                            <div class="progress-group">
                                <b style="font-size: 17px">P. TOTAL</b>
                                <span class="float-right"><b style="font-size: 19px"><?php echo $rowPAGOTOTAL['PAGOTOTAL']?></b>/<b style="color: salmon;font-size: 21px"><?php echo $row['precio_total'];?></b></span>                   
                                
                            </div>                           
                            <div class="progress progress-sm" style="height: 15px;">
                                <div class="progress-bar bg-success" style="width: <?php echo $porcentaje_calculado?>%"></div>                            
                            </div>
                            <small class="text-success mr-1"><i class="fas fa-arrow-up"></i> % <?php echo $porcentaje_calculado?></small>
                        </div>
                        
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                    <h4>Recent Activity</h4>
                        <div class="post">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                            <span class="username">
                            <a href="#">Jonathan Burke Jr.</a>
                            </span>
                            <span class="description">Shared publicly - 7:45 PM today</span>
                        </div>
                        <!-- /.user-block -->
                        <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                        </p>

                        <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                        </p>
                        </div>

                        <div class="post clearfix">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                            <span class="username">
                            <a href="#">Sarah Ross</a>
                            </span>
                            <span class="description">Sent you a message - 3 days ago</span>
                        </div>
                        <!-- /.user-block -->
                        <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                        </p>
                        <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a>
                        </p>
                        </div>

                        <div class="post">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                            <span class="username">
                            <a href="#">Jonathan Burke Jr.</a>
                            </span>
                            <span class="description">Shared publicly - 5 days ago</span>
                        </div>
                        <!-- /.user-block -->
                        <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                        </p>

                        <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v1</a>
                        </p>
                        </div>
                    </div>
                </div>
                </div>

        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2 ">
                    <!-- DIV CONTENEDOR DE PAGOS -->

            <div class="card">
                <div class="card-header">
                <h3 class="card-title">PAGOS</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                </div>
                </div>
                <!-- /.card-header -->
                <?php                   
                    include_once 'conect.php';
                    $con =mysqli_connect($host,$user_db,$contra_db,$db);
                    $queryPAGO = "SELECT fecha_pago, cantidad_pago, u.nombre_usuario, mp.cmedio
                    FROM pagos p
                    LEFT JOIN usuario u 	ON u.idusuario = p.idusuario
                    LEFT JOIN medio_pago mp	on mp.idmedio_pago = p.idmedio_pago
                    where idvoleta = $IDBOLE ORDER BY fecha_pago DESC;  ";   
                    $respuestaPAGO = mysqli_query($con,$queryPAGO);                   
                    ?>
                <div class="card-body p-0">
                <?php    
                    while ($rowPAGOS=mysqli_fetch_assoc($respuestaPAGO)) {
                        $fecha_hora = $rowPAGOS['fecha_pago'];
                        $separar = (explode(" ",$fecha_hora));
                        $fecha = $separar[0];
                        $hora = $separar[1];
                        ///
                        $monto =$rowPAGOS['cantidad_pago'];
                        $nombre =$rowPAGOS['nombre_usuario']
                ?>
                <ul class="products-list product-list-in-card pl-2 pr-2">
                    <li class="item">
                    <div class="product-img"> 
                    <span class="badge badge-dark center" style="font-size:15px"><h7><?php echo $fecha?></h7><br><h7><?php echo $hora?></h7></span>      
                    </div>
                    <div class="product-info">
                        <b onclick="info_pago(<?php echo $monto.',`'.$nombre.'` ' ?>)" class="product-title"><span class="badge badge-info"><?php echo $rowPAGOS['cmedio']?></span>
                        <span    class="badge badge-success float-right"><h6>S/.<?php echo $rowPAGOS['cantidad_pago']?></h6></span></b>
                        <span class="product-description">
                        <?php echo $rowPAGOS['nombre_usuario']?>
                        </span>
                    </div>
                    </li>
                </ul>
                <?php    
                    }
                    mysqli_close($con);
                
                ?>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                <a href="javascript:void(0)" class="uppercase">View All Products</a>
                </div>
                <!-- /.card-footer -->
            </div>

            
                <h3 class="text-primary"><i class="fas fa-paint-brush"></i> AdminLTE va3</h3>
                <p class="text-muted">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terr.</p>
                <br>
                <div class="text-muted">
                    <p class="text-sm">Client Company
                    <b class="d-block">Deveint Inc</b>
                    </p>
                    <p class="text-sm">Project Leader
                    <b class="d-block">Tony Chicken</b>
                    </p>
                </div>

                <h5 class="mt-5 text-muted">Project files</h5>
                <ul class="list-unstyled">
                    <li>
                    <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                    </li>
                    
                    
                </ul>
                <div class="text-center mt-5 mb-3">
                    <a href="#" class="btn btn-sm btn-primary">Add files</a>
                    <a href="#" class="btn btn-sm btn-warning">Report contact</a>
                </div>
        </div>
            
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        </section>
</div>