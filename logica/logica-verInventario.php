<?php
include_once __DIR__ . '/../conexion/conexion.php';

$mensaje = "";
$tipo = "";

// -------------------- CAMBIAR ESTADO --------------------
if (isset($_POST['cambiar_estado']) && !empty($_POST['id_producto'])) {
    $id = intval($_POST['id_producto']);
    $nuevo_estado = intval($_POST['nuevo_estado']);

    $update = $conexion->prepare("UPDATE productos SET idEstProEnumFK = ? WHERE idPro = ?");
    $update->bind_param("ii", $nuevo_estado, $id);

    if ($update->execute()) {
        $mensaje = "Estado del producto actualizado correctamente.";
        $tipo = "exito";
    } else {
        $mensaje = "Error al actualizar estado: " . $conexion->error;
        $tipo = "error";
    }
}

// -------------------- PAGINACIÓN --------------------
$registrosPorPagina = 10;

$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$paginaActual = max(1, $paginaActual);

$offset = ($paginaActual - 1) * $registrosPorPagina;

// -------------------- FILTROS --------------------
$where = "WHERE 1=1";

if (!empty($_GET['buscar'])) {
    $buscar = "%" . $conexion->real_escape_string($_GET['buscar']) . "%";
    $where .= " AND (p.nomPro LIKE '$buscar' OR p.idPro LIKE '$buscar')";
}

if (!empty($_GET['categoria']) && $_GET['categoria'] != "0") {
    $categoria = intval($_GET['categoria']);
    $where .= " AND p.idCatFK = $categoria";
}

if (!empty($_GET['estado']) && $_GET['estado'] != "0") {
    $estado = intval($_GET['estado']);
    $where .= " AND p.idEstProEnumFK = $estado";
}

// -------------------- CONSULTA CON LIMIT --------------------
$sql = "
SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct,
       c.nomCat,
       ep.nomEst AS estado_nombre,
       p.idEstProEnumFK AS estado_id
FROM productos p
INNER JOIN categoria_producto c ON p.idCatFK = c.idCat
INNER JOIN estado_producto ep ON p.idEstProEnumFK = ep.idEst
$where
ORDER BY p.idPro ASC
LIMIT $registrosPorPagina OFFSET $offset
";

$resultado = $conexion->query($sql);
if (!$resultado) {
    die("Error SQL: " . $conexion->error);
}

// -------------------- OBTENER TOTAL DE REGISTROS --------------------
$sqlTotal = "
SELECT COUNT(*) AS total
FROM productos p
$where
";
$totalReg = $conexion->query($sqlTotal)->fetch_assoc()['total'];
$totalPaginas = ceil($totalReg / $registrosPorPagina);

// -------------------- LISTA DE CATEGORÍAS --------------------
$categorias = $conexion->query("SELECT * FROM categoria_producto");

// -------------------- RETORNO --------------------
return [
    "resultado"      => $resultado,
    "categorias"     => $categorias,
    "mensaje"        => $mensaje,
    "tipo"           => $tipo,
    "paginaActual"   => $paginaActual,
    "totalPaginas"   => $totalPaginas
];
