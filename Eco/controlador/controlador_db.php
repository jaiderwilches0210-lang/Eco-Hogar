<?php

// LOGIN
if (isset($_POST["btnIngresar"])) {

    if (empty($_POST["nomUsu"]) || empty($_POST["email_Usu"]) || empty($_POST["clave"]) || empty($_POST["idRolFK"])) {

        echo "<script>alert('Los campos son obligatorios');</script>";

    } else {

        $usuario = $_POST["nomUsu"];
        $email = $_POST["email_Usu"];
        $clave = $_POST["clave"];

        // Convertir texto a FK numérica
        $rol = ($_POST["idRolFK"] == "admin") ? 1 : 2;

        $sql = $conexion->query("SELECT * FROM usuarios WHERE nomUsu='$usuario' AND  email_Usu='$email' AND clave='$clave' AND idRolFK='$rol'");

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

    if (empty($_POST["nomUsu"]) || empty($_POST["email_Usu"]) || empty($_POST["clave"]) || empty($_POST["idRolFK"])) {

        echo "<script>alert('Todos los campos son obligatorios');</script>";

    } else {

        
        $usuario = $_POST["nomUsu"];
        $email = $_POST["email_Usu"];
        $clave = $_POST["clave"];

        $rol = ($_POST["idRolFK"] == "admin") ? 1 : 2;

        $sql = $conexion->query("INSERT INTO usuarios (nomUsu, email_Usu, clave, idRolFK) VALUES ('$usuario', '$email', '$clave', '$rol')");

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
