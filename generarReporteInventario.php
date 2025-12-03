<?php


include_once 'logica/logica-reporte-inventario.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generación de Reportes</title>
    
    <link rel="stylesheet" href="css/style-admin.css"> 
    <link rel="stylesheet" href="css/style-registro-final.css"> 
    <link rel="stylesheet" href="css/style-reporteInventario.css"> 
    
    
</head>
<body class="<?php echo 'reporte-' . htmlspecialchars($reporte_tipo); ?>">
    <?php include './components/sidebar.php'; ?>

    <main class="content-area">
     <header class="topbar">
        <h1>Productos</h1>
        <img src="imagenes/logo.png" alt="Logo de la Aplicación" style="border-radius: 50%;">

        <nav class="main-nav-menu">
        <ul>
            <li><a href="verInventario.php">Inventario</a></li>
            <li><a href="historialMovimientos.php">Movimientos</a></li>
            <li><a href="registrarProducto.php">Registrar Producto</a></li>
            <li><a href="registrarIngreso.php">Ingreso</a></li> 
            <li><a href="generarReporteInventario.php">Reportes</a></li> </ul>
    </nav>
        <form action="inicio.php" method="POST" style="position: absolute; right: 30px; top: 30px; margin: 0;">
            <button type="submit" name="regresarbtn" class="regresarbtn">Regresar</button>
        </form>
    </header>
    </main>
    <div class="admin-box">
        <h2 style="color: white;">Generación de Reportes</h2> 

        <div class="registro-box wide"> 

            <?php if (!empty($mensaje_feedback)): ?>
                <div class="resultado-server <?php echo htmlspecialchars($clase_feedback); ?>">
                    <?php echo htmlspecialchars($mensaje_feedback); ?>
                </div>
            <?php endif; ?>

            <p class="search-instruction">Seleccione el tipo de reporte y aplique los filtros:</p> <br>

            <form action="generarReporteInventario.php" method="GET" class="filter-form" id="filter-form">
                
                <div class="filter-controls">
                    
                    <div class="form-group half-width">
                        <label for="reporte">Tipo de Reporte:</label>
                        <select name="reporte" id="reporte" onchange="toggleAuditFilters(this.value)">
                            <option value="inventario" <?php echo ($reporte_tipo == 'inventario') ? 'selected' : ''; ?>>Reporte de Inventario</option>
                            <option value="auditoria" <?php echo ($reporte_tipo == 'auditoria') ? 'selected' : ''; ?>>Reporte de Auditoría</option>
                        </select>
                    </div>

                    <div class="form-group half-width">
                        <label for="categoria">Categoría:</label>
                        <select name="categoria" id="categoria">
                            <option value="0" <?php echo ($id_categoria == 0) ? 'selected' : ''; ?>>Todas las Categorías</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?php echo $cat['idCat']; ?>" <?php echo ($id_categoria == $cat['idCat']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nomCat']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group half-width">
                        <label for="busqueda_producto">Producto (ID o Descripción):</label>
                        <input type="text" name="busqueda_producto" id="busqueda_producto" 
                               placeholder="ID o Descripción" 
                               value="<?php echo htmlspecialchars($busqueda_producto); ?>">
                    </div>

                    <div class="form-group filtro-auditoria" id="filtro-tipo-movimiento">
                        <label for="tipo_movimiento">Tipo de Movimiento:</label>
                        <select name="tipo_movimiento" id="tipo_movimiento">
                            <option value="0" <?php echo ($tipo_movimiento == 0) ? 'selected' : ''; ?>>Todos</option>
                            <?php foreach ($tipos_movimiento as $cod => $nombre): ?>
                                <option value="<?php echo $cod; ?>" <?php echo ($tipo_movimiento == $cod) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group filtro-auditoria" id="filtro-usuario">
                        <label for="nombre_usuario">Nombre de Usuario:</label>
                        <input type="text" name="nombre_usuario" id="nombre_usuario" 
                               placeholder="Ej. Juan Pérez" 
                               value="<?php echo htmlspecialchars($nombre_usuario); ?>">
                    </div>

                    <button type="submit" class="btn-search">Generar/Filtrar Reporte</button>
                </div>
            </form>
            <div id="report-content-wrapper">
                
                <div class="reporte-final-documento" id="reporte-documento"> 
                    
                    <div class="document-header">
                        <img src="imagenes/logo.png" alt="Logo" class="logo-reporte">
                        <div class="header-info">
                            <h1>REPORTE DE <?php echo htmlspecialchars($reporte_titulo); ?></h1>
                            <p>Generado por: <?php echo htmlspecialchars($usuario_generador); ?></p>
                            <p>Fecha de Emisión: <?php echo htmlspecialchars($fecha_reporte); ?></p>
                        </div>
                    </div>

                    <hr class="document-separator">
                    
                    <div class="filtros-aplicados">
                        <h4>Filtros Aplicados:</h4>
                        <ul>
                            <?php if ($reporte_tipo === 'auditoria'): ?>
                                <li>**Tipo de Acción:** <?php 
                                        $tipo_texto = $tipos_movimiento[$tipo_movimiento] ?? 'todos';
                                        echo '**' . htmlspecialchars($tipo_movimiento == 0 ? 'todos' : $tipo_texto) . '**'; 
                                    ?>
                                </li>
                                <li>**Usuario Buscado:** <?php echo empty($nombre_usuario) ? '**Ninguno**' : htmlspecialchars($nombre_usuario); ?></li>
                            <?php endif; ?>
                            <li>**Producto Buscado:** <?php echo empty($busqueda_producto) ? '**Ninguno**' : htmlspecialchars($busqueda_producto); ?></li>
                            <li>**Categoría:** <?php 
                                   
                                        $cat_actual = array_filter($categorias, fn($c) => $c['idCat'] == $id_categoria);
                                        echo ($id_categoria > 0 && !empty($cat_actual)) 
                                            ? '**' . htmlspecialchars(reset($cat_actual)['nomCat']) . '**'
                                            : '**Todas**'; 
                                ?>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="table-scroll"> 
                        <?php if (!empty($data)): ?> 
                            <table class="data-table"> 
                                <thead>
                                    <?php if ($reporte_tipo === 'inventario'): ?>
                                        <tr>
                                            <th>ID</th>
                                            <th>Producto (DesPro)</th>
                                            <th>Categoría</th>
                                            <th>Costo Unitario</th> 
                                            <th>Precio Venta</th>
                                            <th>Stock Actual</th>
                                            <th>Umbral Mínimo</th>
                                            <th>Fec. Reg.</th>
                                        </tr>
                                    <?php elseif ($reporte_tipo === 'auditoria'): ?>
                                        <tr>
                                            <th>ID Mov</th>
                                            <th>Fecha</th> 
                                            <th>Tipo Acción</th>
                                            <th>Producto (DesPro)</th>
                                            <th>Categoría</th>
                                            <th>Cantidad</th>
                                            <th>Usuario</th>
                                            <th>Razón/Detalle</th>
                                        </tr>
                                    <?php endif; ?>
                                </thead>
                                <tbody>
                                    <?php if ($reporte_tipo === 'inventario'): ?>
                                        <?php foreach ($data as $pro): ?> 
                                            <?php $stock_class = ($pro['stoAct'] <= $pro['umbMinSo']) ? 'stock-low' : ''; ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($pro['idPro']); ?></td>
                                                <td><?php echo htmlspecialchars($pro['desPro']); ?></td>
                                                <td><?php echo htmlspecialchars($pro['nomCat']); ?></td>
                                                <td>$ <?php echo number_format($pro['preUni'], 2, ',', '.'); ?></td>
                                                <td>$ <?php echo number_format($pro['preVen'], 2, ',', '.'); ?></td>
                                                <td class="<?php echo $stock_class; ?>"><?php echo number_format($pro['stoAct'], 0, ',', '.'); ?></td>
                                                <td><?php echo number_format($pro['umbMinSo'], 0, ',', '.'); ?></td>
                                                <td><?php echo htmlspecialchars(substr($pro['FecReg'], 0, 10)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php elseif ($reporte_tipo === 'auditoria'): ?>
                                        <?php foreach ($data as $mov): ?> 
                                            <tr>
                                                <td><?php echo htmlspecialchars($mov['idMov']); ?></td>
                                                <td><?php echo htmlspecialchars($mov['fecMov']); ?></td>
                                                <td><?php echo htmlspecialchars($mov['tipo_accion_texto']); ?></td>
                                                <td><?php echo htmlspecialchars($mov['desPro']); ?></td>
                                                <td><?php echo htmlspecialchars($mov['nomCat']); ?></td>
                                                <td><?php echo number_format($mov['cantSto'], 0, ',', '.'); ?></td>
                                                <td><?php echo htmlspecialchars($mov['nomUsu']); ?></td>
                                                <td><?php echo htmlspecialchars($mov['razEgre']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <p class="report-footer">Fin del Reporte. **Total de Registros:** <?php echo count($data); ?></p>
                        <?php else: ?>
                            <p style="text-align: center; margin: 50px 0;">No se encontraron registros para el reporte de <?php echo htmlspecialchars($reporte_tipo); ?> con los filtros aplicados.</p>
                            <p class="report-footer">Fin del Reporte.</p>
                        <?php endif; ?>
                    </div> 
                    
                    <div class="report-actions">
                        <form method="POST" action="generarReporteInventario.php?<?php echo htmlspecialchars(http_build_query($_GET)); ?>" style="display:inline;">
                            <button type="submit" name="exportar_excel" class="btn-primary">Exportar en Excel</button>
                        </form>
                        <button type="button" onclick="window.print()" class="btn-secondary">Imprimir Reporte (PDF)</button>
                    </div>

                </div>
            </div> 
        </div>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const reporteSelect = document.getElementById('reporte');
        
        // Función para cambiar la clase del body y mostrar/ocultar filtros
        window.toggleAuditFilters = function(reporte) {
            const body = document.body;
            
            // Remueve la clase anterior y añade la nueva
            body.classList.remove('reporte-inventario', 'reporte-auditoria');
            body.classList.add('reporte-' + reporte);
        }

        // INICIALIZACIÓN: Inicializar con el valor actual al cargar la página
        toggleAuditFilters(reporteSelect.value);

        // EVENTO: Cuando el usuario cambia el selector de reporte
        reporteSelect.addEventListener('change', function() {
            toggleAuditFilters(this.value);
        });
    });

    </script>
</body>
</html>