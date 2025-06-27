<?php
include('conexion.php');

// Verificar si el tablero está habilitado
$mostrar_tablero = true;
$hoy = date('Y-m-d');
$cartonesDesactivados = [];

if (file_exists("cartones_ejecutados.json")) {
    $estado = json_decode(file_get_contents("cartones_ejecutados.json"), true);

    if (isset($estado["fecha"]) && $estado["fecha"] === $hoy) {
        if (isset($estado["estado"]) && $estado["estado"] === "desactivado") {
            $mostrar_tablero = false;
        }

        if (isset($estado['rangos']) && is_array($estado['rangos'])) {
            foreach ($estado['rangos'] as $rango) {
                $desde = isset($rango['desde']) ? (int)$rango['desde'] : 0;
                $hasta = isset($rango['hasta']) ? (int)$rango['hasta'] : 0;
                for ($i = $desde; $i <= $hasta; $i++) {
                    $cartonesDesactivados[] = $i;
                }
            }
        }
    }
}

// Obtener cartones ocupados
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

// Leer el archivo JSON para saber cuántos cartones hay
$archivo_json = 'cartones_fijos.json';
$cartones = [];
if (file_exists($archivo_json)) {
    $contenido = file_get_contents($archivo_json);
    $cartones = json_decode($contenido, true);
}
$total_cartones = count($cartones);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bingo Gamer - Cartón</title>
    <link rel="stylesheet" href="carton.css" />
    <link rel="stylesheet" href="index.css" />
</head>
<body>

<?php include('header.php'); ?>

<div id="contenidoCompleto" class="container-main">
    <h1 class="title">Generar Cartón de Bingo</h1>

    <?php if ($mostrar_tablero): ?>
        <div class="numbers">
            <?php for ($i = 1; $i <= $total_cartones; $i++): ?>
                <?php if (!in_array($i, $cartonesOcupados) && !in_array($i, $cartonesDesactivados)): ?>
                    <button type="button" class="number-btn" onclick="generarCarton(<?= $i ?>)"><?= $i ?></button>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <div style="text-align:center; padding: 50px;">
            <h2>🛑 El tablero está desactivado</h2>
            <p>El administrador ha desactivado temporalmente el tablero. Intenta más tarde.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="cartonModal" class="carton-modal">
    <div class="carton-modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <div class="modal-header">
            <img src="imagenes/miportada.png" alt="Logo" class="logo-carton" />
            <h2 id="cartonTitulo" class="carton-title">Número del Cartón Seleccionado:</h2>
        </div>

        <div class="carton-container">
            <div class="carton-header">
                <div>B</div>
                <div>I</div>
                <div>N</div>
                <div>G</div>
                <div>O</div>
            </div>
            <div id="carton" class="carton"></div>
        </div>

        <div class="modal-actions">
            <button class="modal-btn" onclick="cerrarModal()">Salir</button>
            <button class="modal-btn" onclick="seleccionarCarton()">Seleccionar</button>
        </div>
    </div>
</div>

<div id="labelPago" class="label-pago" onclick="irAPagar()"></div>

<script src="carton.js"></script>
</body>
</html>
