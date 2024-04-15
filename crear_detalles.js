
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
          confirmButtonText: "ACEPTAR",
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


///VER DEUDORES
function aa(){
      var tabla = $('#example1').DataTable();
      var valorBusqueda = '5' // Obtener el valor del campo de entrada

      // Aplicar la búsqueda en todas las columnas y redibujar la tabla
      tabla.search(valorBusqueda).draw();
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
});




