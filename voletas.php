<script src="plugins/jquery/jquery.min.js">
</script>
<script src="crear_detalles.js">

</script>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-1">
          <div class="col-sm-6">
              
          </div>
          <div class="col-sm-6">
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <?php 
        include_once 'conect.php';
        $con =mysqli_connect($host,$user_db,$contra_db,$db);
        $querysinpagar = "SELECT COUNT(*) FROM `boleta` WHERE estado_pago = 0 AND fecha_crea BETWEEN DATE_SUB(now(), INTERVAL 4 MONTH) AND now() ;";
        $respusinpagar = mysqli_query($con,$querysinpagar);
        $rowsinpaga=mysqli_fetch_assoc($respusinpagar)
        
        ?>

      <div class="container-fluid">
        <div class="row">
        
          <div class="col-12">
            <div class="card">
              <div class="card-header row">
                <div class="col-md-3">
                <button type="button" id="btn_no_pagado" onclick="dtable_cargar_seach('n-c')" class="btn btn-danger btn-block">
                  <i class="fa fa-times-circle"></i> <?php echo ($rowsinpaga['COUNT(*)'])?> SIN CANCELAR</button>
                </div>               
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                  <button type="button" onclick="link_crear()" class="btn btn-outline-success btn-block">
                    <i class="fa fa-file"></i> CREAR VOLETA
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>COD</th>
                    <th>CREACION</th>
                    <th>ENTREGA</th>
                    <th>CLINICA</th>
                    <th>ODONTOLOG</th>                    
                    <th>DEUDA</th>
                    <th>ESTADO</th>                 
                  </tr>
                  </thead>
                  <tbody>
              <?php  
              include_once 'conect.php';
              $con =mysqli_connect($host,$user_db,$contra_db,$db);
              $queryGENE = "SELECT idboleta,deuda,estado_entrega,estado_pago,DATE_FORMAT(fecha_crea, '%d-%m-%Y %H:%i:%s')f_crea,DATE_FORMAT(fecha_entrega, '%d-%m-%Y') AS fentrega,precio_total,c.nombre_cli,o.nombre_odo
                            FROM `boleta` b
                            INNER JOIN 	clinica c 		ON	b.idclinica 	= c.idclinica
                            INNER JOIN	odontologo o 	ON	b.idodontologo	= o.idodontologo
                            WHERE fecha_crea BETWEEN DATE_SUB(now(), INTERVAL 4 MONTH) AND now() ORDER BY fecha_crea DESC;";
              $respuestaGENE = mysqli_query($con,$queryGENE);
              while ($rowGENE=mysqli_fetch_assoc($respuestaGENE)) {
                        ////SEPARAR HORA Y FECHA
                        $fecha_hora = $rowGENE['f_crea'];
                        $separar = (explode(" ",$fecha_hora));
                        $fecha = $separar[0];
                        $hora = $separar[1];
                        
                        $mensaje_estpago    = ($rowGENE['estado_pago']==1) ? ' CANCELADO' : ' NO CANCELADO';
                        $color_estpago      = ($rowGENE['estado_pago']==1) ? '#28a745' : 'salmon';
        
                        $color_estentrega      = ($rowGENE['estado_entrega']==1) ? '#28a745' : 'salmon';
                        $boton_entre           = ($rowGENE['estado_entrega']==1) ? '' : 'confirmar_envio('.$rowGENE['idboleta'].')';

                        $color_est_tot         =($rowGENE['estado_pago']==1)&&($rowGENE['estado_entrega']==1) ? 'success' : 'warning';
              ?>      
                  <tr>
                    <td><span class="badge badge-<?php echo $color_est_tot ?>" style="font-size:19px">C-<?php echo $rowGENE['idboleta'] ?></span><br>
                    <p style="font-size: 5px; display: none;"><?php echo (($rowGENE['estado_pago']==1) ? '' : 'n-c')?></p>
                    </td>
                    <td><?php echo $fecha.'<br>'.$hora ?></td>
                    <td>E-<?php echo $rowGENE['fentrega'] ?></td>
                    <td><?php echo $rowGENE['nombre_cli'] ?></td>
                    <td><?php echo $rowGENE['nombre_odo'] ?></td>
                    <td><?php echo $rowGENE['deuda'] ?> de <?php echo $rowGENE['precio_total'] ?></td>
                    <td>
                    <div style="position: relative;">                      
                      <svg viewBox="0 0 512 512" style="fill:<?php echo $color_estpago ?>" width="24" height="24"><path d="M320 96H192L144.6 24.9C137.5 14.2 145.1 0 157.9 0H354.1c12.8 0 20.4 14.2 13.3 24.9L320 96zM192 128H320c3.8 2.5 8.1 5.3 13 8.4C389.7 172.7 512 250.9 512 416c0 53-43 96-96 96H96c-53 0-96-43-96-96C0 250.9 122.3 172.7 179 136.4l0 0 0 0c4.8-3.1 9.2-5.9 13-8.4zm84 88c0-11-9-20-20-20s-20 9-20 20v14c-7.6 1.7-15.2 4.4-22.2 8.5c-13.9 8.3-25.9 22.8-25.8 43.9c.1 20.3 12 33.1 24.7 40.7c11 6.6 24.7 10.8 35.6 14l1.7 .5c12.6 3.8 21.8 6.8 28 10.7c5.1 3.2 5.8 5.4 5.9 8.2c.1 5-1.8 8-5.9 10.5c-5 3.1-12.9 5-21.4 4.7c-11.1-.4-21.5-3.9-35.1-8.5c-2.3-.8-4.7-1.6-7.2-2.4c-10.5-3.5-21.8 2.2-25.3 12.6s2.2 21.8 12.6 25.3c1.9 .6 4 1.3 6.1 2.1l0 0 0 0c8.3 2.9 17.9 6.2 28.2 8.4V424c0 11 9 20 20 20s20-9 20-20V410.2c8-1.7 16-4.5 23.2-9c14.3-8.9 25.1-24.1 24.8-45c-.3-20.3-11.7-33.4-24.6-41.6c-11.5-7.2-25.9-11.6-37.1-15l0 0-.7-.2c-12.8-3.9-21.9-6.7-28.3-10.5c-5.2-3.1-5.3-4.9-5.3-6.7c0-3.7 1.4-6.5 6.2-9.3c5.4-3.2 13.6-5.1 21.5-5c9.6 .1 20.2 2.2 31.2 5.2c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-6.5-1.7-13.7-3.4-21.1-4.7V216z"/></svg>                   
                      <svg onclick="<?php echo $boton_entre ?>" viewBox="0 0 640 512" style="fill:<?php echo $color_estentrega ?>;margin-right: 18px; " width="30" height="30"><path d="M64 32C28.7 32 0 60.7 0 96V304v80 16c0 44.2 35.8 80 80 80c26.2 0 49.4-12.6 64-32c14.6 19.4 37.8 32 64 32c44.2 0 80-35.8 80-80c0-5.5-.6-10.8-1.6-16H416h33.6c-1 5.2-1.6 10.5-1.6 16c0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16H608c17.7 0 32-14.3 32-32V288 272 261.7c0-9.2-3.2-18.2-9-25.3l-58.8-71.8c-10.6-13-26.5-20.5-43.3-20.5H480V96c0-35.3-28.7-64-64-64H64zM585 256H480V192h48.8c2.4 0 4.7 1.1 6.2 2.9L585 256zM528 368a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM176 400a32 32 0 1 1 64 0 32 32 0 1 1 -64 0zM80 368a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                      <i onclick="window.location.href='inicio.php?modulo=detalles_boleta&idBole=<?php echo $rowGENE['idboleta'] ?>'" style="font-size:24px;vertical-align: middle;" class="fas fa-eye text-light"></i>
                      
                    </div>

                    </td>
                  </tr>
                  <?php } mysqli_close($con);  ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-0">

          </div>

          
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
</div>


