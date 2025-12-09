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

<body>
    <?php include './components/sidebar.php'; ?>

 <main class="content-area">
 <header class="topbar">
        <h1>Inventario</h1>
        <a href="inicio.php" class ="regresarbtn">Regresar</a>
    </header>

    <div class="admin-box">

<table border="1" cellpadding="40" cellspacing="20" style="border-collapse: collapse; width: 100%;">
    <tr style="background-color: #66B2A0; color: white; text-align: left;">
        <th>Numero Registro</th>
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


<div class="pagination">

    <!-- Botón ANTERIOR -->
    <?php if ($pagina > 1): ?>
        <a href="?pag=<?php echo $pagina - 1; ?>">&#10094;</a>
    <?php else: ?>
        <span class="disabled">&#10094;</span>
    <?php endif; ?>


    <!-- Números -->
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

        <?php if ($i == $pagina): ?>
            <span class="active"><?php echo $i; ?></span>
        <?php else: ?>
            <a href="?pag=<?php echo $i; ?>"><?php echo $i; ?></a>
        <?php endif; ?>

    <?php endfor; ?>


    <!-- Botón SIGUIENTE -->
    <?php if ($pagina < $totalPaginas): ?>
        <a href="?pag=<?php echo $pagina + 1; ?>">&#10095;</a>
    <?php else: ?>
        <span class="disabled">&#10095;</span>
    <?php endif; ?>

</div>          
    </div>
</body>
</html>