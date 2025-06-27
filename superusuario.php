<?php
session_start();
include("conexion.php"); // Asegúrate de tener este archivo con la conexión a la DB

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    // Consulta para validar usuario
    $query = "SELECT * FROM admin WHERE usuario = '$usuario' AND clave = '$clave'";
    $resultado = mysqli_query($conn, $query);

    if (mysqli_num_rows($resultado) == 1) {
        $_SESSION['superusuario'] = $usuario;
       header("Location: menu_superusuario.php");

        exit();
    } else {
        $mensaje = "Usuario o clave incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Superusuario</title>
    <style>
        body {
            background: #e0f7fa;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
            width: 300px;
        }

        h2 {
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }

        input[type="submit"] {
            width: 100%;
            background: #0288d1;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .mensaje {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Superusuario</h2>
        <form method="post">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="clave" placeholder="Clave" required>
            <input type="submit" value="Entrar">
        </form>
        <?php if ($mensaje != "") { echo "<p class='mensaje'>$mensaje</p>"; } ?>
    </div>
</body>
</html>
