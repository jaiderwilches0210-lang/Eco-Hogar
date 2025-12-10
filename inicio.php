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
<?php include './components/sidebar.php'; ?>
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
                    <div class="color"><img src="imagenes/imagen1.jpeg" alt="Imagen Decorativa 1"></div>
                    <div class="color"><img src="imagenes/imagen2.jpeg" alt="Imagen Decorativa 2"></div>
                    <div class="color"><img src="imagenes/imagen3.jpeg" alt="Imagen Decorativa 3"></div>
                    <div class="color"><img src="imagenes/imagen4.jpeg" alt="Imagen Decorativa 4"></div>
                    <div class="color"><img src="imagenes/imagen5.jpeg" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/imagen6.jpeg" alt="Imagen Decorativa 6"></div>
                    <div class="color"><img src="imagenes/imagen7.jpeg" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/imagen8.jpeg" alt="Imagen Decorativa 6"></div>
                    <div class="color"><img src="imagenes/imagen9.jpeg" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/imagen10.jpeg" alt="Imagen Decorativa 6"></div>  
            </div>
        </main>
        
    </div> </body>
</html>