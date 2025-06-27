<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoJuego = $_POST['tipoJuego'] ?? '';
    $cartonesSeleccionados = $_POST['cartonesSeleccionados'] ?? [];

    $data = [
        'tipoJuego' => $tipoJuego,
        'cartonesSeleccionados' => $cartonesSeleccionados
    ];

    file_put_contents('tipos_de_juego.json', json_encode($data, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'message' => 'Juego guardado correctamente.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Acceso no permitido.']);
}
?>
