

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

$(document).ready(function(){
    
      //// calendario
      
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

});
