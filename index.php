<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LABORATORIO HEBERT</title>

  <!-- Google Font: Source Sans Pro -->
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page dark-mode">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index2.html" class="h1"><b>LABORATORIO</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Iniciar sesion</p>
      <?php 
      if(isset($_REQUEST['login'])){

        $user = $_REQUEST['user'];
        $contra = $_REQUEST['contra'];
        include_once "conect.php";
        $con =mysqli_connect($host,$user_db,$contra_db,$db);
        $query = "SELECT idusuario,nombre_usuario,TIPO.ctipouser AS CTIPO from usuario USER 
        LEFT JOIN tipo_usuario TIPO ON USER.tipo_usuario = TIPO.idtipo_usuario
        where usuario='" . $user . "' and contrasena='" . $contra . "';  ";
        $respuesta = mysqli_query($con,$query);
        $row = mysqli_fetch_assoc($respuesta);
        if($row){
            session_start();
            $_SESSION['nombre']=$row['nombre_usuario'];
            $_SESSION['id']=$row['idusuario'];
            $_SESSION['ctipo_user']=$row['CTIPO'];
            mysqli_close($con);
            header("location: inicio.php");
        }
        else{
      ?> 
        <div class="alert alert-danger" role="alert">
            Usuario o Contraseña incorrecta
        </div>
      <?php    
          mysqli_close($con);
        }
      }
      ?>
      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Usuario" required name="user">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Contraseña " required name="contra">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-success btn-block" name="login">INGRESAR</button>
          </div>
          <!-- /.col -->
        </div>
      </form>


    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
