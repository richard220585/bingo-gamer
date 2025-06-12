<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Bingo Gamer - Compra</title>
</head>
<body>
    <header>
        <!-- Logo de la página -->
        <img src="imagenes/bingo gamer.jpg" alt="Logo Bingo" class="logo-img">
        
        <!-- Menú de imágenes -->
        <div class="menu">
            <div class="menu-item">
                <a href="compras.php">
                    <img src="imagenes/compra.png" alt="Compra" class="icon">
                    <button class="menu-btn">Compra</button>
                </a>
            </div>
            <div class="menu-item">
                <a href="compras.php">
                    <img src="imagenes/descarga.png" alt="Descarga" class="icon">
                    <button class="menu-btn">Descargar</button>
                </a>
            </div>
            <div class="menu-item">
                <a href="compras.php">
                    <img src="imagenes/testimonio.jpg" alt="Testimonio" class="icon">
                    <button class="menu-btn">Testimonio</button>
                </a>
            </div>
            <div class="menu-item">
                <a href="compras.php">
                    <img src="imagenes/soporte.jpg" alt="Soporte" class="icon">
                    <button class="menu-btn">Soporte</button>
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="content">
        <h1 class="step-title">Paso 1: Selecciona tu Cartón</h1>

        <p class="instruction">Ahora selecciona el número de cartón que deseas jugar (puedes elegir más de uno, recuerda que cada uno cuesta 40 Bolívares).</p>

        <div class="carton-selection">
            <div class="carton-info">
                <p class="carton-text">Valor del Cartón: 40 Bs</p>
                <p class="carton-text">Fecha de hoy: <strong id="fechaHoy"></strong></p>
                <a href="carton.php">
                    <button class="select-btn">Seleccionar Cartón</button>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <h2>Gracias por visitar Bingo Gamer</h2>
        <p>Para más información, contáctanos a: contacto@bingogamer.com</p>
    </footer>

    <script>
        // Función para mostrar la fecha de hoy
        const fechaHoy = new Date().toLocaleDateString();
        document.getElementById("fechaHoy").textContent = fechaHoy;
    </script>
</body>
</html>
