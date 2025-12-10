<?php include_once __DIR__ . "/logica/logica-registrar-cat.php"; ?>

<h2>Registrar Categoría</h2>

<form method="POST">
    <label>Nombre de la categoría:</label>
    <input type="text" name="nomCat" required>
    
    <button type="submit">Guardar</button>
</form>

<a href="listaCategorias.php">Volver</a>
