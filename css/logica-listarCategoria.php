<?php
include_once __DIR__ . "/../config/conexion.php";

$sql = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
$result = $conexion->query($sql);

$categorias = [];

if ($result->num_rows > 0) {
    while ($fila = $result->fetch_assoc()) {
        $categorias[] = $fila;
    }
}
?>