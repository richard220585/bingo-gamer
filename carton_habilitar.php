<?php
// Si se presiona un botón
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accion = $_POST["accion"];

    if ($accion === "desactivar") {
        $data = [
            "fecha" => date("Y-m-d"),
            "estado" => "desactivado"
        ];
        file_put_contents("cartones_ejecutados.json", json_encode($data));
    }

    if ($accion === "habilitar") {
        if (file_exists("cartones_ejecutados.json")) {
            unlink("cartones_ejecutados.json");
        }
    }

    header("Location: carton_habilitar.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Control de Tablero</title>
    <style>
        body {
            font-family: Arial;
            text-align: center;
            padding-top: 100px;
            background-color: #f0f8ff;
        }
        form {
            display: inline-block;
            margin: 0 20px;
        }
        button {
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            color: white;
        }
        .desactivar {
            background-color: #e74c3c;
        }
        .habilitar {
            background-color: #2ecc71;
        }
        .volver {
            margin-top: 50px;
            background-color: #3498db;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            color: white;
            display: inline-block;
        }
        .volver:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <h1>Control del Tablero</h1>

    <form method="POST">
        <input type="hidden" name="accion" value="desactivar">
        <button class="desactivar" type="submit">Desactivar Tablero</button>
    </form>

    <form method="POST">
        <input type="hidden" name="accion" value="habilitar">
        <button class="habilitar" type="submit">Habilitar Tablero</button>
    </form>

    <!-- Botón para volver al menú -->
    <div>
        <a class="volver" href="menu_superusuario.php">⬅ Volver al Menú</a>
    </div>

</body>
</html>
