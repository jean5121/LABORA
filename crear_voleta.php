<script src="plugins/jquery/jquery.min.js"></script>

<script src="crear_detalles.js">


</script>
<style>
  #canti{
    outline: none ;
    box-shadow: none ;
    border: #ffc107 4px solid ;
    height: 8px;

  }
  
  #total{
    outline: none ;
    box-shadow: none ;
    border: salmon 2px solid ;
    color:salmon; 
    font-weight: bold;font-size: 30px;
  }
</style>
  
<script type="text/javascript">

window.onload = lanzador;


</script>

<div class="content-wrapper">
<section class="content-header">

</section>
  <div class="col-11 container-fluid t-2">
    <div class="card card-success">
              <div class="card-header row">
                <div class="col-3">
                  <h4 class="card-title">CREAR NUEVA BOLETA</h4>
                  
                </div>
                <div class="col-3">
                  
                </div>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="inicio.php?modulo=detalles_boleta" method="POST" id="formulario">
                <div class="card-body" >
                  <div class="row">
                      <div class="form-group col-3">
                        <label>SELECCIONAR CLINICA:</label>
                        <select id="contenedor_clinicas"  name="contenedor_clinicas" required class="form-control select2 select2-success" style="width: 100%;">                         
                        </select>
                      </div>
                      <div class="form-group col-3 ">
                      <label>Fecha entrega:</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">                       
                            <input type="date" id="f_entrega"  name="f_entrega"  class="form-control">                        
                        </div>
                      </div>
                      <div class="col-2"></div>
                      <div class="col-3">
                            <div id="canti" class="info-box">
                              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file"></i></span>
                              <div  class="info-box-content">               
                                <span class="info-box-number">
                                  <input name="num_elemetos" type="number"  required onchange="llenarElementosPHP($('#num_elemetos').val())" requ placeholder="Elementos" min="1" max="1000" id="num_elemetos" class="form-control">                             
                                </span>
                              </div>            
                            </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-3">
                        <label>SELECCIONAR ODONTOLOGO:</label>
                        <select id="contenedor_odontologo"  name="contenedor_odontologo" required class="form-control select2 select2-success" style="width: 100%;">                         
                        </select>
                      </div>
                  </div>
                  <div>
                                
                      </div>
                    <div class="divider py-1 card card-info" ></div>
                  </div>
                <div id="contenedor">     
                      
                </div>

                <!-- /.card-body -->
                
                <div class="card-footer row">
                      <div class="col-4"></div>
                      <div class="col-2">
                      
                        <button type="submit" id="enviar" name="enviar" class="btn btn-success">                   
                            <i class="fas fa-save fa-3x"></i><br>                       
                          GUARDAR
                        </button>
                      </div>
                      <div class="col-3"></div>
                      <div class=" col-2">
                        <label >TOTAL</label>
                        <input  id="total" name="total" required type="number" min="1" readonly class="form-control" value="0">
                        
                      </div>
                </div>
                
              </form>
    </div>
  </div>  
</div>
<script>

$(function () {

})
</script>