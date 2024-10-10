<script src="crear_detalles.js"></script>
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Flot Charts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Flot</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">


        <div class="row">
          <div class="col-md-6">
            <!-- Line chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Line Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="line-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Area chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Area Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="area-chart" style="height: 338px;" class="full-width-chart"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col -->

          <div class="col-md-12">
            <!-- Bar chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  <span style="margin-left: 10px;"> INGRESOS POR CLINICA</span>
                  <!-- Formulario dentro de la cabecera del card -->
                  <div class="form-group d-inline-block ml-3 mb-0">
                    <!-- Select con clase pequeña y control de ancho -->
                    
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <select class="form-control form-control-sm w-25" id="yearSelector" name="yearSelector" >
                    <option value="1">GLOBAL</option>
                  </select>
              </div>
              <div class="card-body">
                <div id="bar-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->

            <!-- Donut chart -->
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-chart-bar"></i>
                  Donut Chart
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div id="donut-chart" style="height: 300px;"></div>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script>
    // Obtener el select por su ID
    const selectAnio = document.getElementById("yearSelector");

    // Establecer el año inicial y el año actual
    const yearStart = 2020;
    const currentYear = new Date().getFullYear();

    // Generar opciones para cada año desde el año inicial hasta el actual
    for (let year = yearStart; year <= currentYear; year++) {
      let option = document.createElement("option");
      option.value = year;
      option.text = year;
      selectAnio.add(option);
    }
    


// function motrar_barras(anio){
  
//   var bar_data = {
//       data : [[1,10], [2,8], [3,4], [4,13], [5,17], [6,9]],
//       bars: { show: true }
//     }
//     $.plot('#bar-chart', [bar_data], {
//       grid  : {
//         borderWidth: 1,
//         borderColor: '#f3f3f3',
//         tickColor  : '#f3f3f3'
//       },
//       series: {
//         bars: {
//           show: true, barWidth: 0.4, align: 'center',
//         },
//       },
//       colors: ['#3c8dbc'],
//       xaxis : {
//         ticks: [[1,anio], [2,'February'], [3,'March'], [4,anio], [5,'May'], [6,anio]]
//       }
//     })
// }

// Función para mostrar el gráfico de barras
function mostrar_barras_borrar(c) {
    // Creamos una variable para almacenar los datos JSON
    var datos = [];

    // Realizamos la solicitud AJAX para obtener los datos
    $.ajax({
        url: "funciones.php",  // La URL del servidor
        method: "GET",         // Usamos el método GET
        async: false,          // Sincrónico para esperar los datos antes de continuar
        data: {funcion: "extrae_datos_bar", cc: c},  // Enviamos el código del cliente y el año
        dataType: "json",      // Indicamos que esperamos un JSON como respuesta
        success: function(respu) {
            // Guardamos la respuesta en la variable `datos`
            var datos = Array.isArray(respu) ? respu : [respu];
            //datos = respu;
            
            // Luego procesamos los datos para el gráfico
            var bar_data = {
                data: [],
                bars: { show: true }
            };

            // Iteramos sobre los datos y los agregamos al gráfico
            datos.forEach((item, index) => {
                var mes = index + 1;  // Asignamos un índice que va del 1 en adelante
                var monto = parseFloat(item.monto_total);  // Aseguramos que el monto sea un número
                bar_data.data.push([mes, monto]);  // Añadimos el dato en el formato [mes, monto]
            });

            // Configuramos el gráfico con los datos recibidos
            $.plot('#bar-chart', [bar_data], {
                grid: {
                    borderWidth: 1,
                    borderColor: '#f3f3f3',
                    tickColor: '#f3f3f3',
                    
                },
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.2,
                        align: 'center',
                    },
                },
                colors: ['#3c8dbc'],
                xaxis: {
                    ticks: [...datos.map((item, index) => [index + 1, item.nombre_cli+'<br> S/.('+item.monto_total+')']),
                    [datos.length + 1, 'Tick Ficticio 1'],  // Primer tick ficticio
                    [datos.length + 2, 'Tick Ficticio 2'] ], // Segundo tick ficticio PARA QUE EL GRAFICO NO MUERA SI SE TRAEN MENOS DE 3 DATOS
                    tickLength: 0,  // Para evitar que los números en el eje X se solapen con los nombres
                    rotateTicks: 45,  // Rotamos los nombres de los clientes si es necesario
                    font: {
                            size: 14, // Ajusta este valor según lo necesites
                            color: '#FFFFFF'
                        }
                }
            });
        },
        error: function(xhr, status, error) {
            alert("Error en la solicitud AJAX: " + status + error);
            console.error(xhr.responseText);  // Mostramos el error en la consola si falla
        }
    });
}

// Evento que captura el cambio en el selector de año
$(document).ready(function() {
    // Asume que el código del cliente es conocido (puedes pasarlo dinámicamente si es necesario)
    var cliente_codigo = 'cliente_codigo';  // Cambia esto por el valor correcto o pásalo dinámicamente

    // Capturamos el evento de cambio en el selector de año
    $('#yearSelector').change(function() {
        // Obtenemos el año seleccionado
        var selectedYear = $(this).val();
        
        // Llamamos a la función `mostrar_barras` con el año seleccionado
        mostrar_barras_borrar(selectedYear);
    });

    // Inicializamos el gráfico con el año predeterminado al cargar la página
    var defaultYear = $('#yearSelector').val();  // Obtenemos el año por defecto
    mostrar_barras_borrar(defaultYear);  // Muestra el gráfico con el año inicial
});


  </script>


<script>
  $(function () {
    
    /*
     * LINE CHART
     * ----------
     */
    //LINE randomly generated data

    var sin = [],
        cos = []
    for (var i = 0; i < 14; i += 0.5) {
      sin.push([i, Math.sin(i)])
      cos.push([i, Math.cos(i)])
    }
    var line_data1 = {
      data : sin,
      color: '#3c8dbc'
    }
    var line_data2 = {
      data : cos,
      color: '#00c0ef'
    }
    $.plot('#line-chart', [line_data1, line_data2], {
      grid  : {
        hoverable  : true,
        borderColor: '#f3f3f3',
        borderWidth: 1,
        tickColor  : '#f3f3f3'
      },
      series: {
        shadowSize: 0,
        lines     : {
          show: true
        },
        points    : {
          show: true
        }
      },
      lines : {
        fill : false,
        color: ['#3c8dbc', '#f56954']
      },
      yaxis : {
        show: true
      },
      xaxis : {
        show: true
      }
    })
    //Initialize tooltip on hover
    $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
      position: 'absolute',
      display : 'none',
      opacity : 0.8
    }).appendTo('body')
    $('#line-chart').bind('plothover', function (event, pos, item) {

      if (item) {
        var x = item.datapoint[0].toFixed(2),
            y = item.datapoint[1].toFixed(2)

        $('#line-chart-tooltip').html(item.series.label + ' of ' + x + ' = ' + y)
          .css({
            top : item.pageY + 5,
            left: item.pageX + 5
          })
          .fadeIn(200)
      } else {
        $('#line-chart-tooltip').hide()
      }

    })
    /* END LINE CHART */

    /*
     * FULL WIDTH STATIC AREA CHART
     * -----------------
     */
    var areaData = [[2, 88.0], [3, 93.3], [4, 102.0], [5, 108.5], [6, 115.7], [7, 115.6],
      [8, 124.6], [9, 130.3], [10, 134.3], [11, 141.4], [12, 146.5], [13, 151.7], [14, 159.9],
      [15, 165.4], [16, 167.8], [17, 168.7], [18, 169.5], [19, 168.0]]
    $.plot('#area-chart', [areaData], {
      grid  : {
        borderWidth: 0
      },
      series: {
        shadowSize: 0, // Drawing is faster without shadows
        color     : '#00c0ef',
        lines : {
          fill: true //Converts the line chart to area chart
        },
      },
      yaxis : {
        show: false
      },
      xaxis : {
        show: false
      }
    })

    /* END AREA CHART */

    /*
     * BAR CHART
     * ---------
     */

    /* END BAR CHART */

    /*
     * DONUT CHART
     * -----------
     */

    var donutData = [
      {
        label: 'Series2',
        data : 30,
        color: '#3c8dbc'
      },
      {
        label: 'Series3',
        data : 20,
        color: '#0073b7'
      },
      {
        label: 'Series4',
        data : 50,
        color: '#00c0ef'
      }
    ]
    $.plot('#donut-chart', donutData, {
      series: {
        pie: {
          show       : true,
          radius     : 1,
          innerRadius: 0.5,
          label      : {
            show     : true,
            radius   : 2 / 3,
            formatter: labelFormatter,
            threshold: 0.1
          }

        }
      },
      legend: {
        show: false
      }
    })
    /*
     * END DONUT CHART
     */

  })

  /*
   * Custom Label formatter
   * ----------------------
   */
  function labelFormatter(label, series) {
    return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">'
      + label
      + '<br>'
      + Math.round(series.percent) + '%</div>'
  }
</script>
