<?php
session_start();
if (!isset($_SESSION['superusuario'])) {
    header("Location: superusuario.php");
    exit();
}

include("conexion.php");

// Filtros
$filtro = "";
if (isset($_GET['nombre']) && $_GET['nombre'] != "") {
    $nombre = mysqli_real_escape_string($conn, $_GET['nombre']);
    $filtro = "WHERE nombre LIKE '%$nombre%'";
} elseif (isset($_GET['correo']) && $_GET['correo'] != "") {
    $correo = mysqli_real_escape_string($conn, $_GET['correo']);
    $filtro = "WHERE correo LIKE '%$correo%'";
} elseif (isset($_GET['fecha']) && $_GET['fecha'] != "") {
    $fecha = mysqli_real_escape_string($conn, $_GET['fecha']);
    $filtro = "WHERE fecha = '$fecha'";
} elseif (isset($_GET['digitos']) && $_GET['digitos'] != "") {
    $digitos = mysqli_real_escape_string($conn, $_GET['digitos']);
    $filtro = "WHERE digitos LIKE '%$digitos%'";
} elseif (isset($_GET['telefono']) && $_GET['telefono'] != "") {
    $telefono = mysqli_real_escape_string($conn, $_GET['telefono']);
    $filtro = "WHERE telefono LIKE '%$telefono%'";
}

$query = "SELECT * FROM venta $filtro ORDER BY fecha DESC";
$resultado = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Ventas - Superusuario</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f1f8fd;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #0288d1;
        }

        .cerrar {
            text-align: right;
            margin-bottom: 10px;
        }

        .cerrar a {
            background-color: #e53935;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .cerrar a:hover {
            background-color: #c62828;
        }

        .acciones {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .acciones form {
            display: inline-block;
        }

        .acciones input[type="text"],
        .acciones input[type="date"] {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .acciones input[type="submit"],
        .acciones a {
            background-color: #0288d1;
            color: white;
            padding: 7px 15px;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #0288d1;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .botones-abajo {
            margin-top: 15px;
            text-align: center;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .botones-abajo button, .botones-abajo a {
            background-color: #0288d1;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .botones-abajo button:hover, .botones-abajo a:hover {
            background-color: #026ca0;
        }

        .btn-eliminar {
            background-color: #e53935;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-eliminar:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <div class="cerrar">
        <a href="cerrar.php">Cerrar sesión</a>
    </div>

    <h2>Informe de Ventas</h2>

    <div class="acciones">
        <a href="crear_admin.php">➕ Crear Superusuario</a>

        <form method="get">
            <input type="text" name="nombre" placeholder="Buscar por nombre" value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>

        <form method="get">
            <input type="text" name="correo" placeholder="Buscar por correo" value="<?php echo isset($_GET['correo']) ? htmlspecialchars($_GET['correo']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>

        <form method="get">
            <input type="date" name="fecha" value="<?php echo isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>

        <form method="get">
            <input type="text" name="digitos" placeholder="Buscar por 4 dígitos" value="<?php echo isset($_GET['digitos']) ? htmlspecialchars($_GET['digitos']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>

        <form method="get">
            <input type="text" name="telefono" placeholder="Buscar por teléfono" value="<?php echo isset($_GET['telefono']) ? htmlspecialchars($_GET['telefono']) : ''; ?>">
            <input type="submit" value="Buscar">
        </form>
    </div>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>4 Dígitos</th>
            <th>Imagen</th>
            <th>Cantidad Cartones</th>
            <th>Total</th>
            <th>Cartones</th>
            <th>Fecha</th>
            <th>Eliminar</th>
        </tr>

        <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
                <td><?php echo htmlspecialchars($fila['telefono']); ?></td>
                <td><?php echo htmlspecialchars($fila['correo']); ?></td>
                <td><?php echo htmlspecialchars($fila['digitos']); ?></td>
                <td><a href="<?php echo htmlspecialchars($fila['imagen']); ?>" target="_blank">Ver</a></td>
                <td><?php echo htmlspecialchars($fila['cantidacartones']); ?></td>
                <td><?php echo htmlspecialchars($fila['total']); ?></td>
                <td><?php echo htmlspecialchars($fila['cartones']); ?></td>
                <td><?php 
                    $fecha_formateada = date('d-m-Y', strtotime($fila['fecha']));
                    echo $fecha_formateada;
                ?></td>
                <td>
                    <form action="eliminar_principal.php" method="post" onsubmit="return confirm('¿Estás seguro de eliminar este registro?');">
                        <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                        <button type="submit" class="btn-eliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div class="botones-abajo">
        <button onclick="window.print()">🖨️ Imprimir</button>
        <a href="principal.php">🔄 Volver a consulta</a>
        <a href="principal_ganador.php">🎉 Buscar ganador</a>
        <a href="menu_superusuario.php">⬅️ Volver al menú</a>
    </div>

</body>
</html>
