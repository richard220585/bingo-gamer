<?php
include("conexion.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $query = "DELETE FROM venta WHERE id = $id";
    mysqli_query($conn, $query);
}

header("Location: principal.php");
exit();
