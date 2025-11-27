
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
            <input type="radio" name="rol" value="admin">Admin
            <input type="radio" name="rol" value="usuario">Usuario
        </div>
        
        <input type="text" name="nomUsu" placeholder="nombre">
        <input type="text" name="apelUsu" placeholder="Apellido">
        <input type="email" name="email" placeholder="Correo Electrónico">
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








