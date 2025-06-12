<?php
// index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <title>Bingo Gamer - Página Principal</title>
</head>
<body>
  <!-- Incluir header -->
  <?php include 'header.php'; ?>

  <!-- Contenido principal -->
  <main class="content">
    <div class="welcome-container">
      <h1 class="welcome-title">Bienvenido a Bingo Gamer</h1>
      <p class="description">Juega y gana con los mejores cartones, disfruta de la diversión y emoción.</p>
      <p class="purchase-text">¡Compra tu cartón ahora y empieza a jugar!</p>
      <div class="purchase-container">
        <a href="compras.php">
          <button class="purchase-btn">Compra Aquí</button>
        </a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <h2>Gracias por visitar Bingo Gamer</h2>
    <p>Para más información, contáctanos a: contacto@bingogamer.com</p>
  </footer>

  <!-- Botón flotante de WhatsApp en esquina inferior derecha -->
  <a href="https://wa.me/584122997327" target="_blank" class="whatsapp-fixed">
    <img src="imagenes/whatsapp.png" alt="Chat por WhatsApp" class="whatsapp-fixed-logo">
  </a>
</body>
</html>











