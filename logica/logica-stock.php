<?php
include("./conexion/conexion.php"); 

$umbralBajo = 10; 
$porPagina = 15;


$filtro_activo = isset($_GET['filtro']) ? $_GET['filtro'] : '';
$where_clause = "";

if ($filtro_activo == 'bajo') {
    $where_clause = " WHERE p.stoAct <= $umbralBajo ";
}

$sql_count = $conexion->query("SELECT COUNT(*) AS total FROM productos p" . $where_clause);

if (!$sql_count) {
    die("Error al contar registros: " . $conexion->error);
}

$totalReg = $sql_count->fetch_assoc()['total'];

$pagina = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($pagina < 1) { 
    $pagina = 1; 
}

$totalPaginas = ceil($totalReg / $porPagina);

if ($pagina > $totalPaginas && $totalPaginas > 0) {
    $pagina = $totalPaginas;
}

$inicio = ($pagina - 1) * $porPagina;

$query = "
    SELECT p.idPro, c.nomCat, p.nomPro, p.desPro, p.preUni, p.stoAct
    FROM productos p
    INNER JOIN categoria_producto c
        ON p.idCatFK = c.idCat
    {$where_clause}  /* <-- Aplicamos el filtro aquí */
    ORDER BY p.idPro ASC
    LIMIT $inicio, $porPagina
";

$sql = $conexion->query($query);

if (!$sql) {
    die("Error en la consulta SQL: " . $conexion->error);
}

?>