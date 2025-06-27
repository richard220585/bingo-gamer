<?php
// guardar_jugada.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    if ($data) {
        $jugadas = json_decode($data, true);
        if ($jugadas) {
            // Guardar en archivo
            file_put_contents('jugadas_marcadas.json', json_encode($jugadas, JSON_PRETTY_PRINT));
            http_response_code(200);
            echo json_encode(['status' => 'ok']);
            exit;
        }
    }
    http_response_code(400);
    echo json_encode(['status' => 'error', 'msg' => 'Datos inválidos']);
}
?>
