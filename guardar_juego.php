<?php
// guardar_juego.php

include 'conexion.php'; // Asegúrate que este archivo contiene tu conexión a MySQL

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $posiciones = $_POST['posiciones'] ?? '';

    if ($nombre && $posiciones) {
        // Insertar en la tabla tipo_juego
        $query = "INSERT INTO tipo_juego (nombre, posiciones) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nombre, $posiciones);

        if ($stmt->execute()) {
            echo "<script>alert('Juego guardado exitosamente'); window.location.href='crear_juego.php';</script>";
        } else {
            echo "Error al guardar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Faltan datos.";
    }

    $conn->close();
}
?>
