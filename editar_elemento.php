<?php 

// Configura el encabezado para responder con JSON
header('Content-Type: application/json');

// Incluir el archivo de conexión a la base de datos
include_once 'conect.php'; 

// Conexión a la base de datos
$con = mysqli_connect($host, $user_db, $contra_db, $db);

// Función para editar producto
function editarProducto($data, $con) {
    $idProducto = intval($data['id']);
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $precio = floatval($data['precio']);
    $material = floatval($data['material']);
    $estado = intval($data['estado']);

    $query = "UPDATE producto SET 
                nombre_pro = '$nombre', 
                precio_promedio = $precio, 
                cantidad_material = $material, 
                estado_pro = $estado 
            WHERE idproducto = $idProducto";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Producto actualizado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al actualizar el producto.'];
    }
}

// Función para agregar producto
function agregarProducto($data, $con) {
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $precio = floatval($data['precio']);
    $material = floatval($data['material']);
    $estado = intval($data['estado']);

// Verificar si el producto ya existe
$checkQuery = "SELECT 1 FROM producto WHERE nombre_pro = '$nombre' LIMIT 1";
$result = mysqli_query($con, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    // Si el producto ya existe, devuelve un error
    return ['success' => false, 'error' => 'El producto ya existe en la base de datos.'];
}

    $query = "INSERT INTO producto (nombre_pro, precio_promedio, cantidad_material, estado_pro) 
            SELECT  '$nombre', $precio, $material, $estado";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Producto agregado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al agregar el producto.'];
    }
}

// Función para eliminar producto
function eliminarProducto($id, $con) {
    $idProducto = intval($id);

    $query = "DELETE FROM producto WHERE idproducto = $idProducto";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Producto eliminado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al eliminar el producto.'];
    }
}

// Función para editar CLINICA
function editarClinica($data, $con) {
    $idProducto = intval($data['id']);
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $direc = mysqli_real_escape_string($con, $data['direc']);
    $refe = mysqli_real_escape_string($con, $data['refe']);
    $ruc = mysqli_real_escape_string($con, $data['ruc']);
    $telefo = mysqli_real_escape_string($con, $data['telef']);
    $estado = intval($data['estado']);

    $query = "UPDATE clinica SET 
                nombre_cli = '$nombre', 
                telefono_cli = '$telefo', 
                direccion_cli = '$direc', 
                referencia_cli = '$refe', 
                ruc_cli = '$ruc', 
                estado_cli = $estado 
            WHERE idclinica  = $idProducto";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Clinica actualizada correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al actualizar Clinica'];
    }
}

function agregarClinica($data, $con) {
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $direc = mysqli_real_escape_string($con, $data['direc']);
    $refe = mysqli_real_escape_string($con, $data['refe']);
    $ruc = mysqli_real_escape_string($con, $data['ruc']);
    $telefo = mysqli_real_escape_string($con, $data['telef']);
    $estado = intval($data['estado']);

// Verificar si el producto ya existe
$checkQuery = "SELECT 1 FROM clinica WHERE nombre_cli = '$nombre' LIMIT 1";
$result = mysqli_query($con, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    // Si el producto ya existe, devuelve un error
    return ['success' => false, 'error' => 'La clinica ya existe en la base de datos.'];
}

    $query = "INSERT INTO clinica (nombre_cli, telefono_cli, direccion_cli,referencia_cli,ruc_cli, estado_cli) 
            SELECT  '$nombre', '$direc', '$refe', '$ruc', '$telefo',$estado";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'clinica agregada correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al agregar la clinica.'];
    }
}
// Mapa de funciones para cada tipo de entidad
$acciones = [
    'producto' => [
        'editar' => 'editarProducto',
        'agregar' => 'agregarProducto',
        'eliminar' => 'eliminarProducto'
    ],
    'clinica' => [
        'editar' => 'editarClinica',
        'agregar' => 'agregarClinica',
        'eliminar' => 'eliminarClinica'
    ],
    'persona' => [
        'editar' => 'editarPersona',
        'agregar' => 'agregarPersona'
    ]
];

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer los datos JSON recibidos
    $data = json_decode(file_get_contents('php://input'), true);

    // Validar que los campos necesarios estén presentes
    if (isset($data['accion']) && isset($data['tipo'])) {
        // Verificar que la acción y tipo sean válidos
        if (isset($acciones[$data['tipo']][$data['accion']])) {
            // Llamar a la función correspondiente basada en el tipo y acción
            $funcion = $acciones[$data['tipo']][$data['accion']];
            $resultado = $funcion($data, $con);
            echo json_encode($resultado);
        } else {
            echo json_encode(['success' => false, 'error' => 'Acción o tipo no válidos.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}

// Cerrar la conexión a la base de datos
mysqli_close($con);



?>