<?php
// Archivo: funciones/funciones-exportacion.php

/**
 * @param array $data El array de resultados de la consulta de inventario.
 * @param string $fecha_reporte La fecha y hora de generación del reporte.
 * @return void Envía el archivo CSV al navegador y detiene la ejecución.
 */
function exportarAExcelInventario(array $data, string $fecha_reporte): void {
    
    //  Definir encabezados de la tabla para el CSV
    $headers = [
        'ID', 
        'Producto (DesPro)', 
        'Categoría', 
        'Costo Unitario', 
        'Precio Venta', 
        'Stock Actual', 
        'Umbral Mínimo', 
        'Fecha Registro'
    ];

    //  Configurar la descarga del archivo
    $filename = "Reporte_Inventario_" . date('Ymd_His') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // Abrir el flujo de salida (output stream)
    $output = fopen('php://output', 'w');

    // Añadir Byte Order Mark (BOM) para asegurar que Excel lea correctamente los acentos (UTF-8)
    fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

    //  Escribir los encabezados
    fputcsv($output, $headers, ';'); // Usamos punto y coma (;) como delimitador estándar para Excel en LatAm

    // Escribir las filas de datos
    foreach ($data as $row) {
        $row_data = [
            $row['idPro'],
            $row['desPro'],
            $row['nomCat'],
            // Reemplazar la coma decimal por punto y formatear para asegurar que Excel lo interprete como número
            str_replace(',', '.', number_format($row['preUni'], 2, '.', '')), 
            str_replace(',', '.', number_format($row['preVen'], 2, '.', '')),
            $row['stoAct'],
            $row['umbMinSo'],
            substr($row['FecReg'], 0, 10)
        ];
        fputcsv($output, $row_data, ';');
    }

    // Cerrar el flujo y detener la ejecución (VITAL para que no se imprima el resto del HTML)
    fclose($output);
    exit; 
}

/**
 * Función para exportar el Reporte de Auditoría de Movimientos a formato CSV.
 *
 * @param array $data El array de resultados de la consulta de auditoría.
 * @param string $fecha_reporte La fecha y hora de generación del reporte.
 * @return void Envía el archivo CSV al navegador y detiene la ejecución.
 */
function exportarAExcelAuditoria(array $data, string $fecha_reporte): void {
    
    // Definir encabezados
    $headers = [
        'ID Movimiento', 
        'Fecha', 
        'Tipo Acción', 
        'Producto (DesPro)', 
        'Categoría', 
        'Cantidad', 
        'Usuario', 
        'Razón/Detalle'
    ];

    //  Configurar la descarga
    $filename = "Reporte_Auditoria_" . date('Ymd_His') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    // 3. Abrir el flujo de salida
    $output = fopen('php://output', 'w');
    fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

    //  Escribir los encabezados
    fputcsv($output, $headers, ';'); 

    // Escribir las filas de datos
    foreach ($data as $row) {
        $row_data = [
            $row['idMov'],
            $row['fecMov'],
            $row['tipo_accion_texto'], 
            $row['desPro'],
            $row['nomCat'],
            $row['cantSto'],
            $row['nomUsu'],
            $row['razEgre']
        ];
        fputcsv($output, $row_data, ';');
    }

    // Cerrar y detener la ejecución
    fclose($output);
    exit;
}
?>