<?php
date_default_timezone_set('America/Bogota');

$host = "localhost";
$usuario = "root";
$clave = "";
$bd = "parqueadero_db";

$conn = new mysqli($host, $usuario, $clave, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar la zona horaria en MySQL para esta sesión
$conn->query("SET time_zone = 'America/Bogota'");

echo "Conexión exitosa a la base de datos: " . $bd;
?>