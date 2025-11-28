<?php
include("conexion/conexion.php");
include("controlador/controlador_db.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <style>@import url('css/style-registro.css');</style>
</head>
<body>

<div class="main-box">

<div class="login-box">
    <h2>Registrarse</h2>

    <form action="" method="POST">
        <div class="rol-group">
            <input type="radio" name="idRolFK" value="admin">Admin
            <input type="radio" name="idRolFK" value="usuario">Usuario
        </div>
        <input type="text" name="nomUsu" placeholder="Nombre">
        <input type="email" name="email_Usu" placeholder="Correo Electrónico">
        <input type="password" name="clave" placeholder="Contraseña">
        <button type="submit" name="btnCrearCuenta">Crear Cuenta</button>
        <button type="submit" name="btnRegresar">Regresar</button>

        

    </form>

</div>

<div class="img-box">
    <img src="imagenes/logo.png" alt="">
</div>

</div>

</body>
</html>
