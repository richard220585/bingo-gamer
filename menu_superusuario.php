<?php
session_start();
if (!isset($_SESSION['superusuario'])) {
    header("Location: superusuario.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
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
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px gray;
            text-align: center;
        }

        h2 {
            color: #0288d1;
            margin-bottom: 20px;
        }

        .menu-button {
            display: block;
            background: #0288d1;
            color: white;
            text-decoration: none;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s;
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
        }

        .logout a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="menu-container">
    <h2>Panel del Superusuario</h2>
    <a class="menu-button" href="principal.php">📋 Ver Registros</a>
    <a class="menu-button" href="carton_habilitar.php">🎮 Habilitar Cartones</a>
    <a class="menu-button" href="principal_ganador.php">🏆 Ver Ganadores</a>

    <div class="logout">
        <a href="cerrar.php">Cerrar sesión</a>
    </div>
</div>

</body>
</html>
