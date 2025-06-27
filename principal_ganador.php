<?php
header("Content-Type: text/html; charset=utf-8");
date_default_timezone_set('America/Santiago');
include("conexion.php");

$fechaSeleccionada = $_GET['fecha'] ?? '';
$cartonBuscado = $_GET['carton'] ?? '';
$modoMarcado = $_GET['modo'] ?? '';

$usuarios = [];
$mensaje = '';
$numerosCarton = [];

if ($fechaSeleccionada && $cartonBuscado !== '') {
    $fechaSegura = mysqli_real_escape_string($conn, $fechaSeleccionada);
    $cartonBuscado = trim($cartonBuscado);
    $cartonBuscadoInt = intval($cartonBuscado);

    $sql = "SELECT * FROM venta WHERE fecha = '$fechaSegura'";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $json = file_get_contents("cartones_fijos.json");
        $cartones = json_decode($json, true);

        $cartonClave = (string)$cartonBuscado;

        if (!isset($cartones[$cartonClave])) {
            $mensaje = "El cartón $cartonBuscado no existe en cartones_fijos.json.";
        } else {
            $numerosCarton = $cartones[$cartonClave];

            while ($fila = mysqli_fetch_assoc($resultado)) {
                $cartonesComprados = json_decode($fila['cartones'], true);
                if (is_array($cartonesComprados) && in_array($cartonBuscadoInt, $cartonesComprados)) {
                    $usuarios[] = $fila;
                }
            }

            if (empty($usuarios)) {
                $mensaje = "No se encontraron coincidencias con ese cartón en esa fecha.";
            }
        }
    } else {
        $mensaje = "No hay registros para esa fecha.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ganador por Cartón</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f9ff;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 700px;
            width: 100%;
            background: white;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { color: #0077cc; margin-bottom: 25px; }
        form.formulario {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        form.formulario label {
            font-weight: bold;
            margin-right: 5px;
            align-self: center;
        }
        form.formulario input[type="date"],
        form.formulario input[type="number"],
        form.formulario select {
            padding: 8px 10px;
            border: 1px solid #0077cc;
            border-radius: 5px;
            width: 160px;
        }
        form.formulario button {
            background: #0077cc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        form.formulario button:hover { background: #005fa3; }
        .resultado { margin-top: 10px; color: #cc0000; font-weight: bold; }
        .ganador {
            margin: 15px 0; padding: 15px 20px; border: 1px solid #0077cc;
            border-radius: 8px; background: #e1f0ff; text-align: left;
            max-width: 400px; margin-left: auto; margin-right: auto;
        }
        .ganador p { margin: 6px 0; font-size: 16px; color: #004a7c; }
        .ganador .botones {
            display: flex; flex-direction: column; gap: 10px; margin-top: 15px;
        }
        .ganador .botones a button,
        .ganador .botones button {
            background: #25D366; color: white; border: none; padding: 10px 15px;
            border-radius: 5px; cursor: pointer; font-weight: bold;
            font-size: 16px; display: flex; align-items: center;
            justify-content: center; gap: 8px; width: 100%;
        }
        .carton {
            display: grid; grid-template-columns: repeat(5, 50px); gap: 5px;
            margin: 25px auto 10px auto; width: max-content;
        }
        .numero {
            width: 50px; height: 50px; background: #e1f0ff;
            border: 1px solid #0077cc; display: flex; align-items: center;
            justify-content: center; font-weight: bold; font-size: 18px;
            color: #004a7c; cursor: pointer; user-select: none;
        }
        .numero.marcado {
            background: #0077cc; color: white; border: 2px solid #004a7c;
        }
        .centro {
            background: #0077cc; color: white; font-size: 20px; cursor: default;
        }
        .bingotitle {
            display: grid; grid-template-columns: repeat(5, 50px); gap: 5px;
            justify-content: center; margin: 0 auto 15px auto;
            font-weight: bold; font-size: 22px; color: #0077cc; letter-spacing: 5px;
        }
        .btn-group {
            display: flex; gap: 15px; justify-content: center;
            margin-top: 25px; flex-wrap: wrap;
        }
        .btn-group a {
            background: #444; color: white; padding: 12px 25px;
            font-size: 16px; border-radius: 6px; text-decoration: none;
            font-weight: bold; cursor: pointer;
        }
        .btn-group a:hover { background: #222; }
        @media (max-width: 500px) {
            .carton, .bingotitle { grid-template-columns: repeat(5, 40px); }
            .numero, .centro { width: 40px; height: 40px; font-size: 16px; }
            .bingotitle { font-size: 18px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Consultar Ganador por Cartón</h1>
    <form class="formulario" method="get" autocomplete="off">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fechaSeleccionada) ?>" required>

        <label for="carton">N° de cartón:</label>
        <input type="number" id="carton" name="carton" min="1" value="<?= htmlspecialchars($cartonBuscado) ?>" required>

        <label for="modo">Modo marcado:</label>
        <select id="modo" name="modo">
            <option value="" <?= $modoMarcado === '' ? 'selected' : '' ?>>Ninguno</option>
            <option value="cuatro_esquinas" <?= $modoMarcado === 'cuatro_esquinas' ? 'selected' : '' ?>>4 Esquinas</option>
            <option value="carton_lleno" <?= $modoMarcado === 'carton_lleno' ? 'selected' : '' ?>>Cartón Lleno</option>
            <option value="letra_x" <?= $modoMarcado === 'letra_x' ? 'selected' : '' ?>>Letra X</option>
            <option value="letra_l" <?= $modoMarcado === 'letra_l' ? 'selected' : '' ?>>Letra L</option>
            <option value="letra_t" <?= $modoMarcado === 'letra_t' ? 'selected' : '' ?>>Letra T</option>
            <option value="cruz" <?= $modoMarcado === 'cruz' ? 'selected' : '' ?>>Cruz</option>
        </select>

        <button type="submit">Buscar</button>
    </form>

<?php if (!empty($usuarios)): ?>
    <div>
        <h3>Ganador(es) con el cartón #<?= htmlspecialchars($cartonBuscado) ?>:</h3>
        <?php foreach ($usuarios as $u): ?>
            <?php
                $mensajeWhatsapp = "🎉 Ganador del Bingo 🎉%0A";
                $mensajeWhatsapp .= "🧑 Nombre: " . urlencode($u['nombre']) . "%0A";
                $mensajeWhatsapp .= "📞 Teléfono: " . urlencode($u['telefono']) . "%0A";
                $mensajeWhatsapp .= "📧 Correo: " . urlencode($u['correo']) . "%0A";
                $mensajeWhatsapp .= "🎟️ Cartón N°: " . $cartonBuscado . "%0A";
                $mensajeWhatsapp .= "👉 Revisa tu cartón: " . urlencode("https://tusitio.com/carton.php?carton=" . $cartonBuscado);
                $telefonoLimpio = preg_replace('/[^0-9]/', '', $u['telefono']);
                $urlWhatsapp = "https://wa.me/58$telefonoLimpio?text=$mensajeWhatsapp";
            ?>
            <div class="ganador">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($u['nombre']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($u['telefono']) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($u['correo']) ?></p>
                <div class="botones">
                    <a href="<?= $urlWhatsapp ?>" target="_blank" rel="noopener noreferrer">
                        <button>📲 Compartir por WhatsApp</button>
                    </a>
                    <button onclick="copiarMensaje('<?= $mensajeWhatsapp ?>')">📋 Copiar mensaje</button>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="bingotitle"><div>B</div><div>I</div><div>N</div><div>G</div><div>O</div></div>

        <div class="carton">
        <?php
            $numerosCartonPlano = isset($numerosCarton[0]) && is_array($numerosCarton[0]) ? array_merge(...$numerosCarton) : $numerosCarton;
            $indicesMarcados = [];

            switch ($modoMarcado) {
                case 'cuatro_esquinas': $indicesMarcados = [0, 4, 20, 24]; break;
                case 'carton_lleno': $indicesMarcados = array_filter(range(0, 24), fn($i) => $i !== 12); break;
                case 'letra_x': $indicesMarcados = [0, 4, 6, 8, 16, 18, 20, 24]; break;
                case 'letra_l': $indicesMarcados = [0, 5, 10, 15, 20, 21, 22, 23, 24]; break;
                case 'letra_t': $indicesMarcados = [0,1,2,3,4,7,12,17,22]; break;
                case 'cruz': $indicesMarcados = [2, 7, 12, 17, 22, 10, 11, 13, 14]; break;
            }

            foreach ($numerosCartonPlano as $index => $numero) {
                $isCenter = ($index === 12);
                $clase = in_array($index, $indicesMarcados) ? ' marcado' : '';
                echo $isCenter
                    ? '<div class="numero centro">X</div>'
                    : '<div class="numero'.$clase.'">' . htmlspecialchars($numero) . '</div>';
            }
        ?>
        </div>
    </div>
<?php endif; ?>

<div class="btn-group">
    <a href="principal.php">🔐 Super Administrador</a>
    <a href="menu_superusuario.php">🏠 Volver al Menú</a>
</div>
</div>

<script>
document.querySelectorAll('.carton .numero:not(.centro)').forEach(function(celda) {
    celda.addEventListener('click', function() {
        celda.classList.toggle('marcado');
    });
});

function copiarMensaje(texto) {
    const textoDecodificado = decodeURIComponent(texto);
    navigator.clipboard.writeText(textoDecodificado);
}
</script>
</body>
</html>
