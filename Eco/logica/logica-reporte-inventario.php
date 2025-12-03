<?php

session_start(); 
include_once 'conexion/conexion.php'; 
include_once 'funciones-exportacion.php'; 

$data = []; // Guardara el resultado de la consulta productos o movimientos
$categorias = [];
$tipos_movimiento = [
    1 => 'Ingreso', 
    2 => 'Egreso/Venta',
    3 => 'Registro Producto',
    4 => 'Actualización Producto',
    5 => 'Eliminación Producto'
];

$mensaje_feedback = '';
$clase_feedback = '';

// 💡 Reemplazar con la variable de sesión real
$usuario_generador = $_SESSION['nomUsu'] ?? 'Admin General'; 
$fecha_reporte = date('Y-m-d H:i:s');

// OBTENER CATEGORÍAS (Para los Selects)


$sql_categorias = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
$res_categorias = $conexion->query($sql_categorias);
if ($res_categorias) {
    while ($cat = $res_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
}
// RECUPERAR FILTROS DESDE GET
$reporte_tipo = $_GET['reporte'] ?? 'inventario';
$reporte_titulo = ($reporte_tipo === 'auditoria') ? 'AUDITORÍA DE MOVIMIENTOS' : 'INVENTARIO DE PRODUCTOS';

// Filtros comunes
$id_categoria = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?? 0;
$busqueda_producto = trim($_GET['busqueda_producto'] ?? '');

// Filtros de Auditoría
$tipo_movimiento = filter_input(INPUT_GET, 'tipo_movimiento', FILTER_VALIDATE_INT) ?? 0;
$nombre_usuario = trim($_GET['nombre_usuario'] ?? '');



//  GENERACIÓN DE REPORTES (Consulta SQL)

$parametros = [];
$tipos = '';

if ($reporte_tipo === 'inventario') {
    $sql_reporte = "SELECT p.idPro, p.desPro, p.preUni, p.preVen, p.stoAct, p.umbMinSo, p.FecReg, c.nomCat
                    FROM productos p 
                    JOIN categoria_producto c ON p.idCatFK = c.idCat 
                    WHERE 1=1";
    
    if ($id_categoria > 0) {
        $sql_reporte .= " AND p.idCatFK = ?";
        $parametros[] = $id_categoria;
        $tipos .= 'i';
    }
    
    if (!empty($busqueda_producto)) {
        // Buscar por descripción o ID 
        if (is_numeric($busqueda_producto)) {
            $sql_reporte .= " AND (p.desPro LIKE ? OR p.idPro = ?)";
            $parametros[] = "%$busqueda_producto%";
            $parametros[] = (int)$busqueda_producto;
            $tipos .= 'si'; 
        } else {
            $sql_reporte .= " AND p.desPro LIKE ?";
            $parametros[] = "%$busqueda_producto%";
            $tipos .= 's'; 
        }
    }

    $sql_reporte .= " ORDER BY p.desPro LIMIT 500"; 
    
} elseif ($reporte_tipo === 'auditoria') {
    $sql_reporte = "SELECT m.idMov, m.fecMov, m.tipoAccion, m.cantSto, m.razEgre, p.desPro, c.nomCat, u.nomUsu
                    FROM movimientos m 
                    JOIN productos p ON m.idProFK = p.idPro
                    LEFT JOIN categoria_producto c ON p.idCatFK = c.idCat 
                    LEFT JOIN usuarios u ON m.idUsuFK = u.idUsu
                    WHERE 1=1";

    if ($id_categoria > 0) {
        $sql_reporte .= " AND p.idCatFK = ?";
        $parametros[] = $id_categoria;
        $tipos .= 'i';
    }
    
    if (!empty($busqueda_producto)) {
         if (is_numeric($busqueda_producto)) {
            $sql_reporte .= " AND (p.desPro LIKE ? OR p.idPro = ?)";
            $parametros[] = "%$busqueda_producto%";
            $parametros[] = (int)$busqueda_producto;
            $tipos .= 'si'; 
        } else {
            $sql_reporte .= " AND p.desPro LIKE ?";
            $parametros[] = "%$busqueda_producto%";
            $tipos .= 's'; 
        }
    }
    
    if ($tipo_movimiento > 0) {
        $sql_reporte .= " AND m.tipoAccion = ?";
        $parametros[] = $tipo_movimiento;
        $tipos .= 'i';
    }

    if (!empty($nombre_usuario)) {
        $sql_reporte .= " AND u.nomUsu LIKE ?";
        $parametros[] = "%$nombre_usuario%";
        $tipos .= 's'; 
    }
    
    $sql_reporte .= " ORDER BY m.fecMov DESC LIMIT 500"; 
}

// Ejecutar la consulta
if (isset($conexion) && $stmt = $conexion->prepare($sql_reporte)) {
    if (!empty($parametros)) {
        $refs = [];
        foreach ($parametros as $key => $value) {
            $refs[$key] = &$parametros[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], array_merge([$tipos], $refs));
    }
    $stmt->execute();
    $resultado = $stmt->get_result();
    while ($fila = $resultado->fetch_assoc()) {
        if ($reporte_tipo === 'auditoria') {
            $fila['tipo_accion_texto'] = $tipos_movimiento[$fila['tipoAccion']] ?? 'Desconocido';
        }
        $data[] = $fila;
    }
    $stmt->close();
}

// LÓGICA DE EXPORTACIÓN A EXCEL

if (isset($_POST['exportar_excel'])) {
    
    if (empty($data)) {
        // Muestra el mensaje de error en la misma página si no hay datos.
        $mensaje_feedback = "Error: No hay datos para exportar con los filtros aplicados.";
        $clase_feedback = "error";
    } else {
        
        if ($reporte_tipo === 'inventario') {
            exportarAExcelInventario($data, $fecha_reporte); 

        } elseif ($reporte_tipo === 'auditoria') {
            exportarAExcelAuditoria($data, $fecha_reporte); 
        }
    }
}

?>