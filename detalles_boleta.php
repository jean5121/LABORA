<script>

function aa(){
    alert("aaaaaaaaaaaa");
    Swal.fire({
    position: "top-end",
    icon: "success",
    title: "Your work has been saved",
    showConfirmButton: false,
    timer: 1500
    }); 
    alert("bbbbb");
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


    echo '<script type="text/javascript"> window.location.href="inicio.php?modulo=detalles_boleta&idBole='.$row2['IDMAX'].'";</script>';
}
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
            <h3 class="card-title">Detalles de Boleta</h3>

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
                        <span class="info-box-text text-center text-muted"><b>Fecha de Creacion</b></span>
                        <span class="info-box-number text-center text-muted mb-0"><b style="color: #28a745;"><?php echo $row['fecha_crea'];?></b></span>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                        <span class="info-box-text text-center text-muted"><b>Fecha de Entrega</b></span>
                        <span class="info-box-number text-center text-muted mb-0"><b style="color: salmon;"><?php echo $row['fecha_entrega'];?></b></span>
                        </div>
                    </div>
                    </div>
                    <div class="col-12 col-sm-4">
                    <div class="info-box bg-light" >
                        <div class="info-box-content">
                            <div class="progress-group">
                                <b>Precio Total</b>
                                <span class="float-right"><b>0</b>/<b style="color: salmon;"><?php echo $row['precio_total'];?></b></span>
                                <div class="progress progress-sm">
                                <div class="progress-bar bg-success" style="width: 5%"></div>
                                </div>
                            </div>
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

                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <!-- DIV CONTENEDOR DE PAGOS -->
            <div class="card">
            <div class="card-header border-transparent">
                <h3 class="card-title"><b style="color:#28a745;">PAGOS</b></h3>
                <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>

                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <?php        ?>
                <table class="table m-0">
                    <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Respo.</th>
                        <th>Monto</th>                       
                        <th>Medio</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>OR9842</td>
                        <td>Call of Duty IV</td>
                        <td><span class="badge badge-success">Shipped</span></td>
                        <td>a</td>
                    </tr>                   
                    </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            
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
            </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

        </section>
</div>