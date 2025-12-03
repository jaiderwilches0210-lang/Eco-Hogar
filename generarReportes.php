<?php
include("logica/logica-reportes.php"); 

if (isset($_POST["regresarbtn"])) {
    header("Location: inicio.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reportes</title>
    <style>
        @import url('css/style-verInventario.css'); 
    
    </style>
</head>

<body>
     <?php include './components/sidebar.php'; ?>
     <main class="content-area">
    <header class="topbar">
       <h1>REPORTES</h1>
        
            <button type="button" class="regresarbtn" style="margin-left: 10px;">
                Cerrar Sesión
            </button> 
    </header>
    
    <ul class="nav-menu">
        <li><a href="inicio.php">INICIO</a></li>
        <li><a href="registrarProducto.php">REGISTRAR</a></li>
        <li><a href="verInventario.php">INVENTARIO</a></li>
        <li><a href="historialMovimientos.php">HISTORIAL</a></li>
        <li><a href="generarReportes.php" style="color: #4CAF50;">REPORTES</a></li>
    </ul>

    <div class="admin-box">
        <h2>Generar Reporte</h2> 
        <div class="report-container">
            
            <div class="data-table-section">
                <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                    <tr style="background-color: #4CAF50; color: white; text-align: left;">
                        <th>ID M</th>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                    </tr>

                    <?php 
                    
                    if (isset($sqlReporte) && $sqlReporte->num_rows > 0) {
                        while ($fila = $sqlReporte->fetch_assoc()) {
                    ?>
                            <tr>
                            <td><?php echo $fila['idMov']; ?></td>              
                            <td><?php echo $fila['nomUsu']; ?></td>            
                            <td><?php echo $fila['tipMo']; ?></td>             
                            <td><?php echo $fila['fecMov']; ?></td> 
                            <td><?php echo $fila['nomPro']; ?></td> 
                            <td><?php echo $fila['cantSto']; ?></td> 
                            </tr>
                    <?php
                        }
                    } else { 
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No se encontraron datos según los filtros.</td>
                        </tr>
                    <?php 
                    } 
                    ?>
                </table>

                <div class="pagination">
                    <?php if (isset($pagina) && $pagina > 1): ?>
                        <a href="?pag=<?php echo $pagina - 1; ?>">&#10094;</a>
                    <?php else: ?>
                        <span class="disabled">&#10094;</span>
                    <?php endif; ?>

                    <?php 
                    if (isset($totalPaginas)) {
                        for ($i = 1; $i <= $totalPaginas; $i++): 
                    ?>
                        <?php if ($i == $pagina): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?pag=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php 
                        endfor;
                    } 
                    ?>

                    <?php if (isset($pagina, $totalPaginas) && $pagina < $totalPaginas): ?>
                        <a href="?pag=<?php echo $pagina + 1; ?>">&#10095;</a>
                    <?php else: ?>
                        <span class="disabled">&#10095;</span>
                    <?php endif; ?>
                </div>
                
            </div>
            
            
            <div class="filters-panel">
                <h4>Filtros</h4>
                <form action="generarReportes.php" method="GET">
                    
                    <div class="filter-group">
                        <label for="tipo_reporte">Tipo de Reporte</label>
                        <select id="tipo_reporte" name="tipo_reporte">
                            <option value="1">Movimientos (Egresos/Ingresos)</option>
                            <option value="2">Stock Actual</option>
                            <option value="3">Productos por Categoría</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="formato_descarga">Formato de Descarga</label>
                        <select id="formato_descarga" name="formato_descarga">
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="fecha_generacion">Fecha de Generación</label>
                        <input type="date" id="fecha_generacion" name="fecha_generacion" value="<?php echo date('Y-m-d'); ?>" disabled>
                    </div>
                    
                    <div class="filter-group">
                        <label for="nombre_administrador">Nombre Administrador</label>
                        <input type="text" id="nombre_administrador" name="nombre_administrador" value="Nombre Admi" disabled>
                    </div>

                    <button type="submit" name="generarbtn" style="background-color: #4CAF50; color: white; padding: 10px; border: none; border-radius: 4px; width: 100%; cursor: pointer;">
                        Generar
                    </button>
                    
                </form>
            </div>
        </div>
     
        <div class="action-buttons">
            
                <button type="button" style="background-color: #337ab7; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">
                    Exportar en Excel
                </button>
           
                <button type="button" style="background-color: #f0ad4e; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">
                    Imprimir Reporte
                </button>
                <button type="button" style="background-color: #d9534f; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">
                    Cancelar Reporte
                </button>
        </div>
    </div>
    
</body>
</html>