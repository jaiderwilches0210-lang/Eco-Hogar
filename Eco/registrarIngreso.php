<?php

include_once 'logica/logica-ingreso.php'; 

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
    <title>Registro de Ingreso | Admin</title>
    <style>
        @import url('css/style-admin.css');
    </style>
    <link rel="stylesheet" href="css/style-ingreso.css"> 
</head>
<body>
    
    <header class="main-header">
        <img src="imagenes/logo.png" alt="Logo de la Aplicación" style="border-radius: 50%;">

        <nav class="main-nav-menu">
        <ul>
            <li><a href="verInventario.php">Inventario</a></li>
            <li><a href="historialMovimientos.php">Movimientos</a></li>
            <li><a href="registrarProducto.php">Registrar Producto</a></li>
            <li><a href="registrarEgreso.php">Egreso/Venta</a></li>
            <li><a href="generarReportes.php">Reportes</a></li>
        </ul>
    </nav>
        <form action="inicio.php" method="POST" style="position: absolute; right: 30px; top: 30px; margin: 0;">
            <button type="submit" name="regresarbtn" class="regresarbtn">Regresar</button>
        </form>
    </header>


    <div class="admin-box">
          <h2 style="color: white; " >Registro Ingreso Stock </h2>

        <div class="registro-box <?php echo ($producto_seleccionado === null) ? 'wide' : ''; ?>"> 

            <?php if (!empty($mensaje)): ?>
                <div class="resultado-server <?php echo $tipo; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            
            <?php 
            
            if ($producto_seleccionado !== null): 
            ?>
                <form action="registrarIngreso.php" method="POST" class="update-form">
                    <h3 class="info-title">Detalles del Producto</h3>
                    
                    <input type="hidden" name="id_producto" value="<?php echo $producto_seleccionado['idPro']; ?>">

                    <div class="form-group-inline">
                         <div class="form-group" style="flex: 1;">
                            <label for="idPro">ID del Producto:</label>
                            <input type="text" id="idPro" value="<?php echo $producto_seleccionado['idPro']; ?>" disabled>
                        </div>
                        <div class="form-group" style="flex: 2;">
                            <label for="nombre">Nombre del Producto:</label>
                            <input type="text" id="nombre" value="<?php echo htmlspecialchars($producto_seleccionado['nomPro']); ?>" disabled>
                        </div>
                    </div>

                    <div>
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" disabled><?php echo htmlspecialchars($producto_seleccionado['desPro']); ?></textarea>
                    </div>

                    <div class="form-group-inline">
                         <div class="form-group" style="flex: 1;">
                            <label for="precio">Precio Unitario:</label>
                            <input type="text" id="precio" value="$<?php echo number_format($producto_seleccionado['preUni'], 2); ?>" disabled>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label for="categoria">Categoría:</label>
                            <input type="text" id="categoria" value="<?php echo htmlspecialchars($producto_seleccionado['nomCat']); ?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cantidad_ingresar">Cantidad a Aumentar (Ingreso):</label>
                        <input type="number" id="cantidad_ingresar" name="cantidad_ingresar" min="1" required 
                               placeholder="Ingrese la cantidad de unidades">
                    </div>

    

                    <div class="btn-group">
                        <a href="registrarIngreso.php" class="btn-cancel" style="text-decoration: none; text-align: center;">Cancelar</a>
                        <button type="submit" name="enviar_ingreso" class="btn-submit">Confirmar Ingreso</button>
                    </div>

                </form>

            <?php 
         
            // FORMULARIO DE BÚSQUEDA Y RESULTADOS 

            else: 
            ?>
            
                <p class="search-instruction">Filtre por nombre, ID o categoría para seleccionar el producto a ingresar stock:</p> <br>

                <form action="registrarIngreso.php" method="POST" class="filter-form">
                    <div class="filter-controls">
                        <div class="form-group search-input">
                            <input type="text" name="consulta_busqueda" placeholder="ID o Nombre del Producto"
                                   value="<?php echo htmlspecialchars($prev_busqueda); ?>">
                        </div>
                        
                        <div class="form-group category-select">
                            <select name="id_categoria">
                                <option value="0">Filtrar por Categoría</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat['idCat']; ?>" 
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
                    <h3 class="results-count">Resultados de la Búsqueda (<?php echo count($productos_encontrados); ?>):</h3> <br>
                    
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
                                        <td><?php echo $producto['idPro']; ?></td>
                                        <td><?php echo htmlspecialchars($producto['nomPro']); ?></td>
                                        <td><?php echo htmlspecialchars($producto['nomCat']); ?></td>
                                        <td class="stock-cell"><?php echo $producto['stoAct']; ?></td>
                                        <td>
                                            <form action="registrarIngreso.php" method="POST" style="margin: 0;">
                                                <input type="hidden" name="id_producto" value="<?php echo $producto['idPro']; ?>">
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