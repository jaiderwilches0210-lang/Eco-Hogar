<?php
// incluir la lógica (que a su vez incluye la conexión)
include_once __DIR__ . '/logica/logica-eliminar-prod.php';

// variables para la vista
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
    <title>Eliminar Producto</title>
    <link rel="stylesheet" href="css/eliminarProducto.css">
    <style>
      /* estilos mínimos para que se vea decente si no tienes css */
      table { border-collapse: collapse; width: 100%; }
      th, td { border: 1px solid #ddd; padding: 8px; text-align:left; }
      th { background: #f4f4f4; }
      .success { color: green; }
      .error { color: red; }
      .btn-select, .btn-submit { padding:6px 10px; }
    </style>
</head>
<body>

<?php include './components/sidebar.php'; ?>

<main class="content-area">

    <header class="topbar">
        <h1>Productos</h1>
        <form action="inicio.php" method="POST">
            <button type="submit" class="regresarbtn">Regresar</button>
        </form>
    </header>

    <div class="admin-box">
        <section class="titulo-area">
            <h1>Eliminar / Inactivar Producto</h1>
        </section>

        <div class="registro-box <?php echo ($producto_seleccionado === null) ? 'wide' : ''; ?>">

            <?php if (!empty($mensaje)): ?>
                <div class="<?php echo ($tipo === 'success') ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <!-- Si hay un producto seleccionado mostramos confirmación -->
            <?php if ($producto_seleccionado !== null): ?>
                <h3 class="info-title">Confirmar Inactivación del Producto</h3>

                <form action="eliminarProducto.php" method="POST" class="update-form">
                    <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto_seleccionado['idPro']); ?>">

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label>ID:</label>
                            <input type="text" value="<?php echo htmlspecialchars($producto_seleccionado['idPro']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" value="<?php echo htmlspecialchars($producto_seleccionado['nomPro']); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea disabled><?php echo htmlspecialchars($producto_seleccionado['desPro']); ?></textarea>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label>Precio Unitario:</label>
                            <input type="text" value="$<?php echo number_format($producto_seleccionado['preUni'], 2); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Categoría:</label>
                            <input type="text" value="<?php echo htmlspecialchars($producto_seleccionado['nomCat']); ?>" disabled>
                        </div>
                    </div>

                    <h3 class="info-title" style="color:#e63946;">
                        ¿Seguro que deseas inactivar este producto?
                    </h3>

                    <div class="btn-group">
                        <a href="eliminarProducto.php" class="btn-cancel">Cancelar</a>
                        <button type="submit" name="confirmar_eliminar" class="btn-submit" style="background:#ff4b5c;">
                            INACTIVAR PRODUCTO
                        </button>
                    </div>
                </form>

            <!-- Si no hay producto seleccionado, mostramos buscador y lista -->
            <?php else: ?>

                <p class="search-instruction">Filtre por ID, Nombre o Categoría:</p>

                <form action="eliminarProducto.php" method="POST" class="filter-form">
                    <div class="filter-controls">
                        <div class="form-group search-input">
                            <input type="text" name="consulta_busqueda" placeholder="ID o Nombre" value="<?php echo htmlspecialchars($prev_busqueda); ?>">
                        </div>

                        <div class="form-group category-select">
                            <select name="id_categoria">
                                <option value="0">Filtrar por Categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['idCat']; ?>" <?php echo ($prev_categoria == $cat['idCat']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nomCat']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" name="enviar_filtro" class="btn-search">Buscar</button>
                    </div>
                </form>

                <?php if (!empty($productos_encontrados)): ?>
                    <h3 class="results-count">Resultados (<?php echo count($productos_encontrados); ?>):</h3>

                    <div class="results-table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($productos_encontrados as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto['idPro']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nomPro']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nomCat']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['stoAct']); ?></td>

                                        <td>
                                            <?php
                                                echo ($producto['idEstProEnumFK'] == 1)
                                                    ? "<span style='color:green; font-weight:bold;'>ACTIVO</span>"
                                                    : "<span style='color:red; font-weight:bold;'>INACTIVO</span>";
                                            ?>
                                        </td>

                                        <td>
                                            <form action="eliminarProducto.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['idPro']); ?>">
                                                <button type="submit" name="seleccionar_producto" class="btn-select">Seleccionar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No se encontraron productos.</p>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>
