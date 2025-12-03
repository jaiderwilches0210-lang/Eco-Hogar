<?php
$conexion = new mysqli("127.0.0.1", "root", "", "eco_hogar", 3306);

if (mysqli_connect_errno()) {
    die("Error en conexión: " . mysqli_connect_error());
}
?>