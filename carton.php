<?php
include('conexion.php');

// Verificar si el tablero está habilitado
$mostrar_tablero = true;
$hoy = date('Y-m-d');

if (file_exists("cartones_ejecutados.json")) {
    $estado = json_decode(file_get_contents("cartones_ejecutados.json"), true);
    if ($estado["fecha"] === $hoy && $estado["estado"] === "desactivado") {
        $mostrar_tablero = false;
    }
}

// Obtener los cartones comprados solo para hoy
$cartonesOcupados = [];
$consulta = $conn->query("SELECT cartones FROM venta WHERE fecha = '$hoy'");

while ($fila = $consulta->fetch_assoc()) {
    $comprados = json_decode($fila['cartones'], true);
    if (is_array($comprados)) {
        foreach ($comprados as $c) {
            $cartonesOcupados[] = (int)$c;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="carton.css" />
    <link rel="stylesheet" href="index.css" />
    <title>Bingo Gamer - Generar Cartón</title>
</head>
<body>

<header>
    <a href="index.php">
        <img src="imagenes/bingo gamer.jpg" alt="Logo Bingo" class="logo-img" />
    </a>
</header>

<div class="container">
    <h1 class="title">Generar Cartón de Bingo</h1>

    <?php if ($mostrar_tablero): ?>
        <div class="numbers">
            <?php
                for ($i = 1; $i <= 100; $i++) {
                    if (!in_array($i, $cartonesOcupados)) {
                        echo "<button type='button' class='number-btn' onclick='generarCarton($i)'>$i</button>";
                    }
                }
            ?>
        </div>
    <?php else: ?>
        <div style="text-align:center; padding: 50px;">
            <h2>🛑 El tablero está desactivado</h2>
            <p>El administrador ha desactivado temporalmente el tablero. Intenta más tarde.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para mostrar el Cartón de Bingo -->
<div id="cartonModal" class="carton-modal">
    <div class="carton-modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2 id="cartonTitulo">Número del Cartón Seleccionado: </h2>
        <div id="carton" class="carton"></div>
        <div class="modal-actions">
            <button class="modal-btn" onclick="cerrarModal()">Salir</button>
            <button class="modal-btn" onclick="seleccionarCarton()">Seleccionar</button>
        </div>
    </div>
</div>

<!-- Label de Pago -->
<div id="labelPago" class="label-pago" onclick="irAPagar()"></div>

<script src="carton.js"></script>
</body>
</html>








