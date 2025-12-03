<?php
include("conexion/conexion.php");

// Contar registros
$sql_count = $conexion->query("SELECT COUNT(*) AS total FROM productos");
$totalReg = $sql_count->fetch_assoc()['total'];

// Configuración
$porPagina = 15;
$pagina = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($pagina < 1) { $pagina = 1; }

$inicio = ($pagina - 1) * $porPagina;

// Obtener datos paginados
$sql = $conexion->query("
    SELECT p.idPro, c.nomCat, p.nomPro, p.desPro, p.preUni, p.stoAct
    FROM productos p
    INNER JOIN categoria_producto c
        ON p.idCatFK = c.idCat
    LIMIT $inicio, $porPagina
");

if (!$sql) {
    die("Error en la consulta: " . $conexion->error);
}

$totalPaginas = ceil($totalReg / $porPagina);
?>
