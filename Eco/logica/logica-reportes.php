<?php
include("./conexion/conexion.php"); 

$registrosPorPagina = 10;

$pagina = isset($_GET['pag']) && is_numeric($_GET['pag']) ? (int)$_GET['pag'] : 1;
$offset = ($pagina - 1) * $registrosPorPagina;

$adminName = $_GET['admin_name'] ?? ''; 
$tipoMov = $_GET['tipo_mov'] ?? ''; 
$filterDate = $_GET['filter_date'] ?? ''; 

$whereClause = "WHERE 1=1 "; 

if (!empty($adminName)) {
    $adminNameEscaped = $conexion->real_escape_string($adminName);
    $whereClause .= " AND u.nomUsu LIKE '%$adminNameEscaped%'";
}

if (!empty($tipoMov)) {
    $tipoMovEscaped = $conexion->real_escape_string($tipoMov);
    $whereClause .= " AND m.tipMo = '$tipoMovEscaped'";
}

if (!empty($filterDate)) {
    $filterDateEscaped = $conexion->real_escape_string($filterDate);
    $whereClause .= " AND DATE(m.fecMov) = '$filterDateEscaped'"; 
}

$sqlBase = "
    SELECT 
        m.idMov AS idMov,
        u.nomUsu AS nomUsu,
        CASE 
            WHEN m.tipMo = 1 THEN 'Ingreso'
            WHEN m.tipMo = 2 THEN 'Egreso'
            WHEN m.tipMo = 3 THEN 'Actualización'
            WHEN m.tipMo = 4 THEN 'Eliminación'
            ELSE m.tipMo
        END AS tipMo, 
        m.fecMov AS fecMov,
        p.nomPro AS nomPro, 
        m.cantSto AS cantSto
    FROM 
        movimientos m
    INNER JOIN 
        usuarios u ON m.idUsuFK = u.idUsu
    INNER JOIN 
        productos p ON m.idProFK = p.idPro
    {$whereClause}  
    ORDER BY 
        m.idMov DESC
";

$sqlCount = "SELECT COUNT(m.idMov) AS total FROM movimientos m {$whereClause}";
$resultadoCount = $conexion->query($sqlCount);

if ($resultadoCount === false) {
    $totalRegistros = 0; 
} else {
    $filaCount = $resultadoCount->fetch_assoc();
    $totalRegistros = (int)$filaCount['total'];
}

$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

if ($pagina > $totalPaginas && $totalPaginas > 0) {
    $pagina = $totalPaginas;
    $offset = ($pagina - 1) * $registrosPorPagina;
}

$sqlFinal = "{$sqlBase} LIMIT {$offset}, {$registrosPorPagina}";

$sqlReporte = $conexion->query($sqlFinal);

if ($sqlReporte === false) {
    $sqlReporte = (object) array('num_rows' => 0, 'fetch_assoc' => function() { return null; }); 
}

?>
