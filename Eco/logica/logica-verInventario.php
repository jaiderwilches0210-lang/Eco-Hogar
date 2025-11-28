<?php
    include("conexion/conexion.php");

 
$sql = $conexion->query(
    "SELECT p.idPro, c.nomCat, p.nomPro, p.desPro, p.preUni, p.stoAct
    FROM productos p
    INNER JOIN categoria_producto c
    
    ON p.idCatFK = c.idCat"
);


?>
