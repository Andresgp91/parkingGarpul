<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
include("db.php");

// Recibir y sanitizar los datos del formulario
$placa = isset($_POST["placa"]) ? $conn->real_escape_string(trim($_POST["placa"])) : "";
$tipo = isset($_POST["tipo"]) ? $conn->real_escape_string(trim($_POST["tipo"])) : "";
$propietario = isset($_POST["propietario"]) ? $conn->real_escape_string(trim($_POST["propietario"])) : "";
$telefono = isset($_POST["telefono"]) ? $conn->real_escape_string(trim($_POST["telefono"])) : "";
$cantidad = 1; // Valor predeterminado

if ($tipo === "ambulante") {
    $cantidad = isset($_POST["cantidad_carritos"]) ? intval($_POST["cantidad_carritos"]) : 1;
}

// Validación básica
if (empty($placa) || empty($tipo) || empty($propietario) || empty($telefono)) {
    die("Faltan datos obligatorios.");
}

$insertados = 0;
$errores = [];

// Si el vehículo es ambulante, se insertan tantos registros como la cantidad especificada
if ($tipo === "ambulante") {
    for ($i = 0; $i < $cantidad; $i++) {
        $placaAmbulante = $placa . "-" . ($i + 1);
        $sql = "INSERT INTO vehiculos (placa, tipo, propietario, telefono, fecha_ingreso, estado)
                VALUES ('$placaAmbulante', '$tipo', '$propietario', '$telefono', NOW(), 'Activo')";
        if ($conn->query($sql) === TRUE) {
            $insertados++;
        } else {
            $errores[] = $conn->error;
        }
    }
} else {
    // Para motos y carros, se inserta un único registro
    $sql = "INSERT INTO vehiculos (placa, tipo, propietario, telefono, fecha_ingreso, estado)
            VALUES ('$placa', '$tipo', '$propietario', '$telefono', NOW(), 'Activo')";
    if ($conn->query($sql) === TRUE) {
        $insertados = 1;
    } else {
        $errores[] = $conn->error;
    }
}

// Si se insertó correctamente, enviar mensaje de WhatsApp
if (empty($errores)) {
    $mensaje = "Hola, su vehículo con placa $placa ha sido registrado en el parqueadero a las " . date("H:i") . ".";
    $mensaje = urlencode($mensaje);
    $whatsapp_url = "https://wa.me/$telefono?text=$mensaje";

    // Redirige al dashboard pero también abre WhatsApp en una nueva pestaña
    echo "<script>
        window.open('$whatsapp_url', '_blank'); // Abre WhatsApp en otra pestaña
        window.location.href = 'dashboard.php'; // Mantiene al usuario en el sistema
    </script>";
    exit();
}

// Redirigir o mostrar errores
if (!empty($errores)) {
    echo "Se insertaron $insertados registros.<br>";
    echo "Errores:<br>";
    foreach ($errores as $error) {
        echo $error . "<br>";
    }
}
?>
