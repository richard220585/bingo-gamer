<?php
session_start();
if (!isset($_SESSION['superusuario'])) {
    header("Location: superusuario.php");
    exit();
}

include("conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = mysqli_real_escape_string($conn, $_POST["usuario"]);
    $clave = mysqli_real_escape_string($conn, $_POST["clave"]);

    // Verificar si ya existe
    $existe = mysqli_query($conn, "SELECT * FROM admin WHERE usuario = '$usuario'");
    if (mysqli_num_rows($existe) > 0) {
        $mensaje = "⚠️ El usuario ya existe.";
    } else {
        $insertar = "INSERT INTO admin (usuario, clave) VALUES ('$usuario', '$clave')";
        if (mysqli_query($conn, $insertar)) {
            $mensaje = "✅ Usuario creado exitosamente.";
        } else {
            $mensaje = "❌ Error al crear el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Crear Superusuario</title>
    <style>
        body {
            background: #f1f8fd;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
            width: 350px;
        }

        h2 {
            text-align: center;
            color: #0288d1;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0288d1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0277bd;
        }

        .mensaje {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .volver {
            text-align: center;
            margin-top: 15px;
        }

        .volver a {
            color: #0288d1;
            text-decoration: none;
        }

        .volver a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Crear Superusuario</h2>
        <form method="post">
            <input type="text" name="usuario" placeholder="Nuevo usuario" required>
            <input type="password" name="clave" placeholder="Clave" required>
            <input type="submit" value="Crear">
        </form>
        <?php if ($mensaje != "") echo "<div class='mensaje'>$mensaje</div>"; ?>
        <div class="volver">
            <a href="principal.php">⬅ Volver al panel</a>
        </div>
    </div>
</body>
</html>
