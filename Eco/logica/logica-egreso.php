<?php

include "conexion/conexion.php"; 

// INICIALIZACIÓN DE VARIABLES GLOBALES
$producto_seleccionado = null; 
$productos_encontrados = [];
$categorias = []; 
$mensaje_resultado = '';
$tipo_mensaje = '';

// OBTENER CATEGORÍAS para el filtro
$sql_categorias = "SELECT idCat, nomCat FROM categoria_producto ORDER BY nomCat";
$res_categorias = $conexion->query($sql_categorias);

if ($res_categorias) {
    while ($cat = $res_categorias->fetch_assoc()) {
        $categorias[] = $cat;
    }
}

// LÓGICA DE EGRESO DE STOCK (Botón: enviar_egreso)
if (isset($_POST['enviar_egreso'])) {
    
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);
    // Campo del formulario de Egreso
    $cantidad_egresar = filter_input(INPUT_POST, 'cantidad_egresar', FILTER_VALIDATE_INT); 
    // Usamos fecha y hora. ¡Verifique que 'fecMov' sea DATETIME o TEXT en su DB!
    $fecha_actual = date('Y-m-d H:i:s'); 
    
    if ($idPro === false || $cantidad_egresar === false || $cantidad_egresar < 1) {
        $mensaje_resultado = "Error: Datos de egreso inválidos. La cantidad debe ser un número positivo.";
        $tipo_mensaje = "error";
    } else {
        // Iniciar transacción 
        $conexion->begin_transaction();
        
        $stock_anterior = 0;
        $nombre_producto = '';
        $idUsuFK = 102; //¡REEMPLAZAR con $_SESSION['idUsu'] AL MANEJAR INICIAR SESION!
        $nombre_usuario = 'Usuario ID: ' . $idUsuFK; 

        try {
            // Obtener el stock actual y nombre/descripción del producto
            $sql_stock_actual = "SELECT stoAct, desPro FROM productos WHERE idPro = ?"; 
            $stmt_stock = $conexion->prepare($sql_stock_actual);
            $stmt_stock->bind_param("i", $idPro);
            $stmt_stock->execute();
            $res_stock = $stmt_stock->get_result();
            
            if ($res_stock->num_rows === 0) {
                 throw new Exception("El producto seleccionado no existe.");
            }
            
            $producto = $res_stock->fetch_assoc();
            $stock_anterior = $producto['stoAct']; 
            $nombre_producto = $producto['desPro']; 
            $stmt_stock->close();
            
            // VALIDACIÓN CRÍTICA: Stock Suficiente
            if ($cantidad_egresar > $stock_anterior) {
                $mensaje_resultado = "Stock insuficiente. Solo hay $stock_anterior unidades disponibles para egresar.";
                $tipo_mensaje = "error";
                $conexion->rollback(); // Revertir por si acaso
                // Redirigir de vuelta al formulario para mostrar el error
                header("Location: registrarEgreso.php?msg=" . urlencode($mensaje_resultado) . "&tipo=" . urlencode($tipo_mensaje) . "&id_producto=" . $idPro);
                exit();
            }
            
            // Lógica para obtener el nombre del usuario (opcional, para la razón)
            $sql_usuario = "SELECT nomUsu FROM usuarios WHERE idUsu = ?";
            if ($stmt_user = $conexion->prepare($sql_usuario)) {
                $stmt_user->bind_param("i", $idUsuFK);
                $stmt_user->execute();
                $res_user = $stmt_user->get_result();
                if ($user_data = $res_user->fetch_assoc()) {
                    $nombre_usuario = $user_data['nomUsu'];
                }
                $stmt_user->close();
            }
            
            // Calcular el nuevo stock (RESTAMOS)
            $nuevo_stock = $stock_anterior - $cantidad_egresar; 
            
            // C. Actualizar el stock en la tabla de productos
            $sql_update = "UPDATE productos SET stoAct = ? WHERE idPro = ?";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bind_param("ii", $nuevo_stock, $idPro);
            
            if (!$stmt_update->execute()) {
                throw new Exception("Fallo al actualizar el stock: " . $stmt_update->error);
            }
            $stmt_update->close();

            //  Registrar el movimiento de EGRESO/SALIDA
            $tipo_movimiento = 2; // 2 = EGRESO / SALIDA 
            
            // Generar la descripción detallada
            $razon_egreso = "Movimiento por EGRESO/SALIDA. Producto: " . htmlspecialchars($nombre_producto) . 
                            ". Operador: " . htmlspecialchars($nombre_usuario) . 
                            ". Stock Anterior: " . $stock_anterior . 
                            ". Stock Actual: " . $nuevo_stock . 
                            ". Unidades egresadas: " . $cantidad_egresar . ".";
            
            
        
            $sql_movimiento = "INSERT INTO movimientos (idUsuFK, idProFK, tipMo, cantSto, fecMov, razEgre) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_mov = $conexion->prepare($sql_movimiento);
            
            // iiisis = (idUsuFK:i, idProFK:i, tipMo:i, cantSto:i, fecMov:s, razEgre:s)
            $stmt_mov->bind_param("iiisis", $idUsuFK, $idPro, $tipo_movimiento, $cantidad_egresar, $fecha_actual, $razon_egreso);

            if (!$stmt_mov->execute()) {
                // Si falla, se lanza una excepción con el error de MySQL
                $error_mysql = $stmt_mov->error;
                throw new Exception("Fallo al registrar el movimiento. ERROR DB: " . $error_mysql);
            }
            $stmt_mov->close();
            $conexion->commit();
            
            // Mensaje de éxito
           $mensaje_resultado = "Stock del producto " . htmlspecialchars($nombre_producto) . " actualizado correctamente." . 
                     "<br> Nuevo Stock: " . $nuevo_stock . " unidades." . 
                     "<br><b>El egreso ha sido registrado como salida.</b>";
            $tipo_mensaje = "success";

            // Redirigir para limpiar el POST y mostrar el mensaje
            header("Location: registrarEgreso.php?msg=" . urlencode($mensaje_resultado) . "&tipo=" . urlencode($tipo_mensaje));
            exit();
            
        } catch (Exception $e) {
            // Revertir los cambios (Esto revierte el UPDATE del stock si no ha hecho commit)
            $conexion->rollback();
            $mensaje_resultado = "Error en la transacción: " . $e->getMessage();
            $tipo_mensaje = "error";
        }
    }
}

// Si viene de una redirección con error y ID, re-seleccionar el producto
if (isset($_POST['seleccionar_producto']) || (isset($_GET['id_producto']) && $producto_seleccionado === null)) {
    
    // Priorizamos el POST, si no existe, usamos el GET (de la redirección por error de stock)
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'id_producto', FILTER_VALIDATE_INT);

    if ($idPro !== false) {
        $sql = "SELECT p.idPro, p.desPro, p.desPro AS nomPro, p.preUni, p.stoAct, c.nomCat
                FROM productos p 
                JOIN categoria_producto c ON p.idCatFK = c.idCat 
                WHERE p.idPro = ?";
        
        $stmt = $conexion->prepare($sql);
        
        if ($stmt && $stmt->bind_param("i", $idPro) && $stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $producto_seleccionado = $resultado->fetch_assoc(); 
            }
            $stmt->close();
        }
    }
}


// Se mantiene la misma lógica de búsqueda que en Ingreso
$consulta_busqueda = $_POST['consulta_busqueda'] ?? '';
$id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT) ?? 0;

$sql_filtro = "SELECT p.idPro, p.desPro, p.stoAct, c.nomCat
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
        // Para pasar un array de parámetros a bind_param
        $stmt_filtro->bind_param($tipos, ...$parametros);
    }
    
    if ($stmt_filtro->execute()) {
        $res_productos = $stmt_filtro->get_result();
        while ($prod = $res_productos->fetch_assoc()) {
            $prod['nomPro'] = $prod['desPro']; 
            $productos_encontrados[] = $prod;
        }
    }
    $stmt_filtro->close();
}


// CERRAR CONEXIÓN 
if (isset($conexion) && $conexion->ping()) {
    $conexion->close();
}
?>