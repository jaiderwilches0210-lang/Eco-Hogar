<?php

include("./logica/logica-registrar-prod.php");
if (isset($_POST["regresarbtn"])) {
    header("Location: inicio.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title> <style>
        @import url('css/style-registrarProducto.css');
    </style>
    </head>

      <header class="main-header">
        <img src="imagenes/logo.png" alt="Logo de la Aplicación" style="border-radius: 50%;">
        
        <form action="" method="POST" style="position: absolute; right: 30px; top: 30px; margin: 0;">
            <button type="submit" name="regresar" class="regresarbtn">
                Regresar
            </button>
        </form>
    </header>
<body>
    

    <div class="admin-box">
        <section class="titulo-area">
                <h1>Panel de Administración</h1>
            </section>


        <div class="registro-box">
            <form action="" method="POST">
                
                <input type="text" name="nomPro" placeholder="Nombre del producto">
                <input type="text" name="desPro" placeholder="Descripción del producto">
                <input type="number" step="0.01" name="preUni" placeholder="Precio unitario">
                <input type="number" name="stoAct" placeholder="Cantidad en stock">
                <select name="catPro" >
                    <option value="">Seleccione una categoría</option>
                    <option value="1">Tecnología</option>
                    <option value="2">Ropa</option>
                    <option value="3">Hogar</option>
                </select>

                <div class="btn-group">
                    <button type="submit" name="btnCancelar">Cancelar</button>
                    <button type="submit" name="btnRegistrar">Registrar</button>
                </div>

            </form>
        </div>

        
        
    </div>
</body>
</html>