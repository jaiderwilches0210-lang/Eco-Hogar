<?php
include_once __DIR__ . "/../config/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomCat = trim($_POST['nomCat']);

    $sql = "INSERT INTO categoria_producto (nomCat) VALUES (?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nomCat);

    if ($stmt->execute()) {
        header("Location: listaCategorias.php");
        exit;
    } else {
        echo "Error al registrar.";
    }
}
?>
