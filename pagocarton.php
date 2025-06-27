<?php
include("conexion.php");
include("header.php");

$contador = isset($_GET['contador']) ? intval($_GET['contador']) : 0;
$cartones = isset($_GET['cartones']) ? $_GET['cartones'] : "[]";
$cartones_decodificados = json_decode($cartones);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $correo = !empty($_POST["correo"]) ? $_POST["correo"] : "";
    $digitos = $_POST["digitos"];
    $fecha = date("Y-m-d");

    $imagenDestino = "";
    if (!empty($_FILES["imagen"]["name"])) {
        $imagenNombre = $_FILES["imagen"]["name"];
        $imagenTemp = $_FILES["imagen"]["tmp_name"];
        $imagenDestino = "imagenes/" . basename($imagenNombre);
        move_uploaded_file($imagenTemp, $imagenDestino);
    }

    $cartonesJSON = $_GET['cartones'];
    $contador = intval($_GET['contador']);
    $total = floatval($_GET['total']);

    $sql = "INSERT INTO venta (nombre, telefono, correo, digitos, imagen, cantidacartones, total, cartones, fecha) 
            VALUES ('$nombre', '$telefono', '$correo', '$digitos', '$imagenDestino', '$contador', '$total', '$cartonesJSON', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                const vendidos = JSON.parse(localStorage.getItem('cartones_vendidos')) || [];
                const comprados = $cartonesJSON;
                comprados.forEach(c => {
                    if (!vendidos.includes(c)) vendidos.push(c);
                });
                localStorage.setItem('cartones_vendidos', JSON.stringify(vendidos));
                alert('¡Pago registrado con éxito!');
                window.location.href='index.php';
              </script>";
        exit();
    } else {
        echo "Error al guardar en la base de datos: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bingo Gamer - Pago del Cartón</title>
    <link rel="stylesheet" href="pagocarton.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #modalMensaje {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
        }
        #modalMensaje .contenido {
            background: white;
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        #modalMensaje button {
            margin: 5px 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        #modalAceptar {
            background-color: #28a745;
            color: white;
        }
        #modalCancelar {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="title">🎟️ PAGO MÓVIL</h1>

    <div class="pago-cuadro">
        <p><strong>Cantidad de Cartones Seleccionados:</strong> <?= $contador ?></p>
        <p><strong>Total a Pagar:</strong> <span id="totalPagar">calculando...</span> BS</p>

        <?php
        if (!empty($cartones_decodificados)) {
            echo "<p><strong>Cartones Seleccionados:</strong> " . implode(", ", $cartones_decodificados) . "</p>";
        } else {
            echo "<p>No se seleccionó ningún cartón.</p>";
        }
        ?>

        <div class="datos-bancarios">
            <p><strong>Banco:</strong> Venezuela (02456)</p>
            <p><strong>Cédula:</strong> 1749191</p>
            <p><strong>Teléfono:</strong> 04247112089</p>
        </div>

        <h3 class="pago-titulo">INGRESE SUS DATOS</h3>

        <form class="formulario-pago" action="pagocarton.php?contador=<?= $contador ?>&total=0&cartones=<?= urlencode($cartones); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <label>Nombre y Apellido:</label>
            <input type="text" name="nombre" id="nombre" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" id="telefono" inputmode="numeric" pattern="[0-9]*" required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo" id="correo" placeholder="(Opcional)">

            <label>Últimos 4 dígitos:</label>
            <input type="text" name="digitos" id="digitos" maxlength="4" required>

            <label>Subir imagen:</label>
            <input type="file" name="imagen" id="imagen">

            <div class="modal-actions">
                <button type="submit" class="modal-btn"><i class="fas fa-paper-plane"></i> Enviar Pago</button>
                <button type="button" onclick="window.location.href='carton.php'" class="modal-btn"><i class="fas fa-undo"></i> Volver</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="modalMensaje">
    <div class="contenido">
        <p>Estimado cliente, este número está registrado en nuestra base de datos. Recuerde que es importante ingresar sus datos correctamente para su premio.</p>
        <button id="modalAceptar">Aceptar</button>
        <button id="modalCancelar">Cancelar</button>
    </div>
</div>

<script>
document.getElementById('telefono').addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('telefono').addEventListener('blur', function () {
    const telefono = this.value.trim();
    if (telefono.length >= 7) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'verificar_telefono.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status === 200 && this.responseText === 'existe') {
                document.getElementById('modalMensaje').style.display = 'block';
            }
        };
        xhr.send('telefono=' + encodeURIComponent(telefono));
    }
});

document.getElementById('modalAceptar').onclick = () => {
    document.getElementById('modalMensaje').style.display = 'none';
};

document.getElementById('modalCancelar').onclick = () => {
    document.getElementById('modalMensaje').style.display = 'none';
    document.querySelector('form').reset();
};

document.getElementById('digitos').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
});

function validarFormulario() {
    const nombre = document.getElementById('nombre').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const digitos = document.getElementById('digitos').value.trim();
    if (!nombre || !telefono || !digitos) {
        alert("Por favor, completa nombre, teléfono y 4 dígitos antes de enviar.");
        return false;
    }
    return true;
}

// Calcular total dinámicamente desde precio_carton.json
fetch('precio_carton.json')
    .then(r => r.json())
    .then(data => {
        const precio = parseFloat(data.precio || 40);
        const cantidad = <?= $contador ?>;
        const total = cantidad * precio;
        document.getElementById('totalPagar').textContent = total.toFixed(2);

        // Reemplazar el valor real en el form (en la URL del action)
        const form = document.querySelector('form');
        const url = new URL(form.action);
        url.searchParams.set('total', total.toFixed(2));
        form.action = url.toString();
    })
    .catch(() => {
        document.getElementById('totalPagar').textContent = 'Error';
    });
</script>

</body>
</html>
