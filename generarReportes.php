<?php
include("logica/logica-reportes.php"); 

if (isset($_POST["regresarbtn"])) {
    header("Location: inicio.php");
    exit();
}
$filter_params = '';
if (isset($adminName) && !empty($adminName)) $filter_params .= "&admin_name=" . urlencode($adminName);
if (isset($tipoMov) && !empty($tipoMov)) $filter_params .= "&tipo_mov=" . urlencode($tipoMov);
if (isset($filterDate) && !empty($filterDate)) $filter_params .= "&filter_date=" . urlencode($filterDate);

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
     <?php include './components/sidebar.php'; ?>
     <main class="content-area">
    <header class="topbar">
       <h1>REPORTES</h1>
            <button type="button" class="regresarbtn" style="margin-left: 10px;">
                Cerrar Sesión
            </button> 
    </header>
    
    <div class="admin-box">

    <form method="GET" class="historial-filters">
    <div class="filter-group">
        <label for="admin_name">Filtrar por Nombre Administrador:</label>
        <input type="text" id="admin_name" name="admin_name" placeholder="Nombre Admin"
            value="<?php echo htmlspecialchars($adminName); ?>">
    </div>

    <div class="filter-group">
        <label for="tipo_mov">Filtrar por Tipo:</label>
        <select id="tipo_mov" name="tipo_mov">
            <option value="" <?php echo $tipoMov == '' ? 'selected' : ''; ?>>Todos</option>
            <option value="1" <?php echo $tipoMov == '1' ? 'selected' : ''; ?>>Ingreso</option>
            <option value="2" <?php echo $tipoMov == '2' ? 'selected' : ''; ?>>Egreso</option>
            <option value="3" <?php echo $tipoMov == '3' ? 'selected' : ''; ?>>Actualización</option>
            <option value="4" <?php echo $tipoMov == '4' ? 'selected' : ''; ?>>Eliminación</option>
        </select>
    </div>
    
    <div class="filter-group">
        <label for="filter_date">Filtrar por Fecha:</label>
        <input type="date" id="filter_date" name="filter_date"
            value="<?php echo htmlspecialchars($filterDate); ?>">
    </div>
    
    <button type="submit" class="btn-filter" style="padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer;">
        Filtrar
    </button>
    </form>

            <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <tr style="background: linear-gradient(135deg, #43a047, #1b5e20); color: white; text-align: left;">
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
                        <a href="?pag=<?php echo $pagina - 1; ?><?php echo $filter_params; ?>">&#10094;</a>
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
                        <a href="?pag=<?php echo $i; ?><?php echo $filter_params; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                    <?php 
                        endfor;
                    } 
                    ?>

                    <?php if (isset($pagina, $totalPaginas) && $pagina < $totalPaginas): ?>
                        <a href="?pag=<?php echo $pagina + 1; ?><?php echo $filter_params; ?>">&#10095;</a>
                    <?php else: ?>
                        <span class="disabled">&#10095;</span>
                    <?php endif; ?>
                </div>

                    <?php if (isset($pagina, $totalPaginas) && $pagina < $totalPaginas): ?>
                        <a href="?pag=<?php echo $pagina + 1; ?><?php echo $filter_params; ?>">&#10095;</a>
                    <?php else: ?>
                        <span class="disabled">&#10095;</span>
                    <?php endif; ?>
                
                
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