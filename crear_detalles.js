
function llenarClinicas(){
    
    $.ajax({
        url: "funciones.php",
        method: "GET",
        async: false,
        data: {funcion: "llenarClinicas"},
        dataType: "text",
        success: function(respuesta) {
        const contenedor = $("#contenedor_clinicas");
        contenedor.innerHTML=``; 
        const elemento =respuesta;
        contenedor.html(elemento);
        },
        
        });
}
function lanzador(){
  llenarClinicas();
  llenar_odontologos();
}

///LLAMAR AL MODULO CREAR
function link_crear(){
  location.href ="inicio.php?modulo=crear";
}


function llenarElementosPHP(n){
    
    if(n>=1 && n<=1000){
        $.ajax({
            url: "funciones.php",
            method: "GET",
            async: false,
            data: {funcion: "llenarElementos",cantidad:n},
            dataType: "text",
            success: function(respuesta) {
            const contenedor = $( "#contenedor");
            contenedor.innerHTML=``; 
            const elemento =respuesta;
            contenedor.html(elemento);
            },
            
            });
        }else{
            alerta_cElementos();         
        }
}

function clik_producto(producto,nfila){
    //alert(producto+"numero de fila"+nfila);
       // $idpruducto = producto;
        var numero_fila = nfila;

        $.ajax({
            type:'GET',
            url:'funciones.php',
            async: false,
            dataType: "json",
            data:{funcion: "detalles_pro",idproduc:producto},
            success:function(data){                
                if(data.status == 'ok'){                                    
                    $(`#precioU${numero_fila}`).val(data.resultado.precio_promedio);
                    cambio_cant_precio(numero_fila);                                                     
                }else{                   
                    alert("User not found...");
                }
            }
        }); 
}
function cambio_cant_precio(F){

    var precioUnidad = $(`#precioU${F}`).val();
    var cantidad = $(`#cantidad${F}`).val();   
    $(`#subtotal${F}`).val((cantidad*precioUnidad));
    //SUMA TOTAL
    var num_Ele = $('#num_elemetos').val();
    
    var suma_total =0 ;
    for (let index = 1; index <= num_Ele; index++) {
        var numtemp =  $(`#subtotal${index}`).val();
        suma_total = parseFloat(suma_total)+ parseFloat(numtemp);
    }
    $('#total').val(suma_total);
}

function alerta_cElementos(){
    Swal.fire({
    position: "center",
    icon: "warning",
    title: "El numero tiene que estar entre 1 y 1000",
    showConfirmButton: false,
    timer: 1500
    });
}
////ALERTA INGRESAR PAGOS
function alert_est_pago(){
    Swal.fire({
    icon: "warning",
    title:"Para cancelar, <b style='color:salmon'>Ingresar pagos.</b>",
    });
}

////(AJAX) LLAMAR PHP PARA LLAMAR ODONTOLOGOS
function llenar_odontologos(){
  $.ajax({
    url: "funciones.php",
    method: "GET",
    async: false,
    data: {funcion: "llenarOdonto"},
    dataType: "text",
    success: function(respuesta) {
    const contenedor = $("#contenedor_odontologo");
    contenedor.innerHTML=``; 
    const elemento =respuesta;
    contenedor.html(elemento);
    },
    
    });
}
  ////INFORMACION DE PAGO
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


  /////LOG OUT (CONFIMACION)
function cerrar_sesion(id) {
  Swal.fire({
          title: "¿Cerrar sesion?",
          icon: "warning",
          dangerMode: 0,
          showDenyButton: true,
          showCancelButton: 0,
          showCloseButton: true,
          confirmButtonColor: '#28a745',
          confirmButtonText: "SI",
          denyButtonText: `No`
      })
      .then((result) => {
/* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            ajax_log_out();
          } else if (result.isDenied) {
              
          }
      });
  }

    ////AJAX ENVIAR PAGO
    function ajax_enviar_pago(idbta,idme,monto,iduser){
      
      $.ajax({
        url: "funciones.php",
        method: "GET",
        async: false,
        data: {funcion: "cargar_pago",cod:idbta,imedio:idme,mot:monto,idus:iduser},
        dataType: "json",
        success:function(respu){ 
          
          let iconomsg = respu.success==1 ? 'success' : 'error';
          Swal.fire({
            position: "center",
            icon: iconomsg,
            title: respu.mensaje,
            showConfirmButton: false,
            timer: 1500
          }).then((result) => {
            // Redirigir a otra página
            window.location.href = "inicio.php?modulo=detalles_boleta&idBole="+respu.idboleta;
        });
        },
        error: function(xhr, status, error) {
          alert("Error en la solicitud AJAX: " + status+error); // Mostrar mensaje de error
          console.error(xhr.responseText); // Mostrar detalle del error en la consola
      }
        
        });
    }
    
////AJAX CAMBIAR F ENTREGA
function ajax_cambio_fentrega(idbta,fetre){
      
  $.ajax({
    url: "funciones.php",
    method: "GET",
    async: false,
    data: {funcion: "cambio_f_entrega",cod:idbta,f:fetre},
    dataType: "json",
    success:function(respu){ 
      
      let iconomsg = respu.success==1 ? 'success' : 'error';
      Swal.fire({
        position: "center",
        icon: iconomsg,
        title: respu.mensaje,
        showConfirmButton: false,
        timer: 1500
      }).then((result) => {
        // Redirigir a otra página
        window.location.href = "inicio.php?modulo=detalles_boleta&idBole="+respu.idboleta;
    });
    },
    error: function(xhr, status, error) {
      alert("Error en la solicitud AJAX: " + status+error); // Mostrar mensaje de error
      console.error(xhr.responseText); // Mostrar detalle del error en la consola
  }
    
    });
}

    ////AJAX LLENAR MEDIO DE PAGO
    function ajax_llenar_med_pago() {
      return new Promise((resolve, reject) => {
          $.ajax({
              url: "funciones.php",
              method: "GET",
              data: { funcion: "llenar_med_pago" },
              dataType: "text",
              success: function (respuesta) {
                  resolve(respuesta); // Resuelve la promesa con la respuesta
              },
              error: function (xhr, status, error) {
                  reject(error); // Rechaza la promesa con el error
              }
          });
      });
  }

async function confirmar_pago(idb,iduser){
  let op = ''; // Declarar op fuera de la promesa

  try {
    op += await ajax_llenar_med_pago(); // Esperar a que la promesa se resuelva
  } catch (error) {
    console.error(error); // Manejar el error si ocurre
    return; // Salir de la función en caso de error
  }
  const { value: formValues } = await  Swal.fire({
    title: "PAGO PARA <p class='badge badge-warning'> C-"+idb+"</p>",
    text:'MEDIO DE PAGO',
    showDenyButton: 0,
    showCancelButton: 0,
    showCloseButton: true,
    confirmButtonColor: '#28a745',
    confirmButtonText: "AGREGAR",
    denyButtonText: `No`,
    html: `
    <form>
        <div class="form-group">
          <input placeholder="Ingresar monto" min="0" id="cantidad" class="swal2-input" type="number" required>
          <select id="med" class="swal2-input"> 
          ${op}
          </select>  
      </div>
    </form>
    `,
    focusConfirm: false,
    preConfirm: () => {
      const cantidad = document.getElementById("cantidad").value;
      if (cantidad > 0) {
      return [
        idb,
        document.getElementById("med").value,
        document.getElementById("cantidad").value,
        iduser,
      ];
      }else {
        Swal.showValidationMessage("La cantidad debe ser mayor que 0");
      }
    }
  });
  if (formValues) {
    ajax_enviar_pago(formValues[0],formValues[1],formValues[2],formValues[3]);
  }
}


async function confirmar_cambio_fentrega(idb){
  
  const { value: formValues } = await  Swal.fire({
    title: "CAMBIO FECHA DE ENTRAGA PARA<p class='badge badge-warning'> C-"+idb+"</p>",
    text:'MEDIO DE PAGO',
    showDenyButton: 0,
    showCancelButton: 0,
    showCloseButton: true,
    confirmButtonColor: '#28a745',
    confirmButtonText: "CAMBIAR",
    denyButtonText: `No`,
    html: `
    <form>
        <div class="form-group text-center">
          <label>FECHA ENTREGA :</label>
                        <div class="input-group date col-md-6 mx-auto" id="reservationdate" data-target-input="nearest">                       
                            <input type="date" id="f_entrega_camb"   class="form-control" required>                        
                        </div> 
        </div>
    </form>
    `,
    focusConfirm: false,
    preConfirm: () => {
      return [
        idb,
        document.getElementById("f_entrega_camb").value,
      ];
      
    }
  });
  if (formValues) {
    ajax_cambio_fentrega(formValues[0],formValues[1]);
  }
}

  function ajax_log_out(){
    $.ajax({
      url: "funciones.php",
      method: "GET",
      async: false,
      data: {funcion: "salir"},
      dataType: "text",
      success: function(respuesta) {
        window.location.href="index.php"
      },
      
      });
  }
/////CAMBIAR ESTADO ENVIO (CONFIMACION)
function confirmar_envio(id) {
  Swal.fire({
          title: "¿Marcar como entregado?",
          html: "<b style='color:salmon'>¡NO se podrá revertir el cambio!</b>",
          icon: "warning",
          dangerMode: true,
          showDenyButton: true,
          showCancelButton: 0,
          showCloseButton: true,
          confirmButtonText: "SI",
          denyButtonText: `No`
      })
      .then((result) => {
/* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            ajax_cambio_est_envio(id)
              
          } else if (result.isDenied) {
              
          }
      });
  }

function ajax_cambio_est_envio(ide){

  $.ajax({
    url: "funciones.php",
    method: "GET",
    async: false,
    data: {funcion: "cambio_estenvio",cod:ide},
    dataType: "text",
    success: function(respuesta) {
      window.location.href="inicio.php?modulo=detalles_boleta&idBole="+ide
    },
    
    });
}

function campana(qry){
  var fechaActual = new Date();

  switch (qry) {
    case 'hoy':
      fechaActual.setDate(fechaActual.getDate());
      break;
    case 'mañana':
      fechaActual.setDate(fechaActual.getDate() + 1);
      
      break;
    case 'pasado':
      fechaActual.setDate(fechaActual.getDate() + 2);
      break; 

  }    
      var fechaFormateada = 'E-'+
      ('0' + fechaActual.getDate()).slice(-2) + '-' +
      ('0' + (fechaActual.getMonth() + 1)).slice(-2) + '-' +
      fechaActual.getFullYear();

  sessionStorage.setItem('busquedaDataTable', fechaFormateada);
  window.location.href = "inicio.php?modulo=voletas";
}


///VER DEUDORES
function dtable_cargar_seach(s){  
      var tabla = $('#example1').DataTable();
      //var valorBusqueda = 'SIN PAGAR' // Obtener el valor del campo de entrada

      // Aplicar la búsqueda en todas las columnas y redibujar la tabla
      tabla.search(s).draw();
}
///CONFIRMAR GUARDADO DE BOLETA
    $(function() {
    var total = $('#total').val();
  const $myForm = $('#formulario')
    .on('submit', function(e) {
      e.preventDefault();
  var total = $('#total').val();
  
      Swal.fire({
        title: '¿Guardar con el monto total de <a style="color:salmon">'+total+'</a>?',
        text: "Una vez guardado, no se podra modificar!",
        showCancelButton: true,
        confirmButtonText: 'Si, Guardar!',
        cancelButtonText: 'No, Cancelar!',
        customClass: {
          confirmButton: 'btn btn-outline-success',
          cancelButton: 'btn btn-outline-danger ml-1'
        },
        buttonsStyling: false
      }).then(function(result) {
        if (result.value) {
          Swal.fire({
            position: "center",
            icon: "success",
            title: "BOLETA GUARDADA!",
            showConfirmButton: false,
            timer: 1400
            
          });
          setTimeout(function() {
            $myForm[0].submit()
          }, 2000); // submit the DOM form
        }
      });
    
    });
    var varesi ='';  
    varesi =sessionStorage.getItem('busquedaDataTable');   
    $("#example1").DataTable({

      ordering: 0,
      "search": {
        "search": varesi || '' 
        },

    "ordering": false,
        "pageLength": 10,
        "lengthMenu": [10, 30, 50, 100],
        "responsive": false,
        "lengthChange": true,
        "autoWidth": false,
        "buttons": ["excel", "pdf", "print"],

      "responsive": 0, "lengthChange": 1, "autoWidth": 0,
      "buttons": ["excel", "pdf", "print",],
      language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "BUSCAR:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },

    }); 
    //table.draw(true);
});




 //////////////////////////////////////////////////////////////7//////REPORTES//////////////////////////////////

 ///AJAX DATOS BAR CHART
function ajax_datos_bar(c){
      
  $.ajax({
    url: "funciones.php",
    method: "GET",
    async: false,
    data: {funcion: "extrae_datos_bar",cc:c},
    dataType: "json",
    success:function(respu){ 

        
    },
    error: function(xhr, status, error) {
      alert("Error en la solicitud AJAX: " + status+error); // Mostrar mensaje de error
      console.error(xhr.responseText); // Mostrar detalle del error en la consola
  }
    
    });
}


