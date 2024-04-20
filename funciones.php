
<?php    

if(isset($_GET['funcion']) && !empty($_GET['funcion'])) {
    $funcion = $_GET['funcion'];
    
    //En función del parámetro que nos llegue ejecutamos una función u otra
    switch($funcion) {
        case 'llenarElementos': 
            $cantidad = $_GET['cantidad'];
            CrearElements($cantidad);
            break;
        case 'llenarClinicas':
            crearClinicas();
            break;
        case 'llenarOdonto':      
            crearOdonto();
            break;
        case 'detalles_pro':
            $cidpro = $_GET['idproduc'];        
            devol_detalles_pruduc($cidpro);
            break;
        case 'cambio_estenvio':
            $idbo = $_GET['cod'];        
            cambio_est_envio($idbo);
            break;
        case 'salir':        
            salirphp();
            break;
        case 'llenar_med_pago':        
            llenar_php_med_pagos();
            break;
        
    }
}

function llenar_php_med_pagos(){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idmedio_pago,cmedio from medio_pago where est_med =1 ORDER BY cmedio ASC";
    $respuesta = mysqli_query($con,$query);
        
    $clinicas ='';

    while ($row = mysqli_fetch_assoc($respuesta)) {
        $clinicas.='<option value="'.$row["idmedio_pago"].'">'.$row["cmedio"].'</option>';
    }
    echo $clinicas;
    mysqli_close($con);
}

function cambio_est_envio($id){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "UPDATE boleta SET estado_entrega = 1 WHERE idboleta=$id";
    $respuesta = mysqli_query($con,$query);
    mysqli_close($con);
}

function CrearElements($canti){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idproducto,nombre_pro,precio_promedio from producto where estado_pro =1";
    $respuesta = mysqli_query($con,$query);
        
    $productos ='';
    $cont =1;
    while ($row = mysqli_fetch_assoc($respuesta)) {
        $productos.='<option value="'.$row["idproducto"].'">'.$cont.'.-'.$row["nombre_pro"].'</option>';
        ($cont++);
    }

    for ($i=1; $i <=$canti ; $i++) { 
        
        echo'
        <div class="row p-3" style="color:#28a745">
        <p>'.($i).'.-</p> 
        <div class="form-group col-3">                                       
            <label>PRODUCTO</label>
            <select id="producto'.($i).'" name="producto'.($i).'" onchange="clik_producto($(`#producto'.($i).'`).val(),'.($i).')" class="form-control select2 select2-success" required style="width: 100%;">
                <option value="" disabled selected>Elige un producto</option>
                '.$productos.'                     
            </select>
        </div>
        <div class="form-group col-1">
            <label for="exampleInputEmail1">CANTIDAD</label>
            <input id="cantidad'.($i).'" name="cantidad'.($i).'" onchange="cambio_cant_precio('.($i).')" value="1" required type="number"  min="1" max="60000" class="form-control">
        </div>                     
        <div class="form-group col-2">
            <label for="exampleInputEmail1">PRECIO UNIDAD</label>
            <input id="precioU'.($i).'" name="precioU'.($i).'"  onchange="cambio_cant_precio('.($i).')" required type="number"  min="1" class="form-control" >
        </div>
        <div class="form-group col-3">
            <label for="exampleInputEmail1">ESPECIFICACIONES</label>
            <textarea id="descripcion'.($i).'" name="descripcion'.($i).'" maxlength="200" rows="1" cols="" class="form-control"></textarea>
        </div>
        <div class="form-group col-2">
            <label for="exampleInputEmail1">SUB TOTAL</label>
            <input id="subtotal'.($i).'" name="subtotal'.($i).'" value="0" required type="number" class="form-control" style="color:salmon; font-weight: bold;font-size: 20px;" readonly>
        </div>  
    </div>                 ';

    }
    mysqli_close($con);
}
function crearClinicas(){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idclinica,nombre_cli from clinica where estado_cli =1 ORDER BY nombre_cli ASC";
    $respuesta = mysqli_query($con,$query);
        
    $clinicas ='<option value="" disabled selected>Elige una clinica</option>';

    while ($row = mysqli_fetch_assoc($respuesta)) {
        $clinicas.='<option value="'.$row["idclinica"].'">'.$row["nombre_cli"].'</option>';
    }
    echo $clinicas;
    mysqli_close($con);
}
function crearOdonto(){
    include_once 'conect.php';
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT idodontologo,nombre_odo from odontologo where est_odo =1 ORDER BY nombre_odo ASC";
    $respuesta = mysqli_query($con,$query);
        
    $odontologos ='<option value="" disabled selected>Elige un Odontologo</option>';

    while ($row = mysqli_fetch_assoc($respuesta)) {
        $odontologos.='<option value="'.$row["idodontologo"].'">'.$row["nombre_odo"].'</option>';
    }
    echo $odontologos;
    mysqli_close($con);
}

function devol_detalles_pruduc($idproduc){
    include_once 'conect.php';

    $Jdetalle_pro = array();
    $con =mysqli_connect($host,$user_db,$contra_db,$db);
    $query = "SELECT precio_promedio,nombre_pro from producto where idproducto = $idproduc";
    $respuesta = mysqli_query($con,$query);
    while ($row = mysqli_fetch_assoc($respuesta)) {
        $Jdetalle_pro['status'] = 'ok';
        $Jdetalle_pro['resultado'] = $row;
    }
    echo json_encode($Jdetalle_pro);
    mysqli_close($con);
}

function salirphp(){
    session_start();
    session_destroy();
}


?>
