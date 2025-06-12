<?php
// procesar_pago.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bingogamer";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$correo = $_POST['correo'] ?? '';
$digitos = $_POST['digitos'] ?? '';

// Procesar imagen
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $nombreArchivo = $_FILES['imagen']['name'];
    $rutaTemporal = $_FILES['imagen']['tmp_name'];
    $carpetaDestino = "imagenes_subidas/";

    // Crear carpeta si no existe
    if (!file_exists($carpetaDestino)) {
        mkdir($carpetaDestino, 0777, true);
    }

    $rutaDestino = $carpetaDestino . basename($nombreArchivo);

    if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
        // Insertar datos en la base de datos
        $sql = "INSERT INTO venta (nombre, telefono, correo, digitos, imagen) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("sssss", $nombre, $telefono, $correo, $digitos, $nombreArchivo);

        if ($stmt->execute()) {
            echo "<script>alert('En unos minutos nos contactaremos con usted para confirmar el pago'); window.location.href='index.php';</script>";
        } else {
            echo "Error al guardar los datos: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al subir la imagen.";
    }
} else {
    echo "No se ha subido ninguna imagen o ocurrió un error.";
}

$conn->close();
?>

