<?php
// Obtener número de cartón desde la URL
$numero = isset($_GET['numero']) ? (int) $_GET['numero'] : 0;

$archivo = __DIR__ . "/cartones/carton_$numero.json";

// Verifica si el archivo existe
if (!file_exists($archivo)) {
    echo "<h2>❌ El cartón número $numero no existe.</h2>";
    exit;
}

// Cargar el contenido del JSON
$contenido = file_get_contents($archivo);
$carton = json_decode($contenido, true);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartón #<?php echo $numero; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e6f0ff;
            text-align: center;
            padding: 30px;
        }
        h1 {
            color: #004080;
        }
        table {
            margin: 0 auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            border: 2px solid #004080;
            padding: 20px;
            width: 60px;
            height: 60px;
            font-size: 20px;
        }
        td {
            background-color: #f9fbff;
        }
        .x {
            background-color: #c6e0ff;
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>

<h1>Cartón N° <?php echo $numero; ?></h1>

<table>
    <tr>
        <th>B</th><th>I</th><th>N</th><th>G</th><th>O</th>
    </tr>
    <?php foreach ($carton as $fila): ?>
        <tr>
            <?php foreach ($fila as $celda): ?>
                <td class="<?php echo $celda === "X" ? "x" : ""; ?>">
                    <?php echo $celda; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
