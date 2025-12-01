<?php

session_start();

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

if (isset($_POST['enviar_egreso'])) {
    
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
    $cantidad_egresar = filter_input(INPUT_POST, 'cantidad_egresar', FILTER_VALIDATE_INT); 
    $razon_egreso = trim($_POST['razon_egreso']);
    $fecha_actual = date('Y-m-d H:i:s'); 
    
    // Asignar el ID de usuario desde la sesión o usar 1 como fallback (generalmente el Admin principal)
    $idUsuFK = isset($_SESSION['idUsu']) ? (int)$_SESSION['idUsu'] : 1; 
    
    // --- VERIFICACIÓN CLAVE DE SEGURIDAD PARA EL ERROR DE FOREIGN KEY ---
    if ($idUsuFK < 1) { 
        $mensaje_resultado = "Error de Sesión: Debe iniciar sesión con un usuario válido para registrar movimientos.";
        $tipo_mensaje = "error";
        // Finaliza la ejecución para evitar el intento de transacción
    } 
    // --------------------------------------------------------------------
    
    else if ($idPro === false || $cantidad_egresar === false || $cantidad_egresar < 1) {
        $mensaje_resultado = "Error: Datos de egreso inválidos. La cantidad debe ser un número positivo.";
        $tipo_mensaje = "error";
    } else if (empty($razon_egreso)) {
         $mensaje_resultado = "Error: La razón de egreso/venta es obligatoria.";
         $tipo_mensaje = "error";
    } else {
        $conexion->begin_transaction();
        
        $stock_anterior = 0;
        $nombre_producto = '';
        
        $nombre_usuario = 'Usuario ID: ' . $idUsuFK; 

        try {
            // 1. Obtener Stock Actual, Nombre del Producto y verificar existencia
            $sql_check = "SELECT nomPro, stoAct FROM productos WHERE idPro = ? FOR UPDATE";
            if ($stmt_check = $conexion->prepare($sql_check)) {
                $stmt_check->bind_param("i", $idPro);
                $stmt_check->execute();
                $resultado_check = $stmt_check->get_result();
                
                if ($resultado_check->num_rows === 0) {
                    throw new Exception("El producto seleccionado ya no existe.");
                }
                
                $data = $resultado_check->fetch_assoc();
                $stock_anterior = $data['stoAct'];
                $nombre_producto = $data['nomPro'];
                $stmt_check->close();

                if ($cantidad_egresar > $stock_anterior) {
                    throw new Exception("Error de stock: La cantidad a egresar ({$cantidad_egresar}) supera el stock actual ({$stock_anterior}) para el producto {$nombre_producto}.");
                }
            } else {
                throw new Exception("Error al preparar la verificación de stock: " . $conexion->error);
            }
            
            // 2. Actualizar el stock
            $nuevo_stock = $stock_anterior - $cantidad_egresar;
            $sql_update = "UPDATE productos SET stoAct = ? WHERE idPro = ?";
            if ($stmt_update = $conexion->prepare($sql_update)) {
                $stmt_update->bind_param("ii", $nuevo_stock, $idPro);
                if (!$stmt_update->execute()) {
                    throw new Exception("Error al actualizar el stock: " . $stmt_update->error);
                }
                $stmt_update->close();
            } else {
                throw new Exception("Error al preparar la actualización de stock: " . $conexion->error);
            }

            // 3. Registrar el movimiento (Tipo 2 = Egreso)
            $tipo_movimiento = 2; 
            $sql_movimiento = "INSERT INTO movimientos (idProFK, idUsuFK, tipMo, cantSto, fecMov, razEgre) 
                               VALUES (?, ?, ?, ?, ?, ?)";
            
            if ($stmt_mov = $conexion->prepare($sql_movimiento)) {
                $stmt_mov->bind_param("iiisis", 
                    $idPro, 
                    $idUsuFK, // Usando el ID de usuario ya validado
                    $tipo_movimiento, 
                    $cantidad_egresar, 
                    $fecha_actual, 
                    $razon_egreso
                );
                
                if (!$stmt_mov->execute()) {
                    // Esta línea manejaría el error de Foreign Key si $idUsuFK aún es inválido (y muestra el error de MySQL)
                    throw new Exception("Error al registrar el movimiento: " . $stmt_mov->error); 
                }
                $stmt_mov->close();
            } else {
                throw new Exception("Error al preparar el registro de movimiento: " . $conexion->error);
            }

            // Si todo fue bien
            $conexion->commit();
            $mensaje_resultado = "Éxito: Se egresaron **{$cantidad_egresar}** unidades de **{$nombre_producto}**. Stock actual: **{$nuevo_stock}**.";
            $tipo_mensaje = "success";
            
            // Limpiar la variable para volver al formulario de búsqueda
            $_POST['seleccionar_producto'] = false;

        } catch (Exception $e) {
            $conexion->rollback();
            $mensaje_resultado = "Error en la transacción: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    }
}

// Lógica para SELECCIONAR un producto
if (isset($_POST['seleccionar_producto'])) {
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
    if ($idPro) {
        $sql = "SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct as stoAct, c.nomCat 
                FROM productos p 
                JOIN categoria_producto c ON p.idCatFK = c.idCat 
                WHERE p.idPro = ?";

        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("i", $idPro);
            
            if (is_int($idPro) && $stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows > 0) {
                    $producto_seleccionado = $resultado->fetch_assoc(); 
                }
                $stmt->close();
            }
        }
    }
}


// Lógica para FILTRAR/BUSCAR productos
$consulta_busqueda = $_POST['consulta_busqueda'] ?? '';
$id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT) ?? 0;

$sql_filtro = "SELECT p.idPro, p.nomPro, p.desPro, p.stoAct, c.nomCat
                FROM productos p 
                JOIN categoria_producto c ON p.idCatFK = c.idCat 
                WHERE 1=1"; 

$parametros = [];
$tipos = '';

if (!empty($consulta_busqueda)) {
    // Buscar por Nombre, Descripción o ID
    $sql_filtro .= " AND (p.nomPro LIKE ? OR p.desPro LIKE ? OR p.idPro = ?)";
    $parametros[] = "%$consulta_busqueda%";
    $parametros[] = "%$consulta_busqueda%";
    $parametros[] = $consulta_busqueda;
    $tipos .= 'ssi'; 
}

if ($id_categoria > 0) {
    $sql_filtro .= " AND p.idCatFK = ?";
    $parametros[] = $id_categoria;
    $tipos .= 'i'; 
}

$sql_filtro .= " ORDER BY p.nomPro LIMIT 50"; 

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
    $resultado_filtro = $stmt_filtro->get_result();
    while ($fila = $resultado_filtro->fetch_assoc()) {
        $productos_encontrados[] = $fila;
    }
    $stmt_filtro->close();
}

// Si se seleccionó un producto pero la selección falló (p.ej., producto no existe)
if (isset($_POST['seleccionar_producto']) && $producto_seleccionado === null) {
    $mensaje_resultado = "Error: El producto seleccionado no pudo ser encontrado.";
    $tipo_mensaje = "error";
    // Forzar a mostrar la lista de búsqueda
    $_POST['seleccionar_producto'] = false;
}
?>