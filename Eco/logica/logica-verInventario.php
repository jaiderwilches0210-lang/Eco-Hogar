<?php
    include("conexion/conexion.php");

 
        $sql = $conexion->query("
        SELECT p.idPro, p.nomPro, p.desPro, p.preUni, p.stoAct, c.nombreCat
        FROM productos p
        INNER JOIN categorias c ON p.catPro = c.idCat
        ");


?>
