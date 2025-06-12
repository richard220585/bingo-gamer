<?php
include("conexion.php");

$contador = isset($_GET['contador']) ? intval($_GET['contador']) : 0;
$total = isset($_GET['total']) ? intval($_GET['total']) : 0;
$cartones = isset($_GET['cartones']) ? $_GET['cartones'] : "[]"; // codificado como string JSON
$cartones_decodificados = json_decode($cartones);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $digitos = $_POST["digitos"];
    $fecha = date("Y-m-d");

    // Procesar imagen
    $imagenNombre = $_FILES["imagen"]["name"];
    $imagenTemp = $_FILES["imagen"]["tmp_name"];
    $imagenDestino = "imagenes/" . basename($imagenNombre);

    if (move_uploaded_file($imagenTemp, $imagenDestino)) {
        $sql = "INSERT INTO venta (nombre, telefono, correo, digitos, imagen, cantidacartones, total, cartones, fecha) 
                VALUES ('$nombre', '$telefono', '$correo', '$digitos', '$imagenDestino', '$contador', '$total', '$cartones', '$fecha')";

        if ($conn->query($sql) === TRUE) {
            // Guardar cartones vendidos en localStorage (agregado más abajo en JS)
            echo "<script>
                    const vendidos = JSON.parse(localStorage.getItem('cartones_vendidos')) || [];
                    const comprados = $cartones;
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
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="carton.css">
    <link rel="stylesheet" href="pagocarton.css">
    <title>Bingo Gamer - Pago del Cartón</title>
</head>
<body>

<header class="header">
    <a href="index.php">
        <img src="imagenes/bingo gamer.jpg" alt="Logo Bingo" class="logo">
    </a>
</header>

<div class="container">
    <h1 class="title">Resumen de Pago</h1>

    <div class="pago-cuadro">
        <?php
            echo "<p><strong>Cantidad de Cartones Seleccionados:</strong> $contador</p>";
            echo "<p><strong>Total a Pagar:</strong> $total BS</p>";

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

        <h3 class="pago-titulo">Pago Móvil</h3>

        <form class="formulario-pago" action="pagocarton.php?contador=<?php echo $contador; ?>&total=<?php echo $total; ?>&cartones=<?php echo urlencode($cartones); ?>" method="POST" enctype="multipart/form-data" onsubmit="return validarFormulario()">
            <label>Nombre y Apellido:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Ingrese su nombre" oninput="activarCampo('telefono')" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" id="telefono" placeholder="Ingrese su número" disabled oninput="activarCampo('correo')" required>

            <label>Correo electrónico:</label>
            <input type="email" name="correo" id="correo" placeholder="Ingrese su correo" disabled oninput="activarCampo('digitos')" required>

            <label>Últimos 4 dígitos:</label>
            <input type="text" name="digitos" id="digitos" placeholder="Ingrese sus 4 dígitos" maxlength="4" disabled oninput="activarCampo('imagen')" required>

            <label>Subir imagen:</label>
            <input type="file" name="imagen" id="imagen" disabled required>

            <div class="modal-actions">
                <button type="submit" class="modal-btn">Enviar Pago</button>
                <button type="button" onclick="window.location.href='carton.php'" class="modal-btn">Volver a Seleccionar cartón</button>
            </div>
        </form>
    </div>
</div>

<script>
    function activarCampo(id) {
        const campo = document.getElementById(id);
        if (campo) {
            campo.disabled = false;
        }
    }

    function validarFormulario() {
        const nombre = document.getElementById('nombre').value.trim();
        const telefono = document.getElementById('telefono').value.trim();
        const correo = document.getElementById('correo').value.trim();
        const digitos = document.getElementById('digitos').value.trim();
        const imagen = document.getElementById('imagen').value;

        if (!nombre || !telefono || !correo || !digitos || !imagen) {
            alert("Por favor, completa todos los campos en orden antes de enviar.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>


