<!DOCTYPE html>
<html lang="es">
<style>
    .mini-checkboxes label {
        font-size: 12px; /* Letra pequeña */
        margin-right: 5px; /* Espaciado entre checks */
        display: inline-flex;
        align-items: center;
    }

    .mini-checkboxes input {
        transform: scale(0.8); /* Reduce el tamaño de los checkboxes */
        margin-right: 2px; /* Espaciado entre checkbox y texto */
    }
</style>
<?php 
  session_start();
  if(isset($_SESSION['id'])==false){
      header("location: index.php");
  }

  $modulo = $_REQUEST['modulo']??'';
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LABORATORIO</title>

<!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- SWEETALERET -->
  <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <!-- <link rel="stylesheet" href="plugins/datepicker/datepicker.scss">-->
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
</head>



<body class="hold-transition sidebar-mini layout-fixed  dark-mode">

<div class="wrapper">
<script src="plugins/jquery/jquery.min.js"></script>


  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a onclick="campana('aa')" class="nav-link" style="cursor: pointer;">INICIO</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <!-- Messages Dropdown Menu -->
      <!-- Notifications Dropdown Menu -->
      <?php 
      include_once 'conect.php';
      $con =mysqli_connect($host,$user_db,$contra_db,$db);
      $query = "SELECT 
    COUNT(CASE WHEN fecha_entrega = CURDATE() THEN 1 END) AS hoy,
    COUNT(CASE WHEN fecha_entrega = DATE_ADD(CURDATE(), INTERVAL 1 DAY) THEN 1 END) AS manana,
    COUNT(CASE WHEN fecha_entrega = DATE_ADD(CURDATE(), INTERVAL 2 DAY) THEN 1 END) AS pasado_manana
FROM boleta
WHERE 
    fecha_entrega BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 DAY)
    AND estado_entrega = 0;
";
      $respuesta = mysqli_query($con,$query);
      $row = mysqli_fetch_assoc($respuesta);
      $tot_3dias = $row['hoy']+$row['manana']+$row['pasado_manana'];
      mysqli_close($con);
      ?>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell" style="font-size: 32px;color:#ffeeba"></i>
          <span class="badge badge-danger navbar-badge"><b style="font-size:17px ;"><?php echo $tot_3dias ?></b></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><b style="color: salmon;font-size:17px"><?php echo $tot_3dias ?></b> PENDIENTES</span>
          <div class="dropdown-divider"></div>
          <a onclick="campana('hoy')" class="dropdown-item">
            <i class="fas fa-briefcase mr-2"></i> <?php echo $row['hoy']?> por entregar
            <span class="float-right text-muted text-sm">hoy</span>
          </a>
          <div class="dropdown-divider"></div>
          <a onclick="campana('mañana')" class="dropdown-item">
            <i class="fas fa-briefcase mr-2"></i> <?php echo $row['manana']?> por entregar
            <span class="float-right text-muted text-sm">Mañana</span>
          </a>
          <div class="dropdown-divider"></div>
          <a onclick="campana('pasado')" class="dropdown-item">
            <i class="fas fa-briefcase mr-2"></i> <?php echo $row['pasado_manana']?> por entregar
            <span class="float-right text-muted text-sm">Pasado Mañana </span>
          </a>
        </div>
      </li>
      
      <li class="nav-item">
        <a class="nav-link nav-icon " onclick="cerrar_sesion()" role="button">
          <i style="color:salmon;font-size: 34px;" class="fas">
          <svg width="24" height="24" fill="currentColor" class="bi bi-escape" viewBox="0 0 16 16">
            <path d="M8.538 1.02a.5.5 0 1 0-.076.998 6 6 0 1 1-6.445 6.444.5.5 0 0 0-.997.076A7 7 0 1 0 8.538 1.02"/>
            <path d="M7.096 7.828a.5.5 0 0 0 .707-.707L2.707 2.025h2.768a.5.5 0 1 0 0-1H1.5a.5.5 0 0 0-.5.5V5.5a.5.5 0 0 0 1 0V2.732z"/>
          </svg>
          </i>
        </a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a onclick="campana('aa')" class="brand-link" style="cursor: pointer;">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">LABORATORIO</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['ctipo_user'];  ?></a>
          <a href="#" class="d-block"><?php echo $_SESSION['nombre'];  ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
        with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tooth"></i>
              <p>
                MENU
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a onclick="campana('aa')"  class="nav-link <?php echo ($modulo=="voletas"||$modulo=="")?"active":" ";?>"  style="cursor: pointer;">
                <i class="far  nav-icon">
                  <svg width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                  </svg>
                </i> 
                  <p>BOLETAS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="inicio.php?modulo=reportes"  class="nav-link <?php echo ($modulo=="reportes")?"active":" ";?>">
                  <i class="far fa-chart-bar nav-icon"></i>
                  <p>REPORTES</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="inicio.php?modulo=clinicas" class="nav-link <?php echo ($modulo=="clinicas")?"active":" ";?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ADMIN</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->

  <!-- /.content-wrapper -->
  <?php 

  if($modulo=='voletas'||$modulo==''){
    include_once "voletas.php";   

  }elseif($modulo=='reportes'){
    include_once "reportes.php";
  }elseif($modulo=='clinicas'){
    include_once "admin.php";
  }elseif($modulo=='crear'){
    include_once "crear_voleta.php";
  }elseif($modulo=='detalles_boleta'){
    include_once "detalles_boleta.php";
  }

    
  ?>  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->


<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- flot -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="plugins/flot/plugins/jquery.flot.resize.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="plugins/flot/plugins/jquery.flot.pie.js"></script>

<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!--<script src="plugins/datepicker/datepicker.js"></script>-->
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>

<script>

</script>
</body>
</html>
