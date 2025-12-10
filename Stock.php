<?php

include("logica/logica-stock.php");

if (!isset($umbralBajo) || !is_numeric($umbralBajo)) {
    $umbralBajo = 10; 
}

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
    <title>Panel de Administración - Inventario</title> 
    <style>
        @import url('css/style-stock.css');
    </style>
    
</head>

<body>
    <?php include './components/sidebar.php'; ?>

    <main class="content-area">
    <header class="topbar">
        <h1>Inventario </h1>
        <a href="inicio.php" class ="regresarbtn">Regresar</a>
    </header>

    <div class="admin-box">

    <form method="GET" class="historial-filters">
    
    <div class="filter-group" style="width: auto;">
        <label for="filtro_stock">Filtrar por Stock</label>
        <select name="filtro" id="filtro_stock">
            <option value="">Mostrar Todos</option>
            <option value="bajo" <?php echo (isset($_GET['filtro']) && $_GET['filtro'] == 'bajo') ? 'selected' : ''; ?>>
                Stock Bajo
            </option>
        </select>
    </div>

    <button type="submit" class="btn-filter" style="align-self: flex-end;">Filtrar</button>
    </form>

<table border="1" cellpadding="40" cellspacing="20" style="border-collapse: collapse; width: 100%;">
    <tr style="background-color: #66B2A0; color: white; text-align: left;">
        <th>ID</th>
        <th>Producto</th>
        <th>Stock</th>
        <th>Categoría</th>
    </tr>

    <?php 
    while ($fila = $sql->fetch_assoc()) {
        $stock_actual = (int)$fila['stoAct'];
        $claseStockBajo = ($fila['stoAct'] <= $umbralBajo) ? 'stock-bajo' : '';
    ?>

        <tr class="<?php echo $claseStockBajo; ?>">
            <td><?php echo $fila['idPro']; ?></td>
            <td><?php echo $fila['nomPro']; ?></td>
            <td class="<?php echo ($fila['stoAct'] <= $umbralBajo) ? 'stock-bajo-celda' : ''; ?>">
                <?php echo $fila['stoAct']; ?>
                <?php echo ($fila['stoAct'] <= $umbralBajo) ? ' (Bajo)' : ''; ?>
            </td>
            
            <td><?php echo $fila['nomCat']; ?></td>
        </tr>
    <?php } ?>
</table>


<div class="pagination">

    <?php if ($pagina > 1): ?>
        <a href="?pag=<?php echo $pagina - 1; ?>">&#10094;</a>
    <?php else: ?>
        <span class="disabled">&#10094;</span>
    <?php endif; ?>


    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

    <?php if ($i == $pagina): ?>
        <span class="active"><?php echo $i; ?></span>
    <?php else: ?>
        <a href="?pag=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php endif; ?>

    <?php endfor; ?>


    <?php if ($pagina < $totalPaginas): ?>
        <a href="?pag=<?php echo $pagina + 1; ?>">&#10095;</a>
    <?php else: ?>
        <span class="disabled">&#10095;</span>
    <?php endif; ?>

    </div>
</div>
</body>
</html>