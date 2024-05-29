<?php
$conn = new mysqli('127.0.0.1', 'root', 'Root1.', 'tfg');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
echo "Conexión exitosa";
$conn->close();

