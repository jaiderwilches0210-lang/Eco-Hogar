<?php
session_start();

// Incluir la conexión correctamente (archivo: Eco-Hogar/conexion/conexion.php)
include_once __DIR__ . '/../conexion/conexion.php';

// Variables expuestas al archivo que incluye esta lógica
$producto_seleccionado = null;
$categorias = [];
$mensaje_resultado = '';
$tipo_mensaje = '';

// ----------------------------
// Cargar categorías (igual a como lo hiciste en eliminar)
// ----------------------------
$sql_categorias = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
if ($res_categorias = $conexion->query($sql_categorias)) {
    while ($cat = $res_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
    $res_categorias->free();
}

// ----------------------------
// SI VIENE id_producto vía POST SIN guardar_actualizacion -> cargar producto para editar
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto']) && !isset($_POST['guardar_actualizacion'])) {
    $idPro = intval($_POST['id_producto'] ?? 0);

    if ($idPro > 0) {
        $sql = "SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct, p.idEstProEnumFK, p.idCatFK, c.nomCat
                FROM productos p
                LEFT JOIN categoria_producto c ON p.idCatFK = c.idCat
                WHERE p.idPro = ? LIMIT 1";
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("i", $idPro);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    $producto_seleccionado = $res->fetch_assoc();
                }
                if ($res) $res->free();
            }
            $stmt->close();
        }
    } else {
        $mensaje_resultado = "ID de producto inválido.";
        $tipo_mensaje = "error";
    }
}

// ----------------------------
// GUARDAR ACTUALIZACION (guardar_actualizacion)
// ----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_actualizacion'])) {
    $idPro = intval($_POST['id_producto'] ?? 0);

    // Leer y sanitizar valores (mínimo)
    $nom = trim($_POST['nomPro'] ?? '');
    $des = trim($_POST['desPro'] ?? '');
    $pre = isset($_POST['preUni']) ? floatval($_POST['preUni']) : 0.0;
    $stock = isset($_POST['stoAct']) ? intval($_POST['stoAct']) : 0;
    $idCat = isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : 0;

    if ($idPro > 0 && $nom !== '') {
        $sql = "UPDATE productos SET nomPro = ?, desPro = ?, preUni = ?, stoAct = ?, idCatFK = ? WHERE idPro = ? LIMIT 1";
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("ssdiii", $nom, $des, $pre, $stock, $idCat, $idPro);
            if ($stmt->execute()) {
                $mensaje_resultado = "Producto actualizado correctamente.";
                $tipo_mensaje = "success";
            } else {
                $mensaje_resultado = "Error al actualizar producto: " . $stmt->error;
                $tipo_mensaje = "error";
            }
            $stmt->close();
        } else {
            $mensaje_resultado = "Error en la preparación de la consulta.";
            $tipo_mensaje = "error";
        }

        // volver a traer el producto actualizado para mostrarlo en la vista
        $sql2 = "SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct, p.idEstProEnumFK, p.idCatFK, c.nomCat
                 FROM productos p
                 LEFT JOIN categoria_producto c ON p.idCatFK = c.idCat
                 WHERE p.idPro = ? LIMIT 1";
        if ($stmt2 = $conexion->prepare($sql2)) {
            $stmt2->bind_param("i", $idPro);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            if ($res2 && $res2->num_rows > 0) {
                $producto_seleccionado = $res2->fetch_assoc();
            }
            if ($res2) $res2->free();
            $stmt2->close();
        }
    } else {
        $mensaje_resultado = "Datos incompletos para actualización.";
        $tipo_mensaje = "error";
    }
}
