<?php

include("./conexion/conexion.php");

// REGISTRAR PRODUCTO
if (isset($_POST["btnRegistrar"])) {

    if (
        empty($_POST["nomPro"]) || 
        empty($_POST["desPro"]) || 
        empty($_POST["preUni"]) || 
        empty($_POST["stoAct"]) || 
        empty($_POST["idCatFK"])
    ) {
        echo "<script>alert('Todos los campos son obligatorios');</script>";

    } else {

        $nombreProducto = $_POST["nomPro"];
        $descripcionProducto = $_POST["desPro"];
        $precioUnitario = $_POST["preUni"];
        $stockActivo = $_POST["stoAct"];
        $categoriaProducto = $_POST["idCatFK"];

        $sql = $conexion->prepare("
            INSERT INTO productos (nomPro, desPro, preUni, stoAct, idCatFK)
            VALUES (?, ?, ?, ?, ?)
        ");

        $sql->bind_param("ssdii", $nombreProducto, $descripcionProducto, $precioUnitario, $stockActivo, $categoriaProducto);

        if ($sql->execute()) {
            echo "<script>alert('Producto creado exitosamente');</script>";
            header("Location: inicio.php");
            exit();
        } else {
            echo "<pre>";
            echo "ERROR de MySQL: " . $sql->error;
            echo "</pre>";
        }
    }
}

// BOTÓN CANCELAR
if (isset($_POST["btnCancelar"])) {
    header("Location: inicio.php");
    exit();
}
?>
