<?php
session_start();
if (!isset($_SESSION['superusuario'])) {
    header("Location: superusuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Superusuario</title>
    <style>
        body {
            background: #e0f7fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .menu-container {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px gray;
            text-align: center;
        }

        h2 {
            color: #0288d1;
            margin-bottom: 25px;
            font-size: 24px;
        }

        .menu-button {
            display: block;
            background: #0288d1;
            color: white;
            text-decoration: none;
            margin: 10px auto;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
        }

        .menu-button:hover {
            background: #0277bd;
        }

        .logout {
            margin-top: 30px;
            font-size: 14px;
        }

        .logout a {
            color: #d32f2f;
            text-decoration: none;
            font-weight: bold;
        }

        .logout a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .menu-container {
                margin: 40px 20px;
                padding: 20px;
            }

            h2 {
                font-size: 20px;
            }

            .menu-button {
                font-size: 15px;
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>

<div class="menu-container">
    <h2>Panel del Superusuario</h2>
    <a class="menu-button" href="principal.php">📋 Ver Registros</a>
    <a class="menu-button" href="carton_habilitar.php">🎮 Habilitar Cartones</a>
    <a class="menu-button" href="principal_ganador.php">🏆 Ver Ganadores</a>
    <a class="menu-button" href="precio_carton.php">💲 Precio de Cartón</a>

    <div class="logout">
        <a href="cerrar.php">Cerrar sesión</a>
    </div>
</div>

</body>
</html>
