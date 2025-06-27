<?php
// ✅ Función corregida: genera un cartón bingo clásico (5x5) con centro "X"
function generarCartonFijo() {
    $numeros = [
        'B' => range(1, 15),
        'I' => range(16, 30),
        'N' => range(31, 45),
        'G' => range(46, 60),
        'O' => range(61, 75),
    ];

    // Mezclar columnas
    foreach ($numeros as $letra => &$columna) {
        shuffle($columna);
    }

    $carton = [];

    // Construir el cartón fila por fila
    for ($fila = 0; $fila < 5; $fila++) {
        for ($col = 0; $col < 5; $col++) {
            if ($fila === 2 && $col === 2) {
                $carton[] = 'X'; // Centro libre
            } else {
                $letra = array_keys($numeros)[$col];
                $carton[] = array_pop($numeros[$letra]);
            }
        }
    }

    return $carton;
}

// ✅ Acciones por formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"];

    if ($accion === "desactivar") {
        $data = ["fecha" => date("Y-m-d"), "estado" => "desactivado"];
        file_put_contents("cartones_ejecutados.json", json_encode($data));
    }

    if ($accion === "desactivar_rango") {
        $desde = isset($_POST["desde"]) ? (int)$_POST["desde"] : 1;
        $hasta = isset($_POST["hasta"]) ? (int)$_POST["hasta"] : 1;

        $data = ["fecha" => date("Y-m-d")];

        // Si ya existe el archivo, cargar y agregar
        if (file_exists("cartones_ejecutados.json")) {
            $data = json_decode(file_get_contents("cartones_ejecutados.json"), true);
            if (!isset($data["rangos"])) {
                $data["rangos"] = [];
            }
        } else {
            $data["rangos"] = [];
        }

        // Agregar el nuevo rango
        $data["rangos"][] = ["desde" => $desde, "hasta" => $hasta];

        file_put_contents("cartones_ejecutados.json", json_encode($data));
    }

    if ($accion === "habilitar") {
        if (file_exists("cartones_ejecutados.json")) unlink("cartones_ejecutados.json");
    }

    header("Location: carton_habilitar.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Control del Tablero</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f2ff;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 15px;
        }

        input[type="number"] {
            padding: 10px;
            font-size: 16px;
            width: 100px;
            text-align: center;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .desactivar {
            background-color: #e74c3c;
        }

        .desactivar:hover {
            background-color: #c0392b;
        }

        .habilitar {
            background-color: #2ecc71;
        }

        .habilitar:hover {
            background-color: #27ae60;
        }

        .info {
            font-size: 18px;
            color: #444;
        }

        .volver {
            display: inline-block;
            margin-top: 30px;
            background-color: #2980b9;
            padding: 12px 24px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .volver:hover {
            background-color: #21618c;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">

    <!-- 1. Desactivar Tablero -->
    <div class="section">
        <h2>🛑 Desactivar Tablero</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="desactivar">
            <button class="desactivar" type="submit">Desactivar Todo</button>
        </form>
    </div>

    <!-- 2. Habilitar Tablero -->
    <div class="section">
        <h2>✅ Habilitar Tablero</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="habilitar">
            <button class="habilitar" type="submit">Habilitar Todo</button>
        </form>
    </div>

    <!-- 3. Desactivar Rango de Cartones -->
    <div class="section">
        <h2>🔶 Desactivar Rango de Cartones</h2>
        <form method="POST">
            <input type="hidden" name="accion" value="desactivar_rango">
            <input type="number" name="desde" min="1" placeholder="Desde" required>
            <input type="number" name="hasta" min="1" placeholder="Hasta" required>
            <button class="desactivar" type="submit">Desactivar Rango</button>
        </form>
    </div>

    <!-- Estado Actual -->
    <div class="section">
        <h2>📋 Estado Actual</h2>
        <div class="info">
            <?php
            if (file_exists("cartones_fijos.json")) {
                $cartones = json_decode(file_get_contents("cartones_fijos.json"), true);
                echo "Cartones disponibles: <strong>" . count($cartones) . "</strong>";
            } else {
                echo "No se han generado cartones aún.";
            }
            ?>
        </div>
    </div>

    <a href="menu_superusuario.php" class="volver">⬅ Volver al Menú</a>

</div>

</body>
</html>

