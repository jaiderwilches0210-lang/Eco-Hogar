<?php

session_start();
include "conexion/conexion.php";

$producto_seleccionado = null;
$productos_encontrados = [];
$categorias = [];
$mensaje_resultado = '';
$tipo_mensaje = '';


// ------------------ CARGAR CATEGORÍAS --------------------
$sql_categorias = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
$res_categorias = $conexion->query($sql_categorias);

while ($cat = $res_categorias->fetch_assoc()) {
    $categorias[] = $cat;
}


// ------------------ CONFIRMAR ELIMINACIÓN --------------------
if (isset($_POST['confirmar_eliminar'])) {

    $idPro = intval($_POST['id_producto']);

    $sql = "DELETE FROM productos WHERE idPro = ? LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idPro);

    if ($stmt->execute()) {
        $mensaje_resultado = "Producto eliminado correctamente.";
        $tipo_mensaje = "success";
    } else {
        $mensaje_resultado = "Error al eliminar: " . $stmt->error;
        $tipo_mensaje = "error";
    }

    $stmt->close();
}


// ------------------ SELECCIONAR PRODUCTO --------------------
if (isset($_POST['seleccionar_producto'])) {
    $idPro = intval($_POST['id_producto']);

    $sql = "SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct, c.nomCat
            FROM productos p
            JOIN categoria_producto c ON p.idCatFK = c.idCat
            WHERE idPro = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idPro);

    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $producto_seleccionado = $res->fetch_assoc();
    }

    $stmt->close();
}


// ------------------ FILTRO DE BÚSQUEDA --------------------
$consulta = $_POST['consulta_busqueda'] ?? '';
$id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT) ?? 0;

$sql = "SELECT p.idPro, p.nomPro, p.desPro, p.stoAct, c.nomCat
        FROM productos p
        JOIN categoria_producto c ON p.idCatFK = c.idCat
        WHERE 1=1";

$params = [];
$types = '';

if (!empty($consulta)) {
    $sql .= " AND (p.nomPro LIKE ? OR p.desPro LIKE ? OR p.idPro = ?)";
    $params[] = "%$consulta%";
    $params[] = "%$consulta%";
    $params[] = $consulta;
    $types .= "ssi";
}

if ($id_categoria > 0) {
    $sql .= " AND p.idCatFK = ?";
    $params[] = $id_categoria;
    $types .= "i";
}

$sql .= " ORDER BY p.nomPro LIMIT 50";

$stmt = $conexion->prepare($sql);

if (!empty($params)) {
    $arr = [];
    foreach ($params as $i => $val) $arr[$i] = &$params[$i];
    array_unshift($arr, $types);
    call_user_func_array([$stmt, "bind_param"], $arr);
}

$stmt->execute();
$res = $stmt->get_result();

while ($fila = $res->fetch_assoc()) {
    $productos_encontrados[] = $fila;
}

$stmt->close();

?>
