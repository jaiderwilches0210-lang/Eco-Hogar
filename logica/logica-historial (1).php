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

$whereClauses = [];
$filter_admin_name = '';
$filter_tipo_mov = '';
$filter_date = '';

if (isset($_GET['admin_name']) && !empty($_GET['admin_name'])) {
    $filter_admin_name = $conexion->real_escape_string($_GET['admin_name']);
    $whereClauses[] = "u.nomUsu LIKE '%" . $filter_admin_name . "%'";
}

if (isset($_GET['tipo_mov']) && $_GET['tipo_mov'] !== '') {
    $filter_tipo_mov = (int)$_GET['tipo_mov']; 
    $whereClauses[] = "m.tipMo = " . $filter_tipo_mov;
}

if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
    $filter_date = $conexion->real_escape_string($_GET['filter_date']);
    $whereClauses[] = "DATE(m.fecMov) = '" . $filter_date . "'";
}

$where_sql = count($whereClauses) > 0 ? " WHERE " . implode(" AND ", $whereClauses) : "";

$porPagina = 10;
$sql_count_query = "SELECT COUNT(m.idMov) AS total
                    FROM movimientos m
                    INNER JOIN usuarios u ON m.idUsuFK = u.idUsu
                    " . $where_sql;

$sql_count = $conexion->query($sql_count_query);

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
                " . $where_sql . "
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

$filter_params = "";
if (!empty($filter_admin_name)) {
    $filter_params .= "&admin_name=" . urlencode($filter_admin_name);
}
if ($filter_tipo_mov !== '') {
    $filter_params .= "&tipo_mov=" . $filter_tipo_mov;
}
if (!empty($filter_date)) {
    $filter_params .= "&filter_date=" . urlencode($filter_date);
}
?>


