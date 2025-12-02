<?php

include_once 'logica/logica-eliminar-prod.php'; 

$mensaje = $mensaje_resultado ?? '';
$tipo = $tipo_mensaje ?? '';
$fecha_actual = date('Y-m-d');
$prev_busqueda = $_POST['consulta_busqueda'] ?? '';
$prev_categoria = $_POST['id_categoria'] ?? '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Producto | Admin</title>


    <link rel="stylesheet" href="css/style-eliminar.css">
</head>
<body>

<header class="main-header">
    <img src="imagenes/logo.png" style="border-radius: 50%;">
    
    <nav class="main-nav-menu">
        <ul>
            <li><a href="verInventario.php">Inventario</a></li>
            <li><a href="historialMovimientos.php">Movimientos</a></li>
            <li><a href="registrarProducto.php">Registrar Producto</a></li>
            <li><a href="registrarIngreso.php">Ingreso</a></li>
            <li><a href="registrarEgreso.php">Egreso</a></li>
        </ul>
    </nav>

    <form action="inicio.php" method="POST" style="position:absolute; right:30px; top:30px;">
        <button type="submit" class="regresarbtn">Regresar</button>
    </form>
</header>

<div class="admin-box">
    <h2 style="color:white;">Eliminar Producto del Inventario</h2>

    <div class="registro-box <?php echo ($producto_seleccionado === null) ? 'wide' : ''; ?>">

        <?php if (!empty($mensaje)): ?>
            <div class="resultado-server <?php echo htmlspecialchars($tipo); ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>


        <!-- SI SELECCIONÓ UN PRODUCTO -->
        <?php if ($producto_seleccionado !== null): ?>

            <div class="contenedor-formulario-stock">
                <h3 class="info-title">Confirmar Eliminación del Producto</h3>

                <form action="eliminarProducto.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?php echo $producto_seleccionado['idPro']; ?>">

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label>ID:</label>
                            <input type="text" value="<?php echo $producto_seleccionado['idPro']; ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" value="<?php echo $producto_seleccionado['nomPro']; ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Descripción:</label>
                        <textarea disabled><?php echo $producto_seleccionado['desPro']; ?></textarea>
                    </div>

                    <div class="form-group-inline">
                        <div class="form-group">
                            <label>Precio Unitario:</label>
                            <input type="text" value="$<?php echo number_format($producto_seleccionado['preUni'], 2); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Categoría:</label>
                            <input type="text" value="<?php echo $producto_seleccionado['nomCat']; ?>" disabled>
                        </div>
                    </div>

                    <h3 style="color:yellow; margin-top:20px;">
                        ¿Seguro que quieres ELIMINAR este producto?
                    </h3>

                    <div class="btn-group">
                        <a href="eliminarProducto.php" class="btn-cancelar-form">Cancelar</a>
                        <button type="submit" name="confirmar_eliminar" class="btn-submit" style="background:red;">
                            ELIMINAR DEFINITIVO
                        </button>
                    </div>
                </form>
            </div>


        <!-- SI NO SE HA SELECCIONADO PRODUCTO → MOSTRAR BUSCADOR -->
        <?php else: ?>

            <p class="search-instruction">Filtre por ID, Nombre o Categoría:</p>

            <form action="eliminarProducto.php" method="POST" class="filter-form">

                <div class="filter-controls">

                    <div class="form-group search-input">
                        <input type="text" name="consulta_busqueda" placeholder="ID o Nombre"
                            value="<?php echo htmlspecialchars($prev_busqueda); ?>">
                    </div>

                    <div class="form-group category-select">
                        <select name="id_categoria">
                            <option value="0">Filtrar por Categoría</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['idCat']; ?>" 
                                        <?php echo ($prev_categoria == $cat['idCat']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['nomCat']; ?>
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
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($productos_encontrados as $producto): ?>
                                <tr>
                                    <td><?php echo $producto['idPro']; ?></td>
                                    <td><?php echo $producto['nomPro']; ?></td>
                                    <td><?php echo $producto['nomCat']; ?></td>
                                    <td><?php echo $producto['stoAct']; ?></td>

                                    <td>
                                        <form action="eliminarProducto.php" method="POST">
                                            <input type="hidden" name="id_producto" value="<?php echo $producto['idPro']; ?>">
                                            <button type="submit" name="seleccionar_producto" class="btn-select">
                                                Seleccionar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
