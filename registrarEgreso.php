<?php

include_once 'logica/logica-egreso.php'; 

$mensaje = $mensaje_resultado ?? '';
$tipo = $tipo_mensaje ?? '';
$fecha_actual = date('Y-m-d'); 

// Variables para mantener los valores del filtro 
$prev_busqueda = $_POST['consulta_busqueda'] ?? '';
$prev_categoria = $_POST['id_categoria'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Egreso/Salida | Admin</title>
    
    <link rel="stylesheet" href="css/style-egreso.css"> 

</head>
<body>
    <?php include './components/sidebar.php'; ?>
    <main class="content-area">
    <header  class="topbar">
        <h1>Egresos</h1>
        <form action="inicio.php" method="POST" style="position: absolute; right: 30px; top: 30px; margin: 0;">
            <button type="submit" name="regresarbtn" class="regresarbtn">Regresar</button>
        </form>
    </header>


    <div class="admin-box">
        <h2>Egreso/Salida de Stock de Producto</h2>

        <div class="registro-box <?php echo ($producto_seleccionado === null) ? 'wide' : ''; ?>"> 

            <?php if (!empty($mensaje)): ?>
                <div class="resultado-server <?php echo htmlspecialchars($tipo); ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <?php 
            
            if ($producto_seleccionado !== null): 
            ?>
                
                <div class="contenedor-formulario-stock"> 
                    <h3 class="info-title">Detalles del Producto y Stock Actual: 
                        <span class="valor-stock-actual" style="color: #ffffffff;"><?php echo htmlspecialchars($producto_seleccionado['stoAct'] ?? '0'); ?> unidades</span>
                    </h3>
                    
                    <form action="registrarEgreso.php" method="POST" class="formulario-ingreso-stock">
                        
                        <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto_seleccionado['idPro'] ?? ''); ?>">
                        
                        <div class="form-group-inline">
                            <div class="form-group" style="flex: 1;">
                                <label for="idPro">ID del Producto:</label>
                                <input type="text" id="idPro" value="<?php echo htmlspecialchars($producto_seleccionado['idPro'] ?? ''); ?>" disabled>
                            </div>
                            <div class="form-group" style="flex: 2;">
                                <label for="nombre">Nombre del Producto:</label>
                                <input type="text" id="nombre" value="<?php echo htmlspecialchars($producto_seleccionado['nomPro'] ?? ''); ?>" disabled>
                            </div>
                        </div>

                        <div>
                            <label for="descripcion">Descripción:</label>
                            <textarea id="descripcion" disabled><?php echo htmlspecialchars($producto_seleccionado['desPro'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group-inline">
                            <div class="form-group" style="flex: 1;">
                                <label for="precio">Precio Unitario:</label>
                                <input type="text" id="precio" value="$<?php echo number_format($producto_seleccionado['preUni'] ?? 0, 2); ?>" disabled>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label for="categoria">Categoría:</label>
                                <input type="text" id="categoria" value="<?php echo htmlspecialchars($producto_seleccionado['nomCat'] ?? ''); ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cantidad_egresar">Cantidad del producto Vendido (Egreso/Salida):</label>
                            <input type="number" id="cantidad_egresar" name="cantidad_egresar" min="1" required 
                                placeholder="Ingrese la cantidad de unidades a egresar"
                                max="<?php echo htmlspecialchars($producto_seleccionado['stoAct'] ?? '1'); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="razon_egreso">Razón del Egreso/Venta:</label>
                            <textarea id="razon_egreso" name="razon_egreso" rows="3" required placeholder="Ej: Venta al cliente #XYZ, Producto dañado, Devolución a proveedor."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha de Registro:</label>
                            <input type="text" id="fecha" name="fecha" value="<?php echo $fecha_actual; ?>" disabled>
                        </div>

                        <div class="btn-group">
                            <a href="registrarEgreso.php" class="btn-cancelar-form btn-cancel" style="text-decoration: none; text-align: center;">Cancelar</a>
                            <button type="submit" name="enviar_egreso" class="btn-confirmar-form btn-submit">Confirmar Egreso</button>
                        </div>

                    </form>
                </div>

            <?php 
            
            // FORMULARIO DE BÚSQUEDA Y RESULTADOS (Mantiene clases genéricas de búsqueda/tabla)

            else: 
            ?>
            
                <p class="search-instruction">Filtre por nombre, ID o categoría para seleccionar el producto a egresar stock:</p> <br>

                <form action="registrarEgreso.php" method="POST" class="filter-form">
                    <div class="filter-controls">
                        <div class="form-group search-input">
                            <input type="text" name="consulta_busqueda" placeholder="ID o Nombre del Producto"
                                        value="<?php echo htmlspecialchars($prev_busqueda); ?>">
                        </div>
                        
                        <div class="form-group category-select">
                            <select name="id_categoria">
                                <option value="0">Filtrar por Categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['idCat']); ?>" 
                                            <?php echo ($prev_categoria == $cat['idCat']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nomCat']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="enviar_filtro" class="btn-search">Buscar Productos</button>
                    </div>
                </form>
                
                <?php if (!empty($productos_encontrados)): ?>
                    <h3 class="results-count">Resultados de la Búsqueda (<?php echo count($productos_encontrados); ?>):</h3>
                    
                    <div class="results-table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Stock Actual</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos_encontrados as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto['idPro']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nomPro']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nomCat']); ?></td>
                                        <td class="stock-cell"><?php echo htmlspecialchars($producto['stoAct']); ?></td>
                                        <td>
                                            <form action="registrarEgreso.php" method="POST" style="margin: 0;">
                                                <input type="hidden" name="id_producto" value="<?php echo htmlspecialchars($producto['idPro']); ?>">
                                                <button type="submit" name="seleccionar_producto" class="btn-select">Seleccionar</button>
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