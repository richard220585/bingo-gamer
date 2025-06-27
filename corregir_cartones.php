<?php
// ✅ Corrige los cartones mal generados desde el 101 hasta el 300 en cartones_fijos.json

// Función que genera un cartón de bingo válido (5x5) con 'X' en el centro
function generarCartonFijo() {
    $carton = [];
    $columnas = [
        'B' => range(1, 15),
        'I' => range(16, 30),
        'N' => range(31, 45),
        'G' => range(46, 60),
        'O' => range(61, 75),
    ];

    foreach ($columnas as $letra => $numeros) {
        shuffle($numeros);
        $carton[$letra] = array_slice($numeros, 0, 5);
    }

    // Casilla central (N3) debe ser 'X'
    $carton['N'][2] = 'X';

    // Construir el cartón en formato lineal (25 valores)
    $carton_final = [];
    for ($fila = 0; $fila < 5; $fila++) {
        foreach (['B', 'I', 'N', 'G', 'O'] as $letra) {
            $carton_final[] = $carton[$letra][$fila];
        }
    }

    return $carton_final;
}

// Ruta al archivo de cartones
$archivo = "cartones_fijos.json";

// Validar existencia
if (!file_exists($archivo)) {
    die("❌ No se encontró el archivo cartones_fijos.json");
}

// Cargar y conservar los primeros 100
$cartones = json_decode(file_get_contents($archivo), true);
$cartones_validos = array_slice($cartones, 0, 100);

// Generar del 101 al 300
for ($i = count($cartones_validos); $i < 300; $i++) {
    $cartones_validos[] = generarCartonFijo();
}

// Guardar el archivo corregido
file_put_contents($archivo, json_encode($cartones_validos, JSON_PRETTY_PRINT));

// Mensaje de confirmación
echo "✅ Se corrigieron los cartones desde el 101 hasta el 300 exitosamente.";
?>
