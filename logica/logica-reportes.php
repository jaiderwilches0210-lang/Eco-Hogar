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
        case 4:
            return 'Eliminación';
        default:
            return 'Desconocido';
    }
}

$nombreAdmin = $_GET['admin_name'] ?? '';
$tipoMov = $_GET['tipo_mov'] ?? '';
$fecha = $_GET['filter_date'] ?? '';

$whereClauses = [];

if (!empty($nombreAdmin)) {
    $nombreAdminEscapado = $conexion->real_escape_string($nombreAdmin);
    $whereClauses[] = "u.nomUsu LIKE '%$nombreAdminEscapado%'";
}

if (!empty($tipoMov)) {
    $tipoMovEntero = (int)$tipoMov;
    $whereClauses[] = "m.tipMo = $tipoMovEntero";
}

if (!empty($fecha)) {
    $fechaEscapada = $conexion->real_escape_string($fecha);
    $whereClauses[] = "DATE(m.fecMov) = '$fechaEscapada'";
}

$whereSQL = count($whereClauses) > 0 ? " WHERE " . implode(" AND ", $whereClauses) : "";

$filterParams = '';
if (!empty($nombreAdmin)) {
    $filterParams .= "&admin_name=" . urlencode($nombreAdmin);
}
if (!empty($tipoMov)) {
    $filterParams .= "&tipo_mov=" . urlencode($tipoMov);
}
if (!empty($fecha)) {
    $filterParams .= "&filter_date=" . urlencode($fecha);
}

$porPagina = 7;

$sql_count = "SELECT COUNT(m.idMov) AS total 
              FROM movimientos m
              INNER JOIN productos p ON m.idProFK = p.idPro
              INNER JOIN usuarios u ON m.idUsuFK = u.idUsu
              $whereSQL";

$resultado_count = $conexion->query($sql_count);

$totalReg = 0;
if ($resultado_count) {
    $totalReg = $resultado_count->fetch_assoc()['total'];
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
                 $whereSQL
                 ORDER BY m.fecMov DESC
                 LIMIT $inicio, $porPagina";
    
    $resultadoDatos = $conexion->query($sqlDatos);

    if ($resultadoDatos) {
        $sqlHistorial = $resultadoDatos; 
    } else {
        error_log("Error en consulta de historial: " . $conexion->error);
    }
}
?>