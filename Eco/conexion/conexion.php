<?php
$conexion = new mysqli("localhost", "root", "", "eco_login_db", 3306);

if ($conexion->connect_errno) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
