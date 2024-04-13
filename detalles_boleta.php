<script>

function info_pago(monto,nombre,medio,fecha,hora){
    
    var color_succes = "style=color:#28a745";
    Swal.fire({
    title: "<p>S/.<b style='color:salmon'>"+monto+"</b> Se cobro el dia <a "+color_succes+">"+fecha+"</a> a las <a "+color_succes+">"+hora+"</a>, en "+medio+"</p>",
    icon: "info",
    html: `
    <b>COBRADO POR:  </b><br>
    <b>`+nombre+`</b>
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
    $porcentaje_calculado = round($porcentaje_calculado, 0);
    mysqli_close($con);

}

    
?>

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <ul class="list-inline">
                    <li class="list-inline-item"><span class="badge badge-warning"><h5>C-<?php echo $row['idboleta'];?></h5></span></li>
                    <li class="list-inline-item"><h5><span class="badge badge-white"><h7>Clinica: </h7></span><?php echo strtoupper($row['nombre_cli']);?></H5></li>
                    <li class="list-inline-item"><h5><span class="badge badge-white"><h7>Odontologo: </h7></span><?php echo strtoupper($row['nombre_odo']);?></H5></li>
                </ul>    
            </div>
        </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
            <ul class="list-group list-group-horizontal gap-2">
                <?php    
                $mensaje_estpago    = ($row['estado_pago']==1) ? ' CANCELADO' : ' NO CANCELADO';
                $color_estpago      = ($row['estado_pago']==1) ? '#28a745' : 'salmon';

                $mensaje_estentrega    = ($row['estado_entrega']==1) ? ' ENTREGADO' : ' NO ENTREGADO';
                $color_estentrega      = ($row['estado_entrega']==1) ? '#28a745' : 'salmon';
                
                ?>
                <li class="list-group-item border-right-0" style="color:<?php echo $color_estpago; ?>;border: 2px solid <?php echo $color_estpago; ?> ;">
                <svg viewBox="0 0 512 512" style=" fill:<?php echo $color_estpago; ?>" width="24" height="24"><path d="M320 96H192L144.6 24.9C137.5 14.2 145.1 0 157.9 0H354.1c12.8 0 20.4 14.2 13.3 24.9L320 96zM192 128H320c3.8 2.5 8.1 5.3 13 8.4C389.7 172.7 512 250.9 512 416c0 53-43 96-96 96H96c-53 0-96-43-96-96C0 250.9 122.3 172.7 179 136.4l0 0 0 0c4.8-3.1 9.2-5.9 13-8.4zm84 88c0-11-9-20-20-20s-20 9-20 20v14c-7.6 1.7-15.2 4.4-22.2 8.5c-13.9 8.3-25.9 22.8-25.8 43.9c.1 20.3 12 33.1 24.7 40.7c11 6.6 24.7 10.8 35.6 14l1.7 .5c12.6 3.8 21.8 6.8 28 10.7c5.1 3.2 5.8 5.4 5.9 8.2c.1 5-1.8 8-5.9 10.5c-5 3.1-12.9 5-21.4 4.7c-11.1-.4-21.5-3.9-35.1-8.5c-2.3-.8-4.7-1.6-7.2-2.4c-10.5-3.5-21.8 2.2-25.3 12.6s2.2 21.8 12.6 25.3c1.9 .6 4 1.3 6.1 2.1l0 0 0 0c8.3 2.9 17.9 6.2 28.2 8.4V424c0 11 9 20 20 20s20-9 20-20V410.2c8-1.7 16-4.5 23.2-9c14.3-8.9 25.1-24.1 24.8-45c-.3-20.3-11.7-33.4-24.6-41.6c-11.5-7.2-25.9-11.6-37.1-15l0 0-.7-.2c-12.8-3.9-21.9-6.7-28.3-10.5c-5.2-3.1-5.3-4.9-5.3-6.7c0-3.7 1.4-6.5 6.2-9.3c5.4-3.2 13.6-5.1 21.5-5c9.6 .1 20.2 2.2 31.2 5.2c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-6.5-1.7-13.7-3.4-21.1-4.7V216z"/></svg>
                <?php echo $mensaje_estpago ?>
                </li>
                
                <li onclick="alert('asd')" class="list-group-item border-left-0" style="color:<?php echo $color_estentrega; ?>;border: 2px solid <?php echo $color_estentrega; ?> ;">
                <svg viewBox="0 0 640 512" style="fill:<?php echo $color_estentrega; ?>" width="30" height="30"><path d="M64 32C28.7 32 0 60.7 0 96V304v80 16c0 44.2 35.8 80 80 80c26.2 0 49.4-12.6 64-32c14.6 19.4 37.8 32 64 32c44.2 0 80-35.8 80-80c0-5.5-.6-10.8-1.6-16H416h33.6c-1 5.2-1.6 10.5-1.6 16c0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16H608c17.7 0 32-14.3 32-32V288 272 261.7c0-9.2-3.2-18.2-9-25.3l-58.8-71.8c-10.6-13-26.5-20.5-43.3-20.5H480V96c0-35.3-28.7-64-64-64H64zM585 256H480V192h48.8c2.4 0 4.7 1.1 6.2 2.9L585 256zM528 368a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM176 400a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM80 368a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                <?php echo $mensaje_estentrega ?>
                </li>
                
            </ul>
            
                <!-- /.info-box-content -->
            
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
                                <span class="float-right"><b style="font-size: 19px"><?php echo $rowPAGOTOTAL['PAGOTOTAL']??0 ?></b>/<b style="color: salmon;font-size: 21px"><?php echo $row['precio_total'];?></b></span>                   
                                
                            </div>                           
                            <div class="progress progress-sm" style="height: 15px;">
                                <div class="progress-bar bg-success" style="width: <?php echo $porcentaje_calculado?>%"></div>                            
                            </div>
                            <small class="text-success mr-1"><i class="fas fa-caret-up"></i> % <?php echo $porcentaje_calculado?></small>
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
                        <b onclick="info_pago(<?php echo $monto.',`'.$nombre.'`,`'.$rowPAGOS['cmedio'].'`,`'.$fecha.'`,`'.$hora.'` ' ?>)" class="product-title"><span class="badge badge-info"><?php echo $rowPAGOS['cmedio']?></span>
                        <span    class="badge badge-success float-right"><h6>S/ <?php echo $rowPAGOS['cantidad_pago']?></h6></span></b>
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