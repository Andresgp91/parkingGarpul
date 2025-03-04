<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
include("db.php");

if (!isset($_GET["id"])) {
    die("Error: ID de vehículo no especificado.");
}

$vehiculo_id = intval($_GET["id"]); // Sanitizar el ID

// Obtener tarifas desde la tabla configuracion
$sql_tarifas = "SELECT tarifa_hora, tarifa_dia, tarifa_carro FROM configuracion WHERE id = 1";
$result_tarifas = $conn->query($sql_tarifas);
if ($result_tarifas->num_rows == 0) {
    die("Error: No se encontraron tarifas en la base de datos.");
}
$tarifas = $result_tarifas->fetch_assoc();
$tarifa_moto  = $tarifas["tarifa_hora"];  // Tarifa para motos
$tarifa_carro = $tarifas["tarifa_carro"]; // Tarifa para carros
$tarifa_dia   = $tarifas["tarifa_dia"];   // Tarifa para ambulantes

// Obtener datos del vehículo
$sql = "SELECT * FROM vehiculos WHERE id = $vehiculo_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Error: Vehículo no encontrado.");
}
$vehiculo = $result->fetch_assoc();

$fecha_ingreso = new DateTime($vehiculo["fecha_ingreso"]);
$fecha_salida  = new DateTime(); // Hora actual
$fecha_salida_str = $fecha_salida->format('Y-m-d H:i:s');

$total_pagar = 0;
$tiempo_estacionado = "";

if ($vehiculo["tipo"] == "ambulante") {
    // Para ambulantes, se cobra por días
    $diff = $fecha_ingreso->diff($fecha_salida);
    $dias = $diff->days;
    if ($dias < 1) { $dias = 1; } // Cobro mínimo de 1 día
    $total_pagar = $dias * $tarifa_dia;
    $tiempo_estacionado = "$dias día(s)";
} else {
    // Para motos y carros, se cobra por horas
    $diff = $fecha_ingreso->diff($fecha_salida);
    $minutos_totales = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
    $horas = ceil($minutos_totales / 60); // Redondear siempre hacia arriba
    if ($horas < 1) { $horas = 1; } // Cobro mínimo de 1 hora

    // Determinar tarifa según tipo de vehículo
    if ($vehiculo["tipo"] == "moto") {
        $total_pagar = $horas * $tarifa_moto;
    } elseif ($vehiculo["tipo"] == "carro") {
        $total_pagar = $horas * $tarifa_carro;
    } else {
        die("Error: Tipo de vehículo desconocido.");
    }

    $tiempo_estacionado = "$horas hora(s)";
}

// Actualizar la base de datos con la información de salida
$sql_update = "UPDATE vehiculos 
               SET estado = 'Retirado', fecha_salida = '$fecha_salida_str', total_pagado = $total_pagar 
               WHERE id = $vehiculo_id";

if ($conn->query($sql_update) === TRUE) {
    header("Location: generar_factura.php?id=" . $vehiculo_id);
    exit();
} else {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Error al registrar salida</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
      <div class="container mt-4">
        <div class="alert alert-danger">
          <h4>Error al registrar la salida</h4>
          <p><?php echo $conn->error; ?></p>
        </div>
        <a href="listar_vehiculos.php" class="btn btn-secondary">Volver a la lista</a>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
}
?>
