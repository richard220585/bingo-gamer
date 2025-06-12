<?php
// crear_juego.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Tipo de Juego</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f8ff;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 40px;
      padding: 40px;
    }

    .carton {
      display: grid;
      grid-template-columns: repeat(5, 60px);
      grid-template-rows: repeat(5, 60px);
      gap: 5px;
    }

    .casilla {
      width: 60px;
      height: 60px;
      background-color: white;
      border: 2px solid #007BFF;
      text-align: center;
      line-height: 60px;
      font-size: 18px;
      cursor: pointer;
      user-select: none;
    }

    .casilla.seleccionada {
      background-color: #28a745;
      color: white;
    }

    .casilla.centro {
      background-color: #ccc;
      pointer-events: none;
    }

    .formulario {
      max-width: 300px;
    }

    label, input, button {
      display: block;
      margin-bottom: 10px;
      width: 100%;
    }

    button {
      padding: 10px;
      font-size: 16px;
      cursor: pointer;
    }

    #mensaje {
      margin-top: 10px;
      color: green;
    }
  </style>
</head>
<body>

  <div class="carton" id="carton">
    <!-- Se generan 25 casillas (índices 0 al 24) -->
    <?php for ($i = 0; $i < 25; $i++): ?>
      <div class="casilla <?= $i === 12 ? 'centro' : '' ?>" data-pos="<?= $i ?>">
        <?= $i === 12 ? 'X' : '' ?>
      </div>
    <?php endfor; ?>
  </div>

  <div class="formulario">
    <form method="POST" action="guardar_juego.php" onsubmit="return prepararDatos()">
      <label for="nombre">Tipo de Juego:</label>
      <input type="text" name="nombre" id="nombre" required>

      <input type="hidden" name="posiciones" id="posiciones">

      <button type="submit">Crear Juego</button>
      <button type="button" onclick="limpiarSeleccion()">Limpiar</button>

      <div id="mensaje"></div>
    </form>
  </div>

  <script>
    const casillas = document.querySelectorAll('.casilla:not(.centro)');
    const posicionesInput = document.getElementById('posiciones');

    casillas.forEach(casilla => {
      casilla.addEventListener('click', () => {
        casilla.classList.toggle('seleccionada');
      });
    });

    function limpiarSeleccion() {
      casillas.forEach(c => c.classList.remove('seleccionada'));
    }

    function prepararDatos() {
      const seleccionadas = [];
      casillas.forEach(c => {
        if (c.classList.contains('seleccionada')) {
          seleccionadas.push(parseInt(c.dataset.pos));
        }
      });
      posicionesInput.value = JSON.stringify(seleccionadas);
      return true;
    }
  </script>

</body>
</html>
