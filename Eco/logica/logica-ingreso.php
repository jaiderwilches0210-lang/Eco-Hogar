<?php

include "conexion/conexion.php";


$producto_seleccionado = null;
$productos_encontrados = [];
$categorias = [];
$mensaje_resultado = '';


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
        $idUsuFK = 102; 
        $nombre_usuario = 'Usuario ID: ' . $idUsuFK; 

        try {

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
            

            $nuevo_stock = $stock_anterior + $cantidad_ingresar; 
            

            $sql_update = "UPDATE productos SET stoAct = ? WHERE idPro = ?";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bind_param("ii", $nuevo_stock, $idPro);
            
            if (!$stmt_update->execute()) {
                throw new Exception("Fallo al actualizar el stock: " . $stmt_update->error);
            }
            $stmt_update->close();


            $tipo_movimiento = 1;
            

            $razon_egreso = "Movimiento por INGRESO. Producto: " . htmlspecialchars($nombre_producto) . 
                            ". Operador: " . htmlspecialchars($nombre_usuario) . 
                            ". Stock Anterior: " . $stock_anterior . 
                            ". Stock Actual: " . $nuevo_stock . 
                            ". Unidades ingresadas: " . $cantidad_ingresar . ".";
            
            $sql_movimiento = "INSERT INTO movimientos (idUsuFK, idProFK, tipMo, cantSto, fecMov, razEgre) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_mov = $conexion->prepare($sql_movimiento);
            

            $stmt_mov->bind_param("iiisis", $idUsuFK, $idPro, $tipo_movimiento, $cantidad_ingresar, $fecha_actual, $razon_egreso);

            if (!$stmt_mov->execute()) {
                throw new Exception("Fallo al registrar el movimiento: " . $stmt_mov->error);
            }
            $stmt_mov->close();
            

            $conexion->commit();
            
 
            $mensaje_resultado = " Stock del producto " . htmlspecialchars($nombre_producto) . " actualizado correctamente." . 
                                 "<br><h4>>Nuevo Stock:</h4>" . $nuevo_stock . " unidades." . 
                                 "<br><br>El movimiento ha sido registrado.<h5>";
            $tipo_mensaje = "success";


            header("Location: registrarIngreso.php?msg=" . urlencode($mensaje_resultado) . "&tipo=" . urlencode($tipo_mensaje));
            exit();
            
        } catch (Exception $e) {

            $conexion->rollback();
            // ⭐️ CORRECCIÓN: La conexión NO se cierra aquí.
            $mensaje_resultado = "Error en la transacción: " . $e->getMessage();
            $tipo_mensaje = "error";
            
        }
    }
}



if (isset($_POST['seleccionar_producto']) && isset($_POST['id_producto'])) {
    
    $idPro = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);

    if ($idPro !== false) {
        $sql = "SELECT p.idPro, p.desPro, p.preUni, p.stoAct, c.nomCat
                FROM productos p 
                JOIN categoria_producto c ON p.idCatFK = c.idCat 
                WHERE p.idPro = ?";
        
        $stmt = $conexion->prepare($sql);
        
        if ($stmt && $stmt->bind_param("i", $idPro) && $stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $producto_seleccionado = $resultado->fetch_assoc(); 
                $producto_seleccionado['nomPro'] = $producto_seleccionado['desPro'];
                // Agregamos el campo desPro también para que la vista lo use
                $producto_seleccionado['desPro'] = $producto_seleccionado['desPro']; 
            }
            $stmt->close();
        }
    }
}



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

if ($conexion && $conexion->ping()) {
    $conexion->close();
}
?>