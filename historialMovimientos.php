<?php
include("logica/logica-historial.php");
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
 <title>Historial de Movimientos</title>
 <style>
  @import url('css/style-verInventario.css');
  @import url('css/style-historial.css');
 </style>
</head>

<body>
 <?php include './components/sidebar.php'; ?>
 <main class="content-area">
 <header class="topbar">
  <h1>Historial de Movimientos</h1>
  <form action="login.php" method="POST">
  <button type="button" class="regresarbtn" style="margin-left: 10px;">
                Cerrar Sesión
       </button> 
    </form>
  </header>

 <div class="admin-box"> 
    <form method="GET" action="historialMovimientos.php">
        <div class="historial-filters">
    <div class="filter-group">
     <label for="admin_name">Filtrar por Nombre Administrador:</label><input type="text" id="admin_name" name="admin_name" placeholder="Nombre Admin"value="<?php echo htmlspecialchars($_GET['admin_name'] ?? ''); ?>">
    </div>
    <div class="filter-group">
     <label for="tipo_mov">Filtrar por Tipo:</label>
     <select id="tipo_mov" name="tipo_mov">
      <option value="">Todos</option>
      <option value="1" <?php echo (isset($_GET['tipo_mov']) && $_GET['tipo_mov'] == '1') ? 'selected' : ''; ?>>Ingreso</option>
      <option value="2" <?php echo (isset($_GET['tipo_mov']) && $_GET['tipo_mov'] == '2') ? 'selected' : ''; ?>>Egreso</option>
      <option value="3" <?php echo (isset($_GET['tipo_mov']) && $_GET['tipo_mov'] == '3') ? 'selected' : ''; ?>>Actualización</option>
      <option value="4" <?php echo (isset($_GET['tipo_mov']) && $_GET['tipo_mov'] == '4') ? 'selected' : ''; ?>>Eliminación</option>
     </select>
    </div>
    
    <div class="filter-group">
     <label for="filter_date">Filtrar por Fecha:</label>
     <input type="date" id="filter_date" name="filter_date" value="<?php echo htmlspecialchars($_GET['filter_date'] ?? ''); ?>">
    </div>
    <button type="submit" class="btn-filter" style="padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer;">
     Filtrar
    </button>
   </div>
</form>
  
<div style="display: flex;">
  
  <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
   <tr style="background: linear-gradient(135deg, #43a047, #1b5e20); color: white; text-align: left;">
    <th>ID M.</th>
    <th>Producto</th>
    <th>Tipo</th>
    <th>Fecha</th>
    <th>Usuario</th>
    <th>Cantidad</th>
    <th>Detalle</th>
   </tr>

   <?php 
   if (isset($sqlHistorial) && $sqlHistorial->num_rows > 0) {
    while ($fila = $sqlHistorial->fetch_assoc()) { 
   ?>
    <tr>
     <td><?php echo $fila['idMov']; ?></td> 
     <td><?php echo $fila['nomPro'] ?? 'N/A'; ?></td> 
     <td><?php echo getTipoMovimiento($fila['tipMo']); ?></td> 
     <td><?php echo $fila['fecMov']; ?></td> 
     <td><?php echo $fila['nomUsu'] ?? 'N/A'; ?></td> 
     <td><?php echo $fila['cantSto']; ?></td> 
     <td><?php echo $fila['razEgre']; ?></td> 
    </tr>
   <?php 
    }
   } else { 
   ?>
    <tr>
     <td colspan="7" style="text-align: center;">No se encontraron movimientos en el historial.</td>
    </tr>
   <?php 
   } 
   ?>
  </table>
</div>

  <br><div class="pagination">
   <?php if (isset($pagina) && $pagina > 1): ?>
                    <a href="?pag=<?php echo $pagina - 1; ?><?php echo $filterParams; ?>">&#10094;</a>
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
                         <a href="?pag=<?php echo $i; ?><?php echo $filterParams; ?>"><?php echo $i; ?></a>
    <?php endif; ?>
   <?php 
    endfor;
   } 
   ?>

   <?php if (isset($pagina, $totalPaginas) && $pagina < $totalPaginas): ?>
                    <a href="?pag=<?php echo $pagina + 1; ?><?php echo $filterParams; ?>">&#10095;</a>
   <?php else: ?>
    <span class="disabled">&#10095;</span>
   <?php endif; ?>
  </div>


 </div>

 <script>
        // Esta función debe capturar los valores de los filtros antes de exportar
  function exportarHistorial(formato) {
            const adminName = document.getElementById('admin_name').value;
            const tipoMov = document.getElementById('tipo_mov').value;
            const filterDate = document.getElementById('filter_date').value;

            let url = `exportar_historial.php?formato=${formato}`;

            if (adminName) url += `&admin_name=${encodeURIComponent(adminName)}`;
            if (tipoMov) url += `&tipo_mov=${encodeURIComponent(tipoMov)}`;
            if (filterDate) url += `&filter_date=${encodeURIComponent(filterDate)}`;

   window.location.href = url;
  }
 </script>
</body>
</html>