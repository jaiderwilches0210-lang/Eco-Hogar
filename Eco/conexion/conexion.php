<?php
$conexion = new mysqli("localhost", "root", "", "eco_hogar", 3307);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
