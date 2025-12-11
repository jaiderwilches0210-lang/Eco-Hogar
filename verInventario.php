<?php
$data = include("logica/logica-verInventario.php");

$resultado = $data['resultado'];
$categorias = $data['categorias'];
$mensaje = $data['mensaje'];
$tipo = $data['tipo'];

$pagina = $data['paginaActual'];
$totalPaginas = $data['totalPaginas'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="css/style-verInventario.css">
</head>

<body>

<?php include './components/sidebar.php'; ?>

<main class="content-area">

<header class="topbar">
    <h1>Inventario</h1>
    <a href="inicio.php" class="regresarbtn">Regresar</a>
</header>

<div class="admin-box">

<?php if (!empty($mensaje)): ?>
    <div class="resultado-server <?= $tipo ?>">
        <?= $mensaje ?>
    </div>
<?php endif; ?>


<!-- ====================== FILTROS ====================== -->
<form method="GET" class="historial-filters">

    <div class="filter-group">
        <label>Búsqueda</label>
        <input type="text" name="buscar" placeholder="Buscar por ID o Nombre" 
               value="<?= $_GET['buscar'] ?? '' ?>">
    </div>

    <div class="filter-group">
        <label>Categoría</label>
        <select name="categoria">
            <option value="0">Categoría</option>

            <?php while ($cat = $categorias->fetch_assoc()): ?>
                <option value="<?= $cat['idCat'] ?>"
                    <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['idCat']) ? 'selected' : '' ?>>
                    <?= $cat['nomCat'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="filter-group">
        <label>Estado</label>
        <select name="estado">
            <option value="0">Estado</option>
            <option value="1" <?= (($_GET['estado'] ?? '') == "1") ? 'selected' : '' ?>>Activo</option>
            <option value="2" <?= (($_GET['estado'] ?? '') == "2") ? 'selected' : '' ?>>Inactivo</option>
        </select>
    </div>

    <button type="submit" class="btn-filter">Filtrar</button>

</form>



<!-- ====================== TABLA ====================== -->
<table>
    <tr>
        <th>ID</th>
        <th>Producto</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Categoría</th>
        <th>Estado</th>
        <th>Acción</th>
    </tr>

    <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?= $fila['idPro'] ?></td>
            <td><?= $fila['nomPro'] ?></td>
            <td><?= $fila['desPro'] ?></td>
            <td>$<?= number_format($fila['preUni'], 2) ?></td>
            <td><?= $fila['stoAct'] ?></td>
            <td><?= $fila['nomCat'] ?></td>

            <td><?= $fila['estado_nombre'] ?></td>

            <td>
                <form method="POST" action="verInventario.php">
                    <input type="hidden" name="id_producto" value="<?= $fila['idPro'] ?>">

                    <?php if ($fila['estado_id'] == 1): ?>
                        <input type="hidden" name="nuevo_estado" value="2">
                        <button type="submit" name="cambiar_estado" class="btn-inactivar">
                            Inactivar
                        </button>
                    <?php else: ?>
                        <input type="hidden" name="nuevo_estado" value="1">
                        <button type="submit" name="cambiar_estado" class="btn-activar">
                            Activar
                        </button>
                    <?php endif; ?>

                </form>
            </td>
        </tr>
    <?php endwhile; ?>

</table>



<!-- ====================== PAGINACIÓN ESTILO STOCK ====================== -->
<div class="pagination">

    <!-- Flecha izquierda -->
    <?php if ($pagina > 1): ?>
        <a href="?pagina=<?= $pagina - 1 ?>&buscar=<?= $_GET['buscar'] ?? '' ?>&categoria=<?= $_GET['categoria'] ?? 0 ?>&estado=<?= $_GET['estado'] ?? 0 ?>">
            &#10094;
        </a>
    <?php else: ?>
        <span class="disabled">&#10094;</span>
    <?php endif; ?>


    <!-- Números de página -->
    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

        <?php if ($i == $pagina): ?>
            <span class="active"><?= $i ?></span>
        <?php else: ?>
            <a href="?pagina=<?= $i ?>&buscar=<?= $_GET['buscar'] ?? '' ?>&categoria=<?= $_GET['categoria'] ?? 0 ?>&estado=<?= $_GET['estado'] ?? 0 ?>">
                <?= $i ?>
            </a>
        <?php endif; ?>

    <?php endfor; ?>


    <!-- Flecha derecha -->
    <?php if ($pagina < $totalPaginas): ?>
        <a href="?pagina=<?= $pagina + 1 ?>&buscar=<?= $_GET['buscar'] ?? '' ?>&categoria=<?= $_GET['categoria'] ?? 0 ?>&estado=<?= $_GET['estado'] ?? 0 ?>">
            &#10095;
        </a>
    <?php else: ?>
        <span class="disabled">&#10095;</span>
    <?php endif; ?>

</div>

</div>
</main>
</body>
</html>
