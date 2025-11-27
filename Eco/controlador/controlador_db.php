<?php

// LOGIN
if (isset($_POST["btnIngresar"])) {

    if (empty($_POST["nomUsu"]) || empty($_POST["apelUsu"]) || empty($_POST["email"]) || empty($_POST["clave"]) || empty($_POST["rol"])) {

        echo "<script>alert('Los campos son obligatorios');</script>";

    } else {

        $nombre = $_POST["nomUsu"];
        $apellido = $_POST["apelUsu"];
        $email = $_POST["email"];
        $clave = $_POST["clave"];

        // Convertir texto a FK numérica
        $rol = ($_POST["rol"] == "admin") ? 1 : 2;

        $sql = $conexion->query("SELECT * FROM usuarios WHERE nomUsu='$nombre' AND apelUsu='$apellido' AND email='$email' AND clave='$clave' AND rol='$rol'");

        if ($datos = $sql->fetch_object()) {
            header("Location: inicio.php");
            exit();
        } else {
            echo "<script>alert('Usuario, correo o contraseña incorrecta');</script>";
        }
    }
}


// REGISTRO DE USUARIO
if (isset($_POST["btnCrearCuenta"])) {

    if (empty($_POST["nomUsu"]) || empty($_POST["apelUsu"]) || empty($_POST["email"]) || empty($_POST["clave"]) || empty($_POST["rol"])) {

        echo "<script>alert('Todos los campos son obligatorios');</script>";

    } else {

        $nombre = $_POST["nomUsu"];
        $apellido = $_POST["apelUsu"];
        $email = $_POST["email"];
        $clave = $_POST["clave"];
        $rol = ($_POST["rol"] == "admin") ? 1 : 2;

        $sql = $conexion->query("INSERT INTO usuarios (nomUsu, apelUsu, email, clave, rol) VALUES ('$nombre', '$apellido', '$email', '$clave', '$rol')");

        echo "<script>alert('Cuenta creada exitosamente');</script>";
        header("Location: login.php");
        exit();
    }
}


// BOTÓN REGRESAR
if (isset($_POST["btnRegresar"])) {
    header("Location: login.php");
    exit();
}
