# Eco-Hogar
Trabajo de grupo Eco-Hogar
https://drive.google.com/drive/folders/1xgsXxeLSVzJueCvZZ6YWK_kqjkRK3tcT?usp=drive_link


/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: #e1eae5;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    overflow-x: hidden;
    /* QUITAMOS overflow: hidden; del body para evitar problemas de scroll innecesarios */
}

/* HEADER */
header {
    
    backdrop-filter: blur(15px);
    width: 98%;
    max-width: 2000px;
    height: auto;
    min-height: 60px;
    
    display: flex; 
    align-items: center;
    justify-content: space-between; /* Distribuye el espacio entre los elementos: Logo | Menú | Botón Salir */
    /* Fin de cambios clave */
    border-radius: 0 0 35px 35px;
    border: 2px solid rgba(255,255,255,0.4);
    box-shadow: 0 10px 30px rgba(0,0,0,0.25);
    padding: 0.5rem 3rem; 
    gap: 2rem;
    position: relative;
}
.main-header{
    background: #0d8166;
}
/* Ajuste del botón Salir */
.main-header form {
    /* Mantenemos estos estilos para asegurar que esté en el flujo */
    position: static !important; 
    right: initial !important;
    top: initial !important;
}

/* NUEVO MENÚ HORIZONTAL */
.horizontal-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex; /* <-- HACE QUE SEA HORIZONTAL */
    gap: 0.5rem; 
    flex-wrap: wrap; 
    justify-content: center; 
}

.horizontal-nav li {
    margin: 0; 
}

.horizontal-nav a {
    /* Estilos base */
    padding: 8px 15px; 
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem; 
    color: white;
    border: 2px solid white;
    border-radius: 20px; 
    transition: all 0.3s ease;
    transform: none !important; 
    box-shadow: none !important;
}

.horizontal-nav a:hover {
    background: rgba(255,255,255,0.5);
    color: #006e93;
    border-color: #006e93;
    transform: translateY(-2px); 
}

/* *** AJUSTE PRINCIPAL DE LAYOUT: ELIMINAR O SOBRESCRIBIR EL ANTIGUO SIDEBAR LATERAL *** */
.admin-box {
    width: 98%;
    max-width: 2000px;
    min-height: 70vh;
    max-height: 80vh;

    margin-top: 2rem;  
    padding: 2rem;

    /* APLICAMOS UN NUEVO GRID SIN SIDEBAR A LA DERECHA */
    display: grid;
    grid-template-columns: 1fr; /* Una sola columna para el contenido */
    grid-template-rows: auto 1fr;
    grid-template-areas:
        "titulo"
        "contenido"; /* El contenido ocupa todo el espacio restante */

    gap: 2rem;

    border-radius: 35px;
    border: 2px solid rgba(255,255,255,0.4);
    background: rgba(255,255,255,0.1);
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
}

.sidebar { 
    display: none; /* <-- Ocultamos el menú lateral de forma permanente */
}

/* El elemento .content-area debe ahora ocupar todo el espacio */
.content-area {
    grid-area: contenido;
    /* Ajustes para el contenido si es necesario, pero ya está en el flujo */
}

/* MEDIA QUERIES para pantallas más pequeñas */
@media (max-width: 900px) {
    header {
        flex-direction: column; /* Apilamos los elementos en pantallas pequeñas */
        gap: 1rem;
        padding: 0.5rem 1rem;
    }

    .horizontal-nav ul {
        justify-content: center;
        gap: 0.25rem;
    }

    .horizontal-nav a {
        padding: 8px 12px;
        font-size: 0.85rem;
    }

    .admin-box {
        grid-template-columns: 1fr;
        grid-template-areas:
            "titulo"
            /* No necesitamos "sidebar" aquí si ya lo ocultamos, pero si es necesario se quita la media query de sidebar */
            "contenido"; 
        padding: 1.5rem; 
    }
    
    .container {
        height: 200px; 
    }
}

/* Imagen en Header */
img {
    height: 65px; 
    width: 65px;
    object-fit: cover;
}

/* BOTÓN SALIR */
.cerrar-sesion {
    background: #ff4b5c;
    border: none;
    color: white;
    padding: 10px 35px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.cerrar-sesion:hover {
    background: #e63946;
    transform: translateY(-2px); 
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
}

/* TÍTULO */
.titulo-area {
    grid-area: titulo;
    text-align: center;
    color: white;
}

.titulo-area h1 {
    font-size: clamp(1rem, 3vw, 2rem);
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    letter-spacing: 1px;
}

/* Cajas de Colores (Carrusel) */
.container {
    width: 95%;
    height: clamp(250px, 45vh, 500px);
    border-radius: 1.5em;
    overflow: hidden;
    background: white;
    display: flex;
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    margin: 0 auto; /* Centrar el contenedor */
}

.palette {
    display: flex;
    width: 100%;
}

.color {
    flex: 1;
    transition: flex 0.4s ease-in-out;
}

.color:hover {
    flex: 3;
}

.color img {
    width: 100%;
    height: 100%;
    object-fit: cover;



    <?php
include("controlador/controlador_db.php");
include("logica/logicaInicio.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title> 
    <style>
        @import url('css/style-inicio.css');
    </style>
</head>
<body>
    
    <header class="main-header">
        <img src="imagenes/logo.png" al t="Logo de la Aplicación" style="border-radius: 50%;">
        
        <nav class="horizontal-nav"> 
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
        <form action="" method="POST" style="margin: 0;"> 
            <button type="submit" name="cerrar-sesion" class="cerrar-sesion">
                Salir
            </button>
        </form>
    </header>

    <div class="admin-box">

        <main class="content-area">

            <section class="titulo-area">
                <h1>Panel de Administración</h1>
            </section>
            
            <div class="container" style="margin-top: 7%;"> 
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
                    <div class="color"><img src="imagenes/logo.png" alt="Imagen Decorativa 5"></div>
                    <div class="color"><img src="imagenes/login.png" alt="Imagen Decorativa 6"></div>
                </div>
            </div>
            
        </main>
        
        <div class="sidebar" style="grid-area: sidebar; display: none;"></div> 
        </div> </body>
</html>

}

