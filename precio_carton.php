<?php
session_start();
if (!isset($_SESSION['superusuario'])) {
    header("Location: superusuario.php");
    exit();
}

include("header.php");

$mensaje = "";

// Ruta del archivo plano
$jsonFile = 'precio_carton.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoPrecio = floatval($_POST['precio']);
    if ($nuevoPrecio > 0) {
        $data = ['precio' => $nuevoPrecio];
        if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT))) {
            $mensaje = "Precio actualizado correctamente.";
        } else {
            $mensaje = "Error al guardar el precio.";
        }
    } else {
        $mensaje = "Por favor ingresa un precio válido.";
    }
}

// Obtener el precio actual desde el JSON
$precioActual = 40; // Por defecto
if (file_exists($jsonFile)) {
    $contenido = file_get_contents($jsonFile);
    $datos = json_decode($contenido, true);
    if (isset($datos['precio'])) {
        $precioActual = floatval($datos['precio']);
    }
}
?>

<style>
    .container {
        background: white;
        padding: 25px 30px;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-radius: 10px;
        margin: 40px auto;
        text-align: center;
        font-family: Arial, sans-serif;
    }
    h1 {
        color: #0077cc;
        margin-bottom: 20px;
    }
    input[type="number"] {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #0077cc;
        border-radius: 6px;
        box-sizing: border-box;
        margin-bottom: 20px;
    }
    button.guardar {
        background: #0077cc;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        margin-bottom: 15px;
        transition: background-color 0.3s ease;
    }
    button.guardar:hover {
        background: #005fa3;
    }
    button.volver {
        background: #444;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    button.volver:hover {
        background: #222;
    }
    .mensaje {
        margin-top: 15px;
        font-weight: bold;
        color: green;
    }
    @media (max-width: 480px) {
        .container {
            padding: 20px;
            margin: 20px 10px;
        }
        h1 {
            font-size: 22px;
        }
        button.guardar, button.volver, input[type="number"] {
            font-size: 14px;
        }
    }
</style>

<div class="container">
    <h1>Precio del Cartón</h1>

    <?php if ($mensaje): ?>
        <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input 
            type="number" 
            name="precio" 
            step="0.01" 
            min="1" 
            value="<?= htmlspecialchars($precioActual) ?>" 
            required 
            placeholder="Ingrese el precio del cartón en BS"
        />
        <button type="submit" class="guardar">Guardar Precio</button>
    </form>

    <button class="volver" onclick="window.location.href='menu_superusuario.php'">🔙 Volver al Menú</button>
</div>

</body>
</html>
