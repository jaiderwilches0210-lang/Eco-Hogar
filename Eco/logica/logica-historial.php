<?php
include("conexion/conexion.php"); 

function getTipoMovimiento($tipo) {
    switch ($tipo) {
        case 1:
            return 'Ingreso';
        case 2:
            return 'Egreso';
        case 3:
            return 'Actualización'; 
        default:
            return 'Desconocido';
    }
}

$porPagina = 10;
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

$sqlHistorial = null; 

if ($totalReg > 0) {
    $sqlDatos = "SELECT m.idMov, m.fecMov, p.nomPro, m.tipMo, m.cantSto, u.nomUsu, m.razEgre
                 FROM movimientos m
                 INNER JOIN productos p ON m.idProFK = p.idPro
                 INNER JOIN usuarios u ON m.idUsuFK = u.idUsu
                 ORDER BY m.fecMov DESC
                 LIMIT $inicio, $porPagina";
    
    $resultadoDatos = $conexion->query($sqlDatos);

    if ($resultadoDatos) {
        $sqlHistorial = $resultadoDatos; 
    } else {
        error_log("Error en consulta de historial: " . $conexion->error);
        die("Error al cargar los datos: " . $conexion->error); 
    }
}

?>

