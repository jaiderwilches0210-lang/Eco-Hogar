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
        @import url('css/style-historial.css');
    </style>
</head>
<body>
    <header class="main-header">
        <img src="imagenes/logo.png" alt="Logo de la Aplicación" style="border-radius: 50%;">
        <ul class="nav-menu">
            <li><a href="inicio.php">INICIO</a></li>
            <li><a href="registrarProducto.php">REGISTRAR</a></li>
            <li><a href="verInventario.php">INVENTARIO</a></li>
            <li><a href="historialMovimientos.php">HISTORIAL</a></li>
            <li><a href="generarReportes.php" style="color: #2200fcff;">REPORTES</a></li>
            <li style="margin-left: auto;"><a href="#">+</a></li>
        </ul>
        <form method="POST">
            <button type="submit" name="regresarbtn" class="regresarbtn" style="background-color: #d9534f;">Cerrar Sesión</button>
        </form>
    </header>


    <div class="admin-box">
        <h2>Generar Reportes</h2>

        <div class="historial-filters">
            <form method="GET" action="">
                <div class="filter-group">
                    <label for="admin_name">Filtrar por Nombre Administrador:</label>
                    <input type="text" id="admin_name" name="admin_name" placeholder="Nombre Admin" value="<?php echo $adminName; ?>" style="padding: 10px; border: none; border-radius: 5px; width: 100%;">
                </div>

                <div class="filter-group">
                    <label for="tipo_mov">Filtrar por Tipo:</label>
                    <select id="tipo_mov" name="tipo_mov">
                        <option value="">Todos</option>
                        <option value="1" <?php if ($tipoMov == '1') echo 'selected'; ?>>Ingreso</option>
                        <option value="2" <?php if ($tipoMov == '2') echo 'selected'; ?>>Egreso</option>
                        <option value="3" <?php if ($tipoMov == '3') echo 'selected'; ?>>Actualización</option>
                        <option value="4" <?php if ($tipoMov == '4') echo 'selected'; ?>>Eliminación</option>
                    </select>
                </div>
            
                <div class="filter-group" style="flex: 1;">
                    <label for="filter_date">Filtrar por Fecha:</label>
                    <input type="date" id="filter_date" name="filter_date" value="<?php echo $filterDate; ?>">
                </div>
            
                <button type="submit" class="btn-filter" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 15px;">
                    Generar Reporte
                </button>
            </form>
        </div>
        
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <tr style="background-color: #4CAF50; color: white; text-align: left;">
                <th>ID M.</th>
                <th>Producto</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Cantidad</th>
            </tr>

            <?php 
            if (isset($sqlReporte) && $sqlReporte->num_rows > 0) {
                while ($fila = $sqlReporte->fetch_assoc()) { 
            ?>
                <tr>
                    <td><?php echo $fila['idMov']; ?></td> 
                    <td><?php echo $fila['nomPro'] ?? 'N/A'; ?></td> 
                    <td><?php echo $fila['tipMo']; ?></td> 
                    <td><?php echo $fila['fecMov']; ?></td> 
                    <td><?php echo $fila['nomUsu'] ?? 'N/A'; ?></td> 
                    <td><?php echo $fila['cantSto']; ?></td> 
                </tr>
            <?php 
                }
            } else { 
            ?>
                <tr>
                    <td colspan="6" style="text-align: center;">No se encontraron movimientos con los filtros aplicados.</td>
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
        
        <div class="export-buttons" style="text-align: right; margin-top: 20px;">
            <?php
            $query_params = "admin_name=" . urlencode($adminName) . 
                            "&tipo_mov=" . urlencode($tipoMov) . 
                            "&filter_date=" . urlencode($filterDate);
            ?>

            <a href="logica/exportar-reporte.php?formato=excel&<?php echo $query_params; ?>" 
               style="text-decoration: none;">
                <button type="button" style="background-color: #337ab7; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">
                    Exportar en Excel
                </button>
            </a>
            
            <a href="logica/exportar-reporte.php?formato=pdf&<?php echo $query_params; ?>" 
               style="text-decoration: none;">
                <button type="button" style="background-color: #f0ad4e; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">
                    Exportar en PDF
                </button>
            </a>
        </div>
    </div>
</body>
</html>