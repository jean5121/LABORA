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

    $query = "INSERT INTO producto (nombre_pro, precio_promedio, cantidad_material, estado_pro) 
            VALUES ('$nombre', $precio, $material, $estado)";

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

// Mapa de funciones para cada tipo de entidad
$acciones = [
    'producto' => [
        'editar' => 'editarProducto',
        'agregar' => 'agregarProducto',
        'eliminar' => 'eliminarProducto'
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