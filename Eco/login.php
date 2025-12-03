<?php
session_start();
include("conexion/conexion.php");
include("controlador/controlador_db.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

<div class="main-box">

<div class="login-box">
    <h2>Iniciar Sesión</h2>

    <form action="" method="POST">
        <div class="rol-group">
            <input type="radio" name="idRolFK" value="admin">Admin
            <input type="radio" name="idRolFK" value="usuario">Usuario
        </div>
        
        <input type="text" name="nomUsu" placeholder="Usuario">
        <input type="email" name="email_Usu" placeholder="Correo Electrónico">
        <input type="password" name="clave" placeholder="Contraseña">
        <button type="submit" name="btnIngresar">Ingresar</button>

        <a href="registro.php">¿Ya estás registrado?</a>
        
    </form>

</div>
<div class="img-box">
    <img src="imagenes/login.png" alt="" >
    
</div>
    
</div>

</body>
</html>

<?php

if (isset($_POST["btnIngresar"])) {

    if (empty($_POST["nomUsu"]) || empty($_POST["email_Usu"]) || empty($_POST["clave"]) || empty($_POST["idRolFK"])) {

        echo "<script>alert('Los campos son obligatorios');</script>";

    } else {

        $usuario = $_POST["nomUsu"];
        $email = $_POST["email_Usu"];
        $clave = $_POST["clave"];

        $rol = ($_POST["idRolFK"] == "admin") ? 1 : 2;

        $sql = "SELECT idUsu, nomUsu, idRolFK FROM usuarios WHERE nomUsu=? AND email_Usu=? AND clave=? AND idRolFK=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssi", $usuario, $email, $clave, $rol);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($datos = $resultado->fetch_object()) {
            
            $_SESSION['idUsu'] = $datos->idUsu;
            $_SESSION['nomUsu'] = $datos->nomUsu;
            $_SESSION['idRolFK'] = $datos->idRolFK;

            header("Location: inicio.php");
            exit();
        } else {
            echo "<script>alert('Usuario, correo o contraseña incorrecta');</script>";
        }
        $stmt->close();
    }
}

if (isset($_POST["btnCrearCuenta"])) {

    if (empty($_POST["nomUsu"]) || empty($_POST["email_Usu"]) || empty($_POST["clave"]) || empty($_POST["idRolFK"])) {

        echo "<script>alert('Todos los campos son obligatorios');</script>";

    } else {

        
        $usuario = $_POST["nomUsu"];
        $email = $_POST["email_Usu"];
        $clave = $_POST["clave"];

        $rol = ($_POST["idRolFK"] == "admin") ? 1 : 2;

        $sql_insert = "INSERT INTO usuarios (nomUsu, email_Usu, clave, idRolFK) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->bind_param("sssi", $usuario, $email, $clave, $rol);

        if ($stmt_insert->execute()) {
             echo "<script>alert('Cuenta creada exitosamente');</script>";
        } else {
             echo "<script>alert('Error al crear la cuenta. Intente con otro correo.');</script>";
        }
        $stmt_insert->close();
        
        header("Location: login.php");
        exit();
    }
}

if (isset($_POST["btnRegresar"])) {
    header("Location: login.php");
    exit();
}
?>