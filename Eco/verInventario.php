<?php

include("logica/logica-verInventario.php");
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
        @import url('css/style-verInventario.css');
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
        

<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <tr style="background-color: #4CAF50; color: white; text-align: left;">
        <th>ID</th>
        <th>Producto</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Categoría</th>
    </tr>

    <?php while ($fila = $sql->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $fila['idPro']; ?></td>
            <td><?php echo $fila['nomPro']; ?></td>
            <td><?php echo $fila['desPro']; ?></td>
            <td>$<?php echo number_format($fila['preUni'], 2); ?></td>
            <td><?php echo $fila['stoAct']; ?></td>
            <td><?php echo $fila['nomCat']; ?></td>
        </tr>
    <?php } ?>
</table>

        
        
    </div>
</body>
</html>