<?php
// Importar la lógica (insertar producto)
include("./logica/logica-registrar-prod.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producto</title> 
    <style>
        @import url('css/style-registrarProducto.css');
    </style>
</head>
<body>
<?php include './components/sidebar.php'; ?>

<main class="content-area">
<header class="topbar">
            <h1>Productos</h1>
            <form action="" method="POST" style="position: absolute; right: 30px; top: 15px; margin: 0;">
            <button type="submit" name="cerrar-sesion" class="cerrar-sesion">
                Cerrar Sesión
            </button>
        </form>
        </header> 

<div class="admin-box">
    <section class="titulo-area">
        <h1>Registrar Producto</h1>
    </section>

    <div class="registro-box">

        <form action="" method="POST">

            <input type="text" name="nomPro" placeholder="Nombre del producto">
            <input type="text" name="desPro" placeholder="Descripción del producto">
            <input type="number" step="0.01" name="preUni" placeholder="Precio unitario">
            <input type="number" name="stoAct" placeholder="Cantidad en stock">

            <select name="idCatFK">
                <option value="">Seleccione una categoría</option>
                <option value="1">Tecnología</option>
                <option value="2">Ropa</option>
                <option value="3">Hogar</option>
            </select>

            <div class="btn-group">
                <button type="submit" class="btnCancelar" name="btnCancelar">Cancelar</button>
                <button type="submit" class="btnRegistrar" name="btnRegistrar">Registrar</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
