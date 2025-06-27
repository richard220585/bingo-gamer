<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Bingo - Selección de Números</title>
  <link rel="stylesheet" href="seleccionar.css" />
</head>
<body>

  <h2>🎱 BINGO - Selecciona Números</h2>

  <div id="contenedor-tablero">
    <table id="bingoTable" aria-label="Tablero de Bingo" role="grid">
      <thead>
        <tr>
          <th>B</th>
          <th>I</th>
          <th>N</th>
          <th>G</th>
          <th>O</th>
        </tr>
      </thead>
      <tbody>
        <!-- Se llena dinámicamente con JS -->
      </tbody>
    </table>

    <div id="panel-derecho">
      <label for="modoJuego">Modo de juego:</label>
      <select id="modoJuego">
        <option value="">Selecciona un modo</option>
        <option value="cuatro_esquinas">🔲 4 Esquinas</option>
        <option value="carton_lleno">✅ Cartón lleno</option>
        <option value="letra_x">❌ Letra X</option>
        <option value="letra_l">🟥 Letra L</option>
        <option value="letra_t">🟥 Letra T</option>
        <option value="cruz_central">✝️ Cruz Central</option>
      </select>

      <div id="numeroGrande">--</div>
      <!-- 🟢 Contador de jugadas -->
      <h3 id="contadorJugadas" style="margin-top: 5px;">0 de 75</h3>
      <div id="ultimosContainer">
        <h3>Últimos 5 números:</h3>
        <div id="ultimosNumeros"></div>
      </div>

      <div id="cartonEjemplo">
        <!-- Aquí se cargará el cartón de ejemplo según el modo -->
      </div>

      <div id="mensajeGanador" style="margin-top: 20px;"></div>

      <!-- Botones Limpiar y Toggle Desdichado -->
      <button id="btnLimpiar" style="margin-top: 10px;">Limpiar</button>
      <button id="btnToggleDesdichado" style="margin-top: 10px;">Deshabilitar desdichado</button>
    </div>
  </div>

  <audio id="sonidoClick" src="click.mp3" preload="auto"></audio>

  <script src="seleccionar.js"></script>
</body>
</html>

