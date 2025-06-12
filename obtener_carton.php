<?php
// obtener_carton.php
header('Content-Type: application/json');

$numero = isset($_GET['numero']) ? (int)$_GET['numero'] : 1;
if ($numero < 1 || $numero > 100) {
    $numero = 1;
}

// Leer el archivo JSON de cartones
$archivo = 'cartones_fijos.json';
$contenido = file_get_contents($archivo);
$cartones = json_decode($contenido, true);

// Mostrar el cartón solicitado
if (isset($cartones[$numero])) {
    echo json_encode($cartones[$numero]);
} else {
    echo json_encode([]);
}
