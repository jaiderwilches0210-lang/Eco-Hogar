<?php
include("controlador/controlador_db.php");
include("logica/logicaInicio.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title> <style>
        @import url('css/style-inicio.css');
    </style>
    </head>
<body>
    
    <div class="sidebar">
       <img src="imagenes/logo.png" alt="Logo de la Aplicación" style="border-radius: 50%;">
        <nav class="menu">
            <ul>
                <li><a 
                    href="verInventario.php"
                    class="button-a"
                >Ver Inventario</a></li>
                
                <li><a
                    href="registrarProducto.php"
                    class="button-a"
                >Registrar Producto</a></li>

                <li><a
                    href="eliminarProducto.php" 
                    class="button-a"
                >Eliminar Producto</a></li>
                
                <li><a 
                    href="registrarIngreso.php" 
                    class="button-a"
                >Registrar Ingreso</a></li>
                
                <li><a 
                    href="registrarEgreso.php" 
                    class="button-a"
                >Registrar Egreso</a></li>
                
                <li><a 
                    href="historialMovimientos.php" 
                    class="button-a"
                >Historial Movimientos</a></li>
                
                <li><a 
                    href="generarReportes.php" 
                    class="button-a"
                >Generar Reportes</a></li>
            </ul>
        </nav>
  </div>



        <main class="content-area">
       <header class="topbar">
            <h1>Eco-hogar</h1>
          
        </header>       
            <div class="container" style="margin-top: 7%;"> 
                 <form action="" method="POST" style="position: absolute; right: 30px; top: 30px; margin: 0;">
            <button type="submit" name="cerrar-sesion" class="cerrar-sesion">
                Cerrar Sesión
            </button>
        </form>
                <div class="palette">
                    <div class="color"><img src="imagenes/logo.png" alt="Imagen Decorativa 1"></div>
                    <div class="color"><img src="imagenes/login.png" alt="Imagen Decorativa 2"></div>
                    <div class="color"><img src="imagenes/logo.png" alt="Imagen Decorativa 3"></div>
                    <div class="color"><img src="imagenes/login.png" alt="Imagen Decorativa 4"></div>
                    <div class="color"><img src="imagenes/logo.png" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/login.png" alt="Imagen Decorativa 6"></div>
                    <div class="color"><img src="imagenes/logo.png" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/login.png" alt="Imagen Decorativa 6"></div>
                    <div class="color"><img src="imagenes/logo.png" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/login.png" alt="Imagen Decorativa 6"></div>  
            </div>
        </main>
        
    </div> </body>
</html>