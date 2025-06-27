<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$basededatos = "bingogamer";

$conn = new mysqli("localhost", "root", "", "bingogamer");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
