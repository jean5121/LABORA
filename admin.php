<script src="plugins/jquery/jquery.min.js">
</script>
<script src="crear_detalles.js">

</script>

</script>
<div class="content-wrapper">

<div class="row">
                    <div class="col-12">
                    <div class="card">
                <div class="card-header">
                <h3 class="card-title">PRODUCTOS</h3>
                <?php        ?>
                <div class="card-tools">
                <button onclick="agregarProducto()" type="button" class="btn btn-outline-success  btn-sm">
                        AGREGAR PRODUCTO</button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">    
                    <i class="fas fa-minus"></i>
                    </button>
                </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-2">
            
            <!-- /.TABLA DETALLES BOLETA -->
            <div class="card">
            <?php  
                include_once 'conect.php';
                $con =mysqli_connect($host,$user_db,$contra_db,$db);
                $queryProduc = "SELECT idproducto, nombre_pro,precio_promedio,estado_pro,cantidad_material from producto";   
                $respuestaProduc = mysqli_query($con,$queryProduc);  
                
            ?>
            <div class="card-header">
                <h3 class="card-title">Productos registrados:</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
            <div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Material</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php    
            while ($rowpro = mysqli_fetch_assoc($respuestaProduc)) {     
            ?>
            <tr>
                <td><?php echo $rowpro['idproducto']  ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowpro['nombre_pro']   ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowpro['precio_promedio']  ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowpro['cantidad_material'] ?></td>
                <td><?php echo ($rowpro['estado_pro'] == 1) ? "activo" : "bloqueado"; ?></td>
                <td>
                                                <!-- Botón Editar -->
                                                <i 
                                                    class="fas fa-edit text-primary"
                                                    style="cursor: pointer;"
                                                    onclick="editarProducto(<?php echo $rowpro['idproducto']; ?>, '<?php echo htmlspecialchars($rowpro['nombre_pro']); ?>', <?php echo $rowpro['precio_promedio']; ?>, <?php echo $rowpro['cantidad_material']; ?>, <?php echo $rowpro['estado_pro']; ?>)"

                                                ></i>
                                                &nbsp;
                                                <!-- Botón Eliminar -->
                                                <i 
                                                    class="fas fa-trash text-danger"
                                                    style="cursor: pointer;"
                                                    onclick="eliminarProducto (<?php echo $rowpro['idproducto']; ?>)"
                                                ></i>
                                            </td>
            </tr>
            <?php   } ?>

        </tbody>
    </table>
    </div>
<?php  mysqli_close($con);  ?>

            </div>
            <!-- /.card-body -->
            </div>
            </div>
            </div>
            </div>
</div>


<div class="row">
                    <div class="col-12">
                    <div class="card">
                <div class="card-header">
                <h3 class="card-title">CLINICAS</h3>
                <?php        ?>
                <div class="card-tools">
                <button onclick="agregarProducto()" type="button" class="btn btn-outline-success  btn-sm">
                        AGREGAR CLINICA</button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse">    
                    <i class="fas fa-minus"></i>
                    </button>
                </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-2">
            
            <!-- /.TABLA DETALLES BOLETA -->
            <div class="card">
            <?php  
                include_once 'conect.php';
                $con =mysqli_connect($host,$user_db,$contra_db,$db);
                $queryProduc = "SELECT idclinica, nombre_cli,telefono_cli,direccion_cli,referencia_cli,estado_cli,ruc_cli from clinica";   
                $respuestaProduc = mysqli_query($con,$queryProduc);  
                
            ?>
            <div class="card-header">
                <h3 class="card-title">Clinicas registradas:</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
            <div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th style="width: 10%;">#</th>
                <th>Nombre</th>
                <th>Telefono</th>
                <th>Direccion</th>
                <th>Ref.</th>
                <th>RUC</th>
                <th>Estado</th>
                <th>Opciones</th>

            </tr>
        </thead>
        <tbody>
            <?php    
            while ($rowcli = mysqli_fetch_assoc($respuestaProduc)) {     
            ?>
            <tr>
                <td><?php echo $rowcli['idclinica']  ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowcli['nombre_cli']   ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowcli['telefono_cli']  ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowcli['direccion_cli'] ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowcli['referencia_cli'] ?></td>
                <td style="word-wrap: break-word; word-break: break-word;"><?php echo $rowcli['ruc_cli'] ?></td>
                <td><?php echo ($rowcli['estado_cli'] == 1) ? "activo" : "bloqueado"; ?></td>
                <td>
                                                <!-- Botón Editar -->
                                <i 
                                    class="fas fa-edit text-primary"
                                    style="cursor: pointer;"
                                    onclick="editarClinicas(<?php echo $rowcli['idclinica']; ?>, '<?php echo htmlspecialchars($rowcli['nombre_cli']); ?>', 
                                    '<?php echo $rowcli['telefono_cli']; ?>', '<?php echo $rowcli['direccion_cli']; ?>', 
                                    '<?php echo $rowcli['referencia_cli']; ?>', '<?php echo $rowcli['ruc_cli']; ?>',<?php echo $rowcli['estado_cli']; ?>)"

                                ></i>
                                                &nbsp;
                                                <!-- Botón Eliminar -->
                                                <i 
                                                    class="fas fa-trash text-danger"
                                                    style="cursor: pointer;"
                                                    onclick="eliminarProducto (<?php echo $rowcli['idclinica']; ?>)"
                                                ></i>
                                            </td>
            </tr>
            <?php   } ?>

        </tbody>
    </table>
</div>
<?php  mysqli_close($con);  ?>

            </div>
            <!-- /.card-body -->
            </div>
            </div>
            </div>
            </div>
                </div>
</div>

