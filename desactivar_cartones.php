<?php
session_start();
if (!isset($_SESSION['superusuario'])) {
    header("Location: superusuario.php");
    exit();
}

file_put_contents('estado_cartones.txt', 'desactivado');
header("Location: principal.php?msg=desactivado");
exit();
?>
