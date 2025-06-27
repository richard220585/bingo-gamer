<?php
include("header.php");
$cartones_json = file_get_contents('cartones_fijos.json');
$cartones_data = json_decode($cartones_json, true);

$conexion = new mysqli("localhost", "root", "", "bingogamer");
$telefono = "";
$datos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["telefono"])) {
    $telefono = $conexion->real_escape_string($_POST["telefono"]);
    $hoy = date('Y-m-d');
    $consulta = "SELECT fecha, cartones FROM venta WHERE telefono = '$telefono' AND fecha = '$hoy' ORDER BY fecha DESC";
    $resultado = $conexion->query($consulta);

    while ($fila = $resultado->fetch_assoc()) {
        $fecha = $fila['fecha'];
        $raw_cartones = str_replace(['[', ']', ' '], '', $fila['cartones']);
        $cartones_array = explode(',', $raw_cartones);
        $cartones = array_filter(array_map('intval', $cartones_array), fn($v) => $v > 0);

        if (!isset($datos[$fecha])) {
            $datos[$fecha] = [];
        }
        $datos[$fecha] = array_unique(array_merge($datos[$fecha], $cartones));
        sort($datos[$fecha], SORT_NUMERIC);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartones del día</title>
    <link rel="stylesheet" href="carton.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 40px;
        }
        form {
            text-align: center;
            margin-bottom: 30px;
        }
        input[type="text"], input[type="submit"] {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        input[type="text"] {
            width: 250px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            margin-left: 10px;
            border: none;
            cursor: pointer;
        }

        .grupo-fecha {
            margin-bottom: 30px;
            padding: 20px;
            background: #ffffff;
            border-radius: 15px;
            border-left: 5px solid #007bff;
            cursor: pointer;
        }

        .grupo-fecha h2 {
            color: #007bff;
            margin: 0;
        }

        .carton-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .carton-item {
            background: #e9f3ff;
            border: 1px solid #007bff22;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            width: 120px;
        }

        .ver-boton {
            padding: 6px 10px;
            font-size: 14px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .ver-boton:hover {
            background-color: #218838;
        }

        #cartonModal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
        }

        #modalContent {
            background-color: white;
            margin: 1% auto;
            padding: 20px;
            border-radius: 12px;
            max-width: 98%;
            width: 95%;
            text-align: center;
            position: relative;
            padding-left: 140px;
        }

        #modalContent img {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 100px;
            height: auto;
            z-index: 10;
        }

        .close {
            color: red;
            float: right;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .modal-buttons button {
            padding: 10px 15px;
            font-size: 14px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-imprimir { background-color: #007bff; color: white; }
        .btn-pdf { background-color: #dc3545; color: white; }
        .btn-captura { background-color: #ffc107; color: black; }
        .btn-cerrar { background-color: #6c757d; color: white; }

        .celda {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 12px;
        }

        .celda.activa {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .carton-contenedor {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .carton-unit {
            zoom: 1;
            min-width: 180px;
        }

        @media (max-width: 600px) {
            .carton-unit {
                zoom: 0.7;
                min-width: 150px;
            }
            #modalContent {
                padding-left: 20px;
            }
            #modalContent img {
                width: 60px;
                top: 5px;
                left: 5px;
            }
        }
    </style>
</head>
<body>
    <h1>Ingrese su número telefónico según cómo hizo la compra</h1>
    <form method="post">
        <input type="text" name="telefono" placeholder="Ingresa tu número" value="<?= htmlspecialchars($telefono) ?>">
        <input type="submit" value="Buscar">
    </form>

    <?php if (!empty($datos)) : ?>
        <?php foreach ($datos as $fecha => $cartones) : ?>
            <div class="grupo-fecha">
                <h2>Fecha: <?= date('d-m-Y', strtotime($fecha)) ?></h2>
                <div class="carton-grid">
                    <?php foreach ($cartones as $carton) : ?>
                        <div class="carton-item">
                            <button class="ver-boton" onclick="mostrarCartones([<?= implode(',', $cartones) ?>]); event.stopPropagation();">Ver todos</button>
                            <?php break; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <p style="text-align:center; color:red;">No se encontraron cartones para hoy con ese número.</p>
    <?php endif; ?>

    <div id="cartonModal">
        <div id="modalContent">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <img src="imagenes/miportada.png" alt="Mi Portada">
            <div id="cartonContainer" class="carton-contenedor"></div>
            <div class="modal-buttons">
                <button class="btn-imprimir" onclick="window.print()">Imprimir</button>
                <button class="btn-pdf" onclick="guardarComoPDF()">Guardar PDF</button>
                <button class="btn-captura" onclick="capturarImagen()">Capturar</button>
                <button class="btn-cerrar" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        const cartonesData = <?= json_encode($cartones_data); ?>;

        function mostrarCartones(numeros) {
            let html = '';
            const letras = ['B', 'I', 'N', 'G', 'O'];
            numeros.forEach(numero => {
                const carton = cartonesData[numero];
                if (!carton) return;
                html += `<div class="carton-unit">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">Cartón ${numero}</div>
                            <table style="border-collapse: collapse; margin: auto; font-size: 22px;">
                                <tr>${letras.map(l => `<th style="border: 1px solid #000; padding: 6px; background: #007bff; color: white;">${l}</th>`).join('')}</tr>`;
                for (let i = 0; i < 5; i++) {
                    html += '<tr>';
                    for (let j = 0; j < 5; j++) {
                        const valor = carton[i][j];  // <-- Aquí accedemos a matriz 5x5
                        const clase = valor === 'X' ? 'celda activa' : 'celda';
                        html += `<td class="${clase}" onclick="toggleCelda(this)" style="width: 60px; height: 60px; font-size: 24px;">${valor}</td>`;
                    }
                    html += '</tr>';
                }
                html += '</table></div>';
            });
            document.getElementById('cartonContainer').innerHTML = html;
            document.getElementById('cartonModal').style.display = 'block';
        }

        function toggleCelda(td) {
            td.classList.toggle('activa');
        }

        function cerrarModal() {
            document.getElementById('cartonModal').style.display = 'none';
        }

        function guardarComoPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            html2canvas(document.getElementById('cartonContainer')).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                doc.addImage(imgData, 'PNG', 10, 10, 190, 0);
                doc.save('cartones.pdf');
            });
        }

        function capturarImagen() {
            html2canvas(document.getElementById('cartonContainer')).then(canvas => {
                const link = document.createElement('a');
                link.download = 'cartones.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        }

        window.onclick = function(event) {
            const modal = document.getElementById('cartonModal');
            if (event.target == modal) cerrarModal();
        }
    </script>
</body>
</html>

