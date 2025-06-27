<?php
$numero = $_GET['numero'];
$cartones = json_decode(file_get_contents('cartones_fijos.json'), true);

if (isset($cartones[$numero])) {
    echo json_encode($cartones[$numero]);
} else {
    echo json_encode(["Cartón no encontrado"]);
}
?>
