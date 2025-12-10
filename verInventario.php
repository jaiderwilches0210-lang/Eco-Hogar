<?php
$data = include("logica/logica-verInventario.php");

$resultado = $data['resultado'];
$categorias = $data['categorias'];
$mensaje = $data['mensaje'];
$tipo = $data['tipo'];
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
    <div class="resultado-server <?php echo $tipo; ?>">
        <?= $mensaje ?>
    </div>
<?php endif; ?>

<!-- FILTROS -->
<form method="GET" class="filtros">

    <input type="text" name="buscar" placeholder="Buscar por ID o Nombre">

    <select name="categoria">
        <option value="0">Categoría</option>
        <?php while ($cat = $categorias->fetch_assoc()): ?>
            <option value="<?= $cat['idCat'] ?>"><?= $cat['nomCat'] ?></option>
        <?php endwhile; ?>
    </select>

    <select name="estado">
        <option value="0">Estado</option>
        <option value="1">Activo</option>
        <option value="2">Inactivo</option>
    </select>

    <button type="submit">Filtrar</button>

</form>

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

            <td>
                <?= $fila['estado_nombre'] ?>
            </td>

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

</div>
</main>

</body>
</html>
