<?php
include("conexion.php");

if (isset($_POST['telefono'])) {
    $telefono = trim($_POST['telefono']);

    // Evitar inyección básica
    $telefono = $conn->real_escape_string($telefono);

    $consulta = "SELECT 1 FROM venta WHERE telefono = '$telefono' LIMIT 1";
    $resultado = $conn->query($consulta);

    if ($resultado && $resultado->num_rows > 0) {
        echo "existe";
    } else {
        echo "no";
    }
}
?>
