<?php 
// Configura el encabezado para responder con JSON en cada respuesta
header('Content-Type: application/json');

// Incluir el archivo de conexión a la base de datos
include_once 'conect.php'; 

// Conexión a la base de datos
$con = mysqli_connect($host, $user_db, $contra_db, $db);

// Función para obtener los tonos de color activos
function obtenerTonos($con) {
    $query = "SELECT idtono_color, ctono FROM tono_color WHERE vigente = 1";
    $result = mysqli_query($con, $query);
    $tonos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $tonos[] = $row;
    }
    return $tonos;
}

// --- Otras funciones existentes ---
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
        return ['success' => false, 'error' => 'El producto ya existe en la base de datos.'];
    }

    $query = "INSERT INTO producto (nombre_pro, precio_promedio, cantidad_material, estado_pro) 
            SELECT '$nombre', $precio, $material, $estado";

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
    $ruc = mysqli_real_escape_string($con, $data['ruc']);
    $telefo = mysqli_real_escape_string($con, $data['telef']);
    $estado = intval($data['estado']);

    $query = "UPDATE cliente SET 
                nombre_cli = '$nombre', 
                telefono_cli = '$telefo', 
                direccion = '$direc', 
                documento = '$ruc', 
                estado_cli = $estado 
            WHERE idcliente = $idProducto";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Clinica actualizada correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al actualizar Clinica'];
    }
}

// Función para agregar Clinica
function agregarClinica($data, $con) {
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $direc = mysqli_real_escape_string($con, $data['direc']);
    $ruc = mysqli_real_escape_string($con, $data['ruc']);
    $telefo = mysqli_real_escape_string($con, $data['telef']);
    $estado = intval($data['estado']);
    $tipo = intval($data['tipocli']);

    // Verificar si el cliente ya existe
    $checkQuery = "SELECT 1 FROM cliente WHERE nombre_cli = '$nombre' LIMIT 1";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        return ['success' => false, 'error' => 'El cliente ya existe en la base de datos.'];
    }

    $query = "INSERT INTO cliente (nombre_cli, telefono_cli, direccion, documento, estado_cli, idtipo_cliente) 
            SELECT '$nombre', '$telefo', '$direc', '$ruc', $estado, $tipo";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Cliente agregado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al agregar'];
    }
}

// Función para editar Tono
function editarTono($data, $con) {
    $idProducto = intval($data['id']);
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $estado = intval($data['estado']);

    $query = "UPDATE tono_color SET 
                ctono = '$nombre', 
                vigente = $estado 
            WHERE idtono_color = $idProducto";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Actualizado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al actualizar'];
    }
}

// Función para agregar Tono
function agregarTono($data, $con) {
    $nombre = mysqli_real_escape_string($con, $data['nombre']);
    $estado = intval($data['estado']);

    // Verificar si el tono ya existe
    $checkQuery = "SELECT 1 FROM tono_color WHERE ctono = '$nombre' LIMIT 1";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        return ['success' => false, 'error' => 'Ya existe en la base de datos.'];
    }
    $query = "INSERT INTO tono_color (ctono, vigente) 
            SELECT '$nombre', $estado";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Tono agregado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al agregar'];
    }
}


// EDITAR DETALLE VOLETA
function editarDetalle($data, $con) {
    $idDetalle = intval($data['id']);
    $tonoColor = intval($data['tono_color']);
    $descripcion = mysqli_real_escape_string($con, $data['descripcion']);
    $estado = mysqli_real_escape_string($con,$data['estado']);

    $query = "UPDATE detalle_boleta SET 
                tono_color = $tonoColor, 
                descripcion = '$descripcion', 
                mod_ant_cub = '$estado' 
            WHERE iddetalle_boleta = $idDetalle";

    if (mysqli_query($con, $query)) {
        return ['success' => true, 'message' => 'Detalle de boleta actualizado correctamente'];
    } else {
        return ['success' => false, 'error' => 'Error al actualizar el detalle de boleta'];
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
        'agregar' => 'agregarClinica'
        // 'eliminar' => 'eliminarClinica'
    ],
    'tono' => [
        'editar' => 'editarTono',
        'agregar' => 'agregarTono'
    ],
    'persona' => [
        'editar' => 'editarPersona',
        'agregar' => 'agregarPersona'
    ],
    'detalle' => [
        'editar' => 'editarDetalle'
    ]
];

// --- Manejo de Solicitudes ---
// Ahora, TODAS las solicitudes se manejan vía POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['accion'])) {
        // Si la acción es obtenerTonos, se retorna mediante POST
        if ($data['accion'] === 'obtenerTonos') {
            echo json_encode(obtenerTonos($con));
            exit;
        }

        // Para las demás acciones se requiere también 'tipo'
        if (isset($data['tipo'])) {
            if (isset($acciones[$data['tipo']][$data['accion']])) {
                $funcion = $acciones[$data['tipo']][$data['accion']];
                $resultado = $funcion($data, $con);
                echo json_encode($resultado);
            } else {
                echo json_encode(['success' => false, 'error' => 'Acción o tipo no válidos.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Faltan datos necesarios (tipo).']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Acción no especificada.']);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Cerrar la conexión a la base de datos
mysqli_close($con);
?>
