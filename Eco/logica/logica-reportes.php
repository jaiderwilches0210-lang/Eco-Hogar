<?php
include("conexion/conexion.php"); 

$porPagina = 20;

$sql_count = $conexion->query("SELECT COUNT(idMov) AS total FROM movimientos");

$totalReg = 0;
if ($sql_count) {
    $totalReg = $sql_count->fetch_assoc()['total'];
}

$totalPaginas = ceil($totalReg / $porPagina);

$pagina = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($pagina < 1) $pagina = 1;
if ($pagina > $totalPaginas && $totalPaginas > 0) $pagina = $totalPaginas;

$inicio = ($pagina - 1) * $porPagina;

$sqlReporte = null; 

if ($totalReg > 0) {
    $sqlDatos = "SELECT 
                    m.idMov, 
                    u.nomUsu,
                    m.tipMo,
                    m.fecMov,
                    p.nomPro,
                    m.cantSto
                 FROM movimientos m
                 INNER JOIN usuarios u ON m.idUsuFK = u.idUsu
                 INNER JOIN productos p ON m.idProFK = p.idPro
                 ORDER BY m.fecMov DESC
                 LIMIT $inicio, $porPagina";
    
    $resultadoDatos = $conexion->query($sqlDatos);

    if ($resultadoDatos) {
        $sqlReporte = $resultadoDatos; 
    } else {
        error_log("Error en consulta de reportes de movimientos: " . $conexion->error);
    }
}
?>