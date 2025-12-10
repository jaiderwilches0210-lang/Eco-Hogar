<!-- componentes/sidebar.php -->
<style>
        @import url('css/sidebar.css');
    </style> 

<div class="sidebar">
      <div class="sidebar-header">
        <img src="imagenes/logo.png" alt="Logo de la Aplicación" style="border-radius: 10px;">
    </div>
        <nav class="sidebar-nav">
             <ul class="ul">
             <li class="li"><a href="inicio.php">Home</a></li>

             <p class="section-title"> GESTIÓN DE INVENTARIO</p>

                <li class="li"><a href="verInventario.php"><i class="icon">&#128202;</i>Ver Inventario</a></li>  
            
                 <p class="section-title">PRODUCTOS</p>
                 <li class="li"><a href="eliminarProducto.php">Listar Productos</a></li>
                 <li class="li"><a href="registrarProducto.php"> Registrar Producto</a></li>
                 
                
                 <p class="section-title">MOVIMIENTOS</p>
                <li class="li"><a href="registrarIngreso.php" class="a"> Registrar Ingreso</a></li>
                
                <li class="li"><a href="registrarEgreso.php"class="a"> Registrar Egreso</a></li>
                
                <li class="li"><a href="historialMovimientos.php" class="a"> Historial Movimientos</a></li>

                <p class="section-title">REPORTES</p>
            
            <li class="li"><a href="generarReporteInventario.php" class="a">📑 Generar Reporte Inventario</a></li>
            </ul>
            
        </nav>
  </div>