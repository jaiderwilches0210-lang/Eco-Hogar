<?php

if (isset($_POST["cerrar-sesion"])) {
    header("Location: login.php");
    exit();
}

?>
