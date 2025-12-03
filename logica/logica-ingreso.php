<?php

session_start(); // Inicia la sesión para acceder a $_SESSION

include "conexion/conexion.php"; 

$producto_seleccionado = null; 
$productos_encontrados = [];
$categorias = []; 
$mensaje_resultado = '';
$tipo_mensaje = '';

$sql_categorias = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
$res_categorias = $conexion->query($sql_categorias);

if ($res_categorias) {
    while ($cat = $res_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
}

if (isset($_POST['enviar_ingreso'])) {
    
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
    $cantidad_ingresar = filter_input(INPUT_POST, 'cantidad_ingresar', FILTER_VALIDATE_INT);
    $fecha_actual = date('Y-m-d H:i:s'); 
    
    if ($idPro === false || $cantidad_ingresar === false || $cantidad_ingresar < 1) {
        $mensaje_resultado = "Error: Datos de ingreso inválidos. La cantidad debe ser un número positivo.";
        $tipo_mensaje = "error";
    } else {
        
        $conexion->begin_transaction();
        
        $stock_anterior = 0;
        $nombre_producto = '';
        
        // CORRECCIÓN PREVIA: Se usa 1 como valor de respaldo (asumiendo que ID 1 existe)
        $idUsuFK = isset($_SESSION['idUsu']) ? (int)$_SESSION['idUsu'] : 1; 
        
        $nombre_usuario = 'Usuario ID: ' . $idUsuFK; 
        
        try {
            // 1. Obtener Stock Actual y Nombre
            $sql_stock = "SELECT stoAct, desPro FROM productos WHERE idPro = ?";
            if ($stmt_stock = $conexion->prepare($sql_stock)) {
                $stmt_stock->bind_param("i", $idPro);
                $stmt_stock->execute();
                $res_stock = $stmt_stock->get_result();
                
                if ($fila = $res_stock->fetch_assoc()) {
                    $stock_anterior = $fila['stoAct'];
                    $nombre_producto = $fila['desPro'];
                } else {
                    throw new Exception("Error: Producto no encontrado.");
                }
                $stmt_stock->close();
            } else {
                throw new Exception("Error al preparar la consulta de stock: " . $conexion->error);
            }
            
            // 2. Calcular nuevo stock
            $stock_nuevo = $stock_anterior + $cantidad_ingresar;
            
            // 3. Registrar Movimiento (Tipo 1: Ingreso)
            $razIngre = "Ingreso de stock. Antes: {$stock_anterior} | Después: {$stock_nuevo}";
            
            // CORRECCIÓN CRÍTICA: Se cambia razIngre por razEgre en la consulta SQL
            $sql_mov = "INSERT INTO movimientos (idProFK, idUsuFK, tipMo, fecMov, cantSto, razEgre) VALUES (?, ?, ?, ?, ?, ?)";
            
            if ($stmt_mov = $conexion->prepare($sql_mov)) {
                $tipo_movimiento = 1; // 1 = Ingreso
                $stmt_mov->bind_param("iisiss", $idPro, $idUsuFK, $tipo_movimiento, $fecha_actual, $cantidad_ingresar, $razIngre);
                
                if (!$stmt_mov->execute()) {
                    throw new Exception("Error al registrar el movimiento: " . $stmt_mov->error);
                }
                $stmt_mov->close();
            } else {
                throw new Exception("Error al preparar la consulta de movimiento: " . $conexion->error);
            }
            
            // 4. Actualizar Stock en productos
            $sql_update = "UPDATE productos SET stoAct = ? WHERE idPro = ?";
            if ($stmt_update = $conexion->prepare($sql_update)) {
                $stmt_update->bind_param("ii", $stock_nuevo, $idPro);
                
                if (!$stmt_update->execute()) {
                    throw new Exception("Error al actualizar el stock: " . $stmt_update->error);
                }
                $stmt_update->close();
            } else {
                throw new Exception("Error al preparar la consulta de actualización: " . $conexion->error);
            }
            
            // 5. Commit y mensaje de éxito
            $conexion->commit();
            $mensaje_resultado = "Éxito: Se ingresaron {$cantidad_ingresar} unidades de '{$nombre_producto}'. Stock Actual: {$stock_nuevo}.";
            $tipo_mensaje = "exito";
            
        } catch (Exception $e) {
            $conexion->rollback();
            $mensaje_resultado = "Fallo en la transacción: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    }
}


// LÓGICA DE BÚSQUEDA Y SELECCIÓN

if (isset($_POST['seleccionar_producto'])) {
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
    
    if ($idPro !== false && $idPro > 0) {
        $sql_select = "SELECT p.idPro, p.desPro, p.stoAct, c.nomCat, p.nomPro 
                       FROM productos p 
                       JOIN categoria_producto c ON p.idCatFK = c.idCat 
                       WHERE p.idPro = ?";
        
        if ($stmt = $conexion->prepare($sql_select)) {
            $stmt->bind_param("i", $idPro);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows > 0) {
                    $producto_seleccionado = $resultado->fetch_assoc(); 
                    // Se asegura que ambos campos existan para evitar warnings en el formulario de ingreso
                    $producto_seleccionado['nomPro'] = $producto_seleccionado['nomPro'] ?? $producto_seleccionado['desPro'];
                    $producto_seleccionado['desPro'] = $producto_seleccionado['desPro']; 
                }
                $stmt->close();
            }
        }
    }
}


$consulta_busqueda = $_POST['consulta_busqueda'] ?? '';
$id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT) ?? 0;

// CORRECCIÓN ADVERTENCIA: Se añade p.nomPro al SELECT para que esté disponible en el HTML
$sql_filtro = "SELECT p.idPro, p.nomPro, p.desPro, p.stoAct, c.nomCat
                FROM productos p 
                JOIN categoria_producto c ON p.idCatFK = c.idCat 
                WHERE 1=1"; 

$parametros = [];
$tipos = '';

if (!empty($consulta_busqueda)) {
    $sql_filtro .= " AND (p.desPro LIKE ? OR p.idPro = ?)";
    $parametros[] = "%$consulta_busqueda%";
    $parametros[] = $consulta_busqueda;
    $tipos .= 'si'; 
}

if ($id_categoria > 0) {
    $sql_filtro .= " AND p.idCatFK = ?";
    $parametros[] = $id_categoria;
    $tipos .= 'i'; 
}

$sql_filtro .= " ORDER BY p.desPro LIMIT 50"; 

if ($stmt_filtro = $conexion->prepare($sql_filtro)) {
    if (!empty($parametros)) {
        // Enlazar parámetros dinámicamente
        $refs = [];
        foreach ($parametros as $key => $value) {
            $refs[$key] = &$parametros[$key];
        }
        call_user_func_array([$stmt_filtro, 'bind_param'], array_merge([$tipos], $refs));
    }
    
    $stmt_filtro->execute();
    $resultado = $stmt_filtro->get_result();
    
    while ($fila = $resultado->fetch_assoc()) {
        $productos_encontrados[] = $fila;
    }
    $stmt_filtro->close();
}

// Para que el formulario de ingreso no se muestre después de un éxito o error 
if ($tipo_mensaje === 'exito' || $tipo_mensaje === 'error') {
    $producto_seleccionado = null;
}