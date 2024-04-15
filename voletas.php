<script src="plugins/jquery/jquery.min.js"></script>
<script src="crear_detalles.js"></script>

<style>
  .dataTables_filter input[type="search"] {
    border: 1px solid salmon;
    color: #fff3cd;
}

</style>
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
      <div class="container-fluid">
        <div class="row">
        
          <div class="col-12">
            <div class="card">
              <div class="card-header row">
                <div class="col-md-3">
                <button type="button" id="btn_no_pagado" onclick="aa()" class="btn btn-danger btn-block">
                  <i class="fa fa-times-circle"></i> 15 SIN PAGAR</button>
                </div>
                <div class="col-md-5">
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
                    <th>ADELANTO</th>
                    <th>ESTADO</th>                 
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td>Trident</td>
                    <td>Internet
                      Explorer 4.0
                    </td>
                    <td>Win 95+</td>
                    <td>CLINICA FELICIUM</td>
                    <td>JEAN CARLOS CARRASCO CUTISACA</td>
                    <td>6</td>
                    <td>A</td>
                  </tr>
                  <tr>
                    <td>Trident</td>
                    <td>Internet
                      Explorer 5.0
                    </td>
                    <td>Win 95+</td>
                    <td>5</td>
                    <td>C</td>
                    <td>6</td>
                    <td>A</td>
                  </tr>
                  <tr>
                    <td>Trident</td>
                    <td>Internet
                      Explorer 5.5
                    </td>
                    <td>Win 95+</td>
                    <td>5.5</td>
                    <td>A</td>
                    <td>6</td>
                    <td>A</td>
                  </tr>
                  <tr>
                    <td>Trident</td>
                    <td>Internet
                      Explorer 6
                    </td>
                    <td>Win 98+</td>
                    <td>6</td>
                    <td>A</td>
                    <td>6</td>
                    <td>A</td>
                  </tr>
                  <tr>
                    <td>Trident</td>
                    <td>Internet Explorer 7</td>
                    <td>Win XP SP2+</td>
                    <td>7</td>
                    <td>A</td>
                    <td>6</td>
                    <td>A</td>
                  </tr>
                  <tr>
                    <td>Trident</td>
                    <td>AOL browser (AOL desktop)</td>
                    <td>Win XP</td>
                    <td>6</td>
                    <td>A</td>
                    <td>6</td>
                    <td>A</td>
                  </tr>
              
                  </tfoot>
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
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
