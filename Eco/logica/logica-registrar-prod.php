<?php

include "conexion/conexion.php";
if (isset($_POST["btnRegistrar"])) {

    if (empty($_POST["nomPro"]) || empty($_POST["desPro"]) || empty($_POST["preUni"]) || empty($_POST["stoAct"]) || empty($_POST["catPro"])) {

        echo "<script>alert('Todos los campos son obligatorios');</script>";

    } else {

        $nombreProducto = $_POST["nomPro"];
        $descripcionProducto= $_POST["desPro"];
        $precioUnitario = $_POST["preUni"];
        $stockActivo = $_POST["stoAct"];
        $categoriaProducto = ($_POST["catPro"]);
        
        

        $sql = $conexion->query("INSERT INTO productos (nomPro, desPro, preUni, stoAct, catPro) VALUES ('$nombreProducto', '$descripcionProducto', '$precioUnitario', '$stockActivo', '$categoriaProducto')");

        echo "<script>alert('Producto creado exitosamente');</script>";
        header("Location: inicio.php");
        exit();
    }
}


// BOTÓN REGRESAR
if (isset($_POST["btnCancelar"])) {
    header("Location: inicio.php");
    exit();
}

