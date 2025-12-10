<?php include_once __DIR__ . "/logica/logica-listar-cat.php"; ?>

<h2>Listado de Categorías</h2>

<a href="registrarCategoria.php" class="btn btn-primary">Registrar Categoría</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($categorias as $cat): ?>
    <tr>
        <td><?= $cat['idCat'] ?></td>
        <td><?= $cat['nomCat'] ?></td>
        <td>
            <a href="actualizarCategoria.php?id=<?= $cat['idCat'] ?>" class="btn btn-warning">Actualizar</a>

            <a href="eliminarCategoria.php?id=<?= $cat['idCat'] ?>" class="btn btn-danger">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
