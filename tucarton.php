<?php
$cartones_json = file_get_contents('cartones_fijos.json');
$cartones_data = json_decode($cartones_json, true);

$conexion = new mysqli("localhost", "root", "", "bingogamer");
$telefono = "";
$datos = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["telefono"])) {
    $telefono = $conexion->real_escape_string($_POST["telefono"]);
    $consulta = "SELECT fecha, cartones FROM venta WHERE telefono = '$telefono' ORDER BY fecha DESC";
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
    <title>Consulta tu cartón</title>
    <link rel="stylesheet" href="carton.css">
    <style>
        body { background-color: #f0f8ff; font-family: 'Segoe UI', sans-serif; padding: 20px; }
        h1 { text-align: center; color: #0056b3; margin-bottom: 40px; }
        form { text-align: center; margin-bottom: 30px; }
        input[type="text"] { padding: 10px; width: 250px; border-radius: 8px; border: 1px solid #ccc; }
        input[type="submit"] { padding: 10px 20px; border-radius: 8px; background-color: #007bff; color: white; border: none; margin-left: 10px; cursor: pointer; }
        .grupo-fecha { margin-bottom: 30px; padding: 20px; background: #ffffff; border-radius: 15px; border-left: 5px solid #007bff; cursor: pointer; }
        .grupo-fecha h2 { color: #007bff; margin: 0; }
        .carton-grid { display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-start; margin-top: 15px; }
        .carton-item { background: #e9f3ff; border: 1px solid #007bff22; border-radius: 10px; padding: 10px; width: 150px; text-align: center; }
        .carton-numero { font-weight: bold; color: #007bff; margin-bottom: 5px; }
        .ver-boton { padding: 6px 10px; font-size: 14px; border: none; background-color: #28a745; color: white; border-radius: 5px; cursor: pointer; }
        .ver-boton:hover { background-color: #218838; }
        #cartonModal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); }
        #modalContent { background-color: white; margin: 5% auto; padding: 20px; border-radius: 12px; width: 400px; text-align: center; }
        .close { color: red; float: right; font-size: 22px; font-weight: bold; cursor: pointer; }
        .modal-buttons { margin-top: 20px; display: flex; justify-content: space-around; flex-wrap: wrap; gap: 10px; }
        .modal-buttons button { padding: 10px 15px; font-size: 14px; border-radius: 5px; border: none; cursor: pointer; }
        .btn-imprimir { background-color: #007bff; color: white; }
        .btn-pdf { background-color: #dc3545; color: white; }
        .btn-captura { background-color: #ffc107; color: black; }
        .btn-cerrar { background-color: #6c757d; color: white; }
        .celda { border: 1px solid #000; padding: 10px; text-align: center; cursor: pointer; }
        .celda.activa { background-color: #007bff; color: white; font-weight: bold; }
        .carton-grid.hidden { display: none; }
    </style>
</head>
<body>
    <h1>Consulta tu cartón</h1>
    <form method="post">
        <input type="text" name="telefono" placeholder="Ingresa tu número" value="<?= htmlspecialchars($telefono) ?>">
        <input type="submit" value="Buscar">
    </form>

    <?php if (!empty($datos)) : ?>
        <?php foreach ($datos as $fecha => $cartones) : ?>
            <div class="grupo-fecha" onclick="toggleCartones('cartones-<?= md5($fecha) ?>')">
                <h2>Fecha: <?= date('d-m-Y', strtotime($fecha)) ?></h2>
                <div id="cartones-<?= md5($fecha) ?>" class="carton-grid hidden">
                    <?php foreach ($cartones as $carton) : ?>
                        <?php if ($carton >= 1 && $carton <= 100) : ?>
                            <div class="carton-item">
                                <div class="carton-numero">Cartón <?= $carton ?></div>
                                <button class="ver-boton" onclick="event.stopPropagation(); mostrarCarton(<?= $carton ?>)">Ver cartón</button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <p style="text-align:center; color:red;">No se encontraron cartones con ese número.</p>
    <?php endif; ?>

    <div id="cartonModal">
        <div id="modalContent">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <div id="cartonContainer"></div>
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

    function toggleCartones(id) {
        const elem = document.getElementById(id);
        if (elem.classList.contains('hidden')) {
            elem.classList.remove('hidden');
        } else {
            elem.classList.add('hidden');
        }
    }

    function mostrarCarton(numero) {
        const carton = cartonesData[numero];
        if (!carton) return;

        const letras = ['B', 'I', 'N', 'G', 'O'];
        let html = '<table style="border-collapse: collapse; margin: auto; font-size: 16px;">';
        html += '<tr>' + letras.map(l => `<th style="border: 1px solid #000; padding: 8px; background: #007bff; color: white;">${l}</th>`).join('') + '</tr>';
        for (let i = 0; i < 5; i++) {
            html += '<tr>';
            for (let j = 0; j < 5; j++) {
                const index = i * 5 + j;
                const valor = carton[index];
                const clase = valor === 'X' ? 'celda activa' : 'celda';
                html += `<td class="${clase}" onclick="toggleCelda(this)">${valor}</td>`;
            }
            html += '</tr>';
        }
        html += '</table>';

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
        const doc = new jsPDF();
        html2canvas(document.getElementById('cartonContainer')).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 10, 190, 0);
            doc.save('carton.pdf');
        });
    }

    function capturarImagen() {
        html2canvas(document.getElementById('cartonContainer')).then(canvas => {
            const link = document.createElement('a');
            link.download = 'carton.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }

    window.onclick = function(event) {
        const modal = document.getElementById('cartonModal');
        if (event.target == modal) {
            cerrarModal();
        }
    }
    </script>
</body>
</html>
