<?php include('header.php'); ?>
<?php
$conexion = new mysqli("localhost", "root", "", "bingogamer");
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

setlocale(LC_TIME, 'es_ES.UTF-8');

function obtenerVentasPorFecha($conexion, $fecha) {
  $sql = "SELECT nombre, fecha, SUM(CAST(cantidacartones AS UNSIGNED)) AS cantidad, SUM(total) AS total 
          FROM venta 
          WHERE fecha = ?
          GROUP BY nombre, fecha
          ORDER BY fecha DESC";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("s", $fecha);
  $stmt->execute();
  $resultado = $stmt->get_result();
  return generarTablaHTML($resultado, "No hay ventas registradas para la fecha <strong>$fecha</strong>.");
}

function obtenerVentasPorRango($conexion, $desde, $hasta) {
  $sql = "SELECT nombre, fecha, SUM(CAST(cantidacartones AS UNSIGNED)) AS cantidad, SUM(total) AS total 
          FROM venta 
          WHERE fecha BETWEEN ? AND ?
          GROUP BY nombre, fecha
          ORDER BY fecha DESC";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("ss", $desde, $hasta);
  $stmt->execute();
  $resultado = $stmt->get_result();
  return generarTablaHTML($resultado, "No hay ventas registradas entre <strong>$desde</strong> y <strong>$hasta</strong>.");
}

function obtenerVentas($conexion, $modo, $mes = null, $anio = null) {
  if ($modo === 'anio') {
    $condicion = "YEAR(fecha) = YEAR(CURDATE())";
  } elseif ($modo === 'mes') {
    $mes = $mes ?: date('m');
    $anio = $anio ?: date('Y');
    $condicion = "YEAR(fecha) = $anio AND MONTH(fecha) = $mes";
  } else {
    return "<p>Consulta inválida</p>";
  }

  $sql = "SELECT nombre, fecha, SUM(CAST(cantidacartones AS UNSIGNED)) AS cantidad, SUM(total) AS total 
          FROM venta 
          WHERE $condicion
          GROUP BY nombre, fecha
          ORDER BY fecha DESC";
  $resultado = $conexion->query($sql);
  return generarTablaHTML($resultado, "No hay ventas registradas para esta consulta.");
}

function generarTablaHTML($resultado, $mensajeVacio) {
  if (!$resultado || $resultado->num_rows === 0) {
    return "<p>$mensajeVacio</p>";
  }

  $html = "<table style='width:100%; border-collapse:collapse; margin-bottom:20px;'>
            <thead>
              <tr style='background:#0077cc; color:white;'>
                <th style='padding:10px; border:1px solid #ccc;'>Fecha</th>
                <th style='padding:10px; border:1px solid #ccc;'>Nombre</th>
                <th style='padding:10px; border:1px solid #ccc;'>Cantidad</th>
                <th style='padding:10px; border:1px solid #ccc;'>Total</th>
              </tr>
            </thead>
            <tbody>";

  $totalFinal = 0;
  $totalCartones = 0;

  while ($fila = $resultado->fetch_assoc()) {
    $html .= "<tr>
                <td style='padding:10px; border:1px solid #ccc;'>{$fila['fecha']}</td>
                <td style='padding:10px; border:1px solid #ccc;'>{$fila['nombre']}</td>
                <td style='padding:10px; border:1px solid #ccc;'>{$fila['cantidad']}</td>
                <td style='padding:10px; border:1px solid #ccc;'>$" . number_format($fila['total'], 0, ',', '.') . "</td>
              </tr>";
    $totalFinal += $fila['total'];
    $totalCartones += $fila['cantidad'];
  }

  $html .= "</tbody></table>";
  $html .= "<h2 style='text-align:center; color:#0077cc;'>📦 TOTAL CARTONES: " . number_format($totalCartones, 0, ',', '.') . "</h2>";
  $html .= "<h2 style='text-align:center; color:#0077cc;'>💰 TOTAL GENERADO: $" . number_format($totalFinal, 0, ',', '.') . "</h2>";

  return $html;
}

$modo = $_GET['modo'] ?? '';
$mensaje = "";
$resultadoHTML = "";

if ($modo === 'dia') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha'])) {
    $fecha = $_POST['fecha'];
    $resultadoHTML = $fecha ? obtenerVentasPorFecha($conexion, $fecha) : "<p style='color:red;'>⚠️ Debes seleccionar una fecha válida.</p>";
  } else {
    $fecha = date('Y-m-d');
    $resultadoHTML = obtenerVentasPorFecha($conexion, $fecha);
  }
} elseif ($modo === 'semana') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desde'], $_POST['hasta'])) {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $resultadoHTML = ($desde && $hasta) ? obtenerVentasPorRango($conexion, $desde, $hasta) : "<p style='color:red;'>⚠️ Debes seleccionar ambas fechas.</p>";
  } else {
    $desde = date('Y-m-d', strtotime('-6 days'));
    $hasta = date('Y-m-d');
    $resultadoHTML = obtenerVentasPorRango($conexion, $desde, $hasta);
  }
} elseif ($modo === 'mes') {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mes'], $_POST['anio'])) {
    $mes = (int) $_POST['mes'];
    $anio = (int) $_POST['anio'];
    $resultadoHTML = obtenerVentas($conexion, 'mes', $mes, $anio);
  } else {
    $mes = date('m');
    $anio = date('Y');
    $resultadoHTML = obtenerVentas($conexion, 'mes', $mes, $anio);
  }
} elseif ($modo === 'anio') {
  $resultadoHTML = obtenerVentas($conexion, 'anio');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Consulta de Ventas</title>
  <style>
    body { margin: 0; font-family: Arial, sans-serif; background: #f5f5f5; }
    .container { display: flex; min-height: 100vh; }
    .sidebar { width: 220px; background: #0077cc; color: white; padding: 20px; }
    .sidebar h3 { font-size: 20px; margin-bottom: 20px; }
    .sidebar a { display: block; width: 100%; margin-bottom: 12px; padding: 12px; background: #0288d1; color: white; text-decoration: none; border-radius: 5px; font-size: 15px; transition: background 0.3s; }
    .sidebar a:hover { background: #026ea1; }
    .main-content { flex-grow: 1; padding: 30px; background: #fff; }
    .resultado { border: 1px solid #ccc; padding: 20px; border-radius: 8px; background: #fafafa; box-shadow: 0 0 5px rgba(0,0,0,0.1); overflow-x: auto; }
    .formulario-dia { margin-bottom: 20px; padding: 20px; background: #e1f5fe; border-radius: 10px; }
    .formulario-dia input, .formulario-dia select { padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 6px; margin-right: 10px; }
    .formulario-dia button { padding: 10px 18px; font-size: 16px; background: #0077cc; color: white; border: none; border-radius: 6px; cursor: pointer; }
    .formulario-dia button:hover { background: #005fa3; }
    @media (max-width: 768px) {
      .container { flex-direction: column; }
      .sidebar { width: 100%; display: flex; flex-wrap: wrap; justify-content: space-around; }
      .sidebar a { width: 45%; margin-bottom: 10px; text-align: center; }
      .main-content { padding: 15px; }
      table { font-size: 14px; }
      .formulario-dia input, .formulario-dia select, .formulario-dia button { width: 100%; margin-bottom: 10px; }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="sidebar">
    <h3>Consultas</h3>
    <a href="consulta.php?modo=dia">📅 Ventas del Día</a>
    <a href="consulta.php?modo=semana">📆 Ventas de la Semana</a>
    <a href="consulta.php?modo=mes">🗓️ Ventas del Mes</a>
    <a href="consulta.php?modo=anio">📈 Ventas del Año</a>
  </div>
  <div class="main-content">
    <h2>
      <?php
        echo match($modo) {
          'dia' => "📅 Ventas del Día",
          'semana' => "📆 Ventas por Rango de Fechas",
          'mes' => "🗓️ Ventas del Mes",
          'anio' => "📈 Ventas del Año",
          default => "Consulta de Ventas"
        };
      ?>
    </h2>

    <?php if ($modo === 'dia'): ?>
      <div class="formulario-dia">
        <form method="POST" action="consulta.php?modo=dia">
          <label for="fecha">Seleccione una fecha:</label>
          <input type="date" name="fecha" id="fecha" value="<?= $fecha ?? date('Y-m-d') ?>" required />
          <button type="submit">Consultar</button>
        </form>
      </div>
    <?php elseif ($modo === 'semana'): ?>
      <div class="formulario-dia">
        <form method="POST" action="consulta.php?modo=semana">
          <label>Desde:</label>
          <input type="date" name="desde" value="<?= $desde ?? '' ?>" required />
          <label>Hasta:</label>
          <input type="date" name="hasta" value="<?= $hasta ?? '' ?>" required />
          <button type="submit">Consultar</button>
        </form>
      </div>
    <?php elseif ($modo === 'mes'): ?>
      <div class="formulario-dia">
        <form method="POST" action="consulta.php?modo=mes">
          <label for="mes">Mes:</label>
          <select name="mes" id="mes" required>
            <?php
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            for ($i = 1; $i <= 12; $i++): ?>
              <option value="<?= $i ?>" <?= (isset($mes) && $mes == $i) ? 'selected' : '' ?>>
                <?= $meses[$i - 1] ?>
              </option>
            <?php endfor; ?>
          </select>
          <label for="anio">Año:</label>
          <select name="anio" id="anio" required>
            <?php $actual = date('Y'); for ($y = $actual; $y >= $actual - 5; $y--): ?>
              <option value="<?= $y ?>" <?= (isset($anio) && $anio == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
          <button type="submit">Consultar</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="resultado">
      <?= $resultadoHTML ?>
    </div>

    <div style="margin-top: 30px; text-align: center;">
      <a href="menu_superusuario.php" style="padding: 12px 20px; background: #0077cc; color: white; text-decoration: none; border-radius: 6px; font-size: 16px;">
        ⬅️ Volver al Menú Principal
      </a>
    </div>

  </div>
</div>
</body>
</html>
