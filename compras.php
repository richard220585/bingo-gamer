<?php
// compras.php

// Leer el precio desde el archivo JSON
$precio = 40; // valor por defecto
$jsonPath = 'precio_carton.json';
if (file_exists($jsonPath)) {
    $contenido = file_get_contents($jsonPath);
    $datos = json_decode($contenido, true);
    if (isset($datos['precio'])) {
        $precio = floatval($datos['precio']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo Gamer - Compra</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <!-- Contenido principal -->
    <div class="content">
        <h1 class="step-title">Paso 1: Selecciona tu Cartón</h1>

        <p class="instruction">
            Ahora selecciona el número de cartón que deseas jugar (puedes elegir más de uno, recuerda que cada uno cuesta <?= $precio ?> Bolívares).
        </p>

        <div class="carton-selection">
            <div class="carton-info">
                <p class="carton-text">Valor del Cartón: <?= $precio ?> Bs</p>
                <p class="carton-text">Fecha de hoy: <strong id="fechaHoy"></strong></p>
                <a href="carton.php">
                    <button class="select-btn">Seleccionar Cartón</button>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <h2>Gracias por visitar Bingo Gamer</h2>
        <p>Para más información, contáctanos a: contacto@bingogamer.com</p>
    </footer>

    <script>
        // Mostrar la fecha de hoy
        const fechaHoy = new Date().toLocaleDateString();
        document.getElementById("fechaHoy").textContent = fechaHoy;
    </script>
</body>
</html>

