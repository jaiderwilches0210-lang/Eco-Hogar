<?php
// incluir la lógica (que a su vez incluye la conexión)
include_once __DIR__ . '/logica/logica-actualizarProducto.php';

// variables para la vista (misma convención que usas en eliminar)
$mensaje = $mensaje_resultado ?? '';
$tipo = $tipo_mensaje ?? '';
$prev_busqueda = $_POST['consulta_busqueda'] ?? '';
$prev_categoria = $_POST['id_categoria'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
    <link rel="stylesheet" href="css/eliminarProducto.css">
    <style>
      /* estilos mínimos si no tienes css */
      table { border-collapse: collapse; width: 100%; }
      th, td { border: 1px solid #ddd; padding: 8px; text-align:left; }
      th { background: #f4f4f4; }
      .success { color: green; }
      .error { color: red; }
      .btn-select, .btn-submit, .btn-update { padding:6px 10px; }
      .btn-update { background:#4CAF50; color:#fff; border:none; }
      .btn-update:hover { opacity:0.95; }
      .form-group { margin-bottom:10px; }
      .form-group-inline { display:flex; gap:12px; }
      input[type="text"], input[type="number"], textarea, select { width:100%; padding:6px; }
    </style>
</head>
<body>

<?php include './components/sidebar.php'; ?>

<main class="content-area">
    <header class="topbar">
        <h1>Actualizar Producto</h1>
        <form action="eliminarProducto.php" method="POST">
            <button type="submit" class="regresarbtn">Regresar</button>
        </form>
    </header>

    <div class="admin-box">
        <section class="titulo-area">
            <h1>Actualizar Producto</h1>
        </section>

        <div class="registro-box <?php echo ($producto_seleccionado === null) ? 'wide' : ''; ?>">

            <?php if (!empty($mensaje)): ?>
                <div class="<?php echo ($tipo === 'success') ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <!-- Si hay un producto seleccionado mostramos formulario de edición -->
            <?php if ($producto_seleccionado !== null): ?>
                <h3 class="info-title">Editar Producto (ID: <?php echo htmlspecialchars($producto_seleccionado['idPro']); ?>)</h3>

                <form action="actualizarProducto.php" method="POST" class="update-form">
                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto_seleccionado['idPro']); ?>">

                    <div class="form-group-inline">
                        <div class="form-group" style="flex:1">
                            <label>ID:</label>
                            <input type="text" value="<?php echo htmlspecialchars($producto_seleccionado['idPro']); ?>" disabled>
                        </div>

                        <div class="form-group" style="flex:3">
                            <label>Nombre:</label>
                            <input type="text" name="nomPro" value="<?php echo htmlspecialchars($producto_seleccionado['nomPro']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea name="desPro" rows="4"><?php echo htmlspecialchars($producto_seleccionado['desPro']); ?></textarea>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group" style="flex:1">
                            <label>Precio Unitario:</label>
                            <input type="number" name="preUni" value="<?php echo htmlspecialchars($producto_seleccionado['preUni']); ?>" step="0.01" required>
                        </div>

                        <div class="form-group" style="flex:1">
                            <label>Stock:</label>
                            <input type="number" name="stoAct" value="<?php echo htmlspecialchars($producto_seleccionado['stoAct']); ?>" required>
                        </div>

                        <div class="form-group" style="flex:1">
                            <label>Categoría:</label>
                            <select name="id_categoria" required>
                                <option value="0">--Seleccione--</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['idCat']; ?>" <?php echo ($producto_seleccionado['idCatFK'] == $cat['idCat']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nomCat']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div style="margin-top:12px;">
                        <a href="eliminarProducto.php" class="btn-cancel">Cancelar</a>
                        <button type="submit" name="guardar_actualizacion" class="btn-submit btn-update">
                            GUARDAR CAMBIOS
                        </button>
                    </div>
                </form>

            <!-- Si no hay producto seleccionado, mostramos instrucción -->
            <?php else: ?>

                <p>Para editar un producto, vuelve a la lista y haz clic en <strong>Actualizar</strong> en la fila correspondiente.</p>
                <p><a href="eliminarProducto.php">Volver a Lista de Productos</a></p>

            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>
