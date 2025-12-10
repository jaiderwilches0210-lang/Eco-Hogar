<?php

include("./conexion/conexion.php");

// REGISTRAR PRODUCTO
if (isset($_POST["btnRegistrar"])) {

    if (
        empty($_POST["nomPro"]) || 
        empty($_POST["desPro"]) || 
        empty($_POST["preUni"]) || 
        empty($_POST["stoAct"]) || 
        empty($_POST["idCatFK"])
    ) {
        echo "<script>alert('Todos los campos son obligatorios');</script>";

    } else {

        $nombreProducto = $_POST["nomPro"];
        $descripcionProducto = $_POST["desPro"];
        $precioUnitario = $_POST["preUni"];
        $stockActivo = $_POST["stoAct"];
        $categoriaProducto = $_POST["idCatFK"];

        // Valores calculados / automáticos
        $precioVenta = $precioUnitario;  // puedes cambiarlo si deseas
        $fechaRegistro = date("Y-m-d");
        $umbralMinimo = 1; // Valor por defecto

        // NO enviamos idEstProEnumFK → MySQL asignará 1 (Activo)
        $sql = $conexion->prepare("
            INSERT INTO productos 
            (nomPro, desPro, preUni, preVen, FecReg, stoAct, umbMinSo, idCatFK)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $sql->bind_param(
            "ssddsidi",
            $nombreProducto,
            $descripcionProducto,
            $precioUnitario,
            $precioVenta,
            $fechaRegistro,
            $stockActivo,
            $umbralMinimo,
            $categoriaProducto
        );

        if ($sql->execute()) {
            echo "<script>alert('Producto creado exitosamente');</script>";
            header("Location: inicio.php");
            exit();
        } else {
            echo "<pre>";
            echo "ERROR de MySQL: " . $sql->error;
            echo "</pre>";
        }
    }
}

// BOTÓN CANCELAR
if (isset($_POST["btnCancelar"])) {
    header("Location: inicio.php");
    exit();
}

?>
