<?php
// obtener_carton.php
header('Content-Type: application/json');

// Obtener el número solicitado (mínimo 1)
$numero = isset($_GET['numero']) ? (int)$_GET['numero'] : 1;
if ($numero < 1) {
    $numero = 1;
}

// Ruta absoluta al archivo JSON (ajusta si es necesario)
$archivo = __DIR__ . '/cartones_fijos.json';
if (!file_exists($archivo)) {
    echo json_encode(['error' => 'Archivo cartones_fijos.json no encontrado']);
    exit;
}

$contenido = file_get_contents($archivo);

// Quitar BOM si existe (caracteres invisibles UTF-8 BOM)
$contenido = preg_replace('/^\xEF\xBB\xBF/', '', $contenido);

$cartones = json_decode($contenido, true);

if ($cartones === null) {
    echo json_encode(['error' => 'Error al decodificar JSON', 'mensaje' => json_last_error_msg()]);
    exit;
}

// Convertir número a string para acceder a la clave (según cómo esté el JSON)
$clave = (string)$numero;

// Para debug: mostrar claves disponibles (comenta esta línea si no quieres verlas)
// error_log('Claves en JSON: ' . implode(', ', array_keys($cartones)));

if (isset($cartones[$clave])) {
    $carton = $cartones[$clave];

    // Validar que sea matriz 5x5
    if (is_array($carton) && count($carton) === 5) {
        foreach ($carton as $fila) {
            if (!is_array($fila) || count($fila) !== 5) {
                echo json_encode(['error' => 'Formato incorrecto en fila del cartón']);
                exit;
            }
        }
        // Todo OK, devolver el cartón
        echo json_encode($carton);
        exit;
    } else {
        echo json_encode(['error' => 'Cartón no tiene formato 5x5']);
        exit;
    }
} else {
    echo json_encode([
        'error' => 'Cartón no encontrado',
        'clave_buscada' => $clave,
        'claves_disponibles' => array_keys($cartones)
    ]);
}

