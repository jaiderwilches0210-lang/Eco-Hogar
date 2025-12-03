<?php

include("../conexion/conexion.php");

$formato = $_GET['formato'] ?? 'excel'; 
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


$sqlReporteCompleto = "
    SELECT 
        m.idMov,
        u.nomUsu,
        CASE 
            WHEN m.tipMo = 1 THEN 'Ingreso'
            WHEN m.tipMo = 2 THEN 'Egreso'
            WHEN m.tipMo = 3 THEN 'Actualización'
            WHEN m.tipMo = 4 THEN 'Eliminación'
            ELSE m.tipMo
        END AS tipMo, 
        m.fecMov,
        p.nomPro, 
        m.cantSto
    FROM 
        movimientos m
    INNER JOIN 
        usuarios u ON m.idUsuFK = u.idUsu
    INNER JOIN 
        productos p ON m.idProFK = p.idPro
    {$whereClause}  /* <--- CLÁUSULA WHERE DINÁMICA APLICADA AQUÍ */
    ORDER BY 
        m.idMov DESC
";
$resultado = $conexion->query($sqlReporteCompleto);


if ($formato === 'excel' && $resultado && $resultado->num_rows > 0) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="Reporte_Movimientos_' . date('Ymd_His') . '.xls"');
    
    $output = fopen('php://output', 'w');

    fputcsv($output, array('ID M.', 'Usuario', 'Tipo', 'Fecha', 'Producto', 'Cantidad'), "\t"); 

    while ($fila = $resultado->fetch_assoc()) {
        fputcsv($output, array(
            $fila['idMov'],
            $fila['nomUsu'],
            $fila['tipMo'],
            $fila['fecMov'],
            $fila['nomPro'],
            $fila['cantSto']
        ), "\t");
    }

    fclose($output);
    exit;

} else if ($formato === 'pdf') {
    echo "Falta la implementación de la librería PDF.";
    exit;
} else {
    echo "No hay datos para exportar o formato no válido.";
}
?>