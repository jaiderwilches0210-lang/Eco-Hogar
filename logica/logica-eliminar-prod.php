<?php
session_start();

// Incluir la conexión correctamente (archivo: Eco-Hogar/conexion/conexion.php)
include_once __DIR__ . '/../conexion/conexion.php';

// Variables expuestas al archivo que incluye esta lógica
$producto_seleccionado = null;
$productos_encontrados = [];
$categorias = [];
$mensaje_resultado = '';
$tipo_mensaje = '';

// ----------------------------
// Cargar categorías (para el select)
// ----------------------------
$sql_categorias = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
if ($res_categorias = $conexion->query($sql_categorias)) {
    while ($cat = $res_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
    $res_categorias->free();
}

// ----------------------------
// INACTIVAR PRODUCTO (confirmar_eliminar)
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_eliminar'])) {
    $idPro = intval($_POST['id_producto'] ?? 0);

    if ($idPro > 0) {
        // Cambiamos idEstProEnumFK a 2 (Inactivo)
        $sql = "UPDATE productos SET idEstProEnumFK = 2 WHERE idPro = ? LIMIT 1";
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("i", $idPro);
            if ($stmt->execute()) {
                $mensaje_resultado = "Producto inactivado correctamente.";
                $tipo_mensaje = "success";
            } else {
                $mensaje_resultado = "Error al inactivar producto: " . $stmt->error;
                $tipo_mensaje = "error";
            }
            $stmt->close();
        } else {
            $mensaje_resultado = "Error en la preparación de la consulta.";
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje_resultado = "ID de producto inválido.";
        $tipo_mensaje = "error";
    }
}

// ----------------------------
// SELECCIONAR PRODUCTO (para confirmar antes de inactivar)
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seleccionar_producto'])) {
    $idPro = intval($_POST['id_producto'] ?? 0);

    if ($idPro > 0) {
        $sql = "SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct, p.idEstProEnumFK, c.nomCat
                FROM productos p
                JOIN categoria_producto c ON p.idCatFK = c.idCat
                WHERE p.idPro = ? LIMIT 1";
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("i", $idPro);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $producto_seleccionado = $res->fetch_assoc();
            }
            $stmt->close();
        }
    }
}

// ----------------------------
// BÚSQUEDA / FILTRO PARA LISTADO
// ----------------------------
$consulta = trim($_POST['consulta_busqueda'] ?? '');
$id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);
$id_categoria = $id_categoria === false ? 0 : ($id_categoria ?? 0);

$sql = "SELECT p.idPro, p.nomPro, p.desPro, p.stoAct, p.preUni, p.idEstProEnumFK, c.nomCat
        FROM productos p
        JOIN categoria_producto c ON p.idCatFK = c.idCat
        WHERE 1=1";

$params = [];
$types = '';

if ($consulta !== '') {
    $sql .= " AND (p.nomPro LIKE ? OR p.desPro LIKE ? OR p.idPro = ?)";
    $params[] = "%$consulta%";
    $params[] = "%$consulta%";
    // si consulta no es numérica, pasamos 0 para que no coincida por id
    $params[] = is_numeric($consulta) ? intval($consulta) : 0;
    $types .= "ssi";
}

if ($id_categoria > 0) {
    $sql .= " AND p.idCatFK = ?";
    $params[] = $id_categoria;
    $types .= "i";
}

$sql .= " ORDER BY p.nomPro LIMIT 200";

if ($stmt = $conexion->prepare($sql)) {
    if (!empty($params)) {
        // bind_param requiere referencias
        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);
    }
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res) {
        while ($fila = $res->fetch_assoc()) {
            $productos_encontrados[] = $fila;
        }
        $res->free();
    }
    $stmt->close();
} else {
    // Si no se pudo preparar, fallback con query (evitar inyección: sólo si no hay params)
    if (empty($params)) {
        $res = $conexion->query($sql);
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $productos_encontrados[] = $fila;
            }
            $res->free();
        }
    } else {
        // error de preparación
        $mensaje_resultado = "Error preparando la búsqueda.";
        $tipo_mensaje = "error";
    }
}

// Fin del archivo lógico — variables disponibles:
// $producto_seleccionado, $productos_encontrados, $categorias, $mensaje_resultado, $tipo_mensaje
