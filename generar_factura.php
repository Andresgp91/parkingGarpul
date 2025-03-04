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

$vehiculo_id = $_GET["id"];
$sql = "SELECT * FROM vehiculos WHERE id = $vehiculo_id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Error: Vehículo no encontrado.");
}
$vehiculo = $result->fetch_assoc();

// Obtener tarifas desde la tabla configuración
$sql_tarifa = "SELECT tarifa_hora, tarifa_dia, tarifa_carro FROM configuracion"; 
$result_tarifa = $conn->query($sql_tarifa);

if ($result_tarifa->num_rows == 0) {
    die("Error: No se encontraron tarifas en la configuración.");
}
$tarifas = $result_tarifa->fetch_assoc();

// Asignar tarifas según el tipo de vehículo
$tipo_vehiculo = $vehiculo["tipo"];
if ($tipo_vehiculo == "moto") {
    $tarifa = $tarifas["tarifa_hora"];
} elseif ($tipo_vehiculo == "carro") {
    $tarifa = $tarifas["tarifa_carro"];
} elseif ($tipo_vehiculo == "ambulante") {
    $tarifa = $tarifas["tarifa_dia"];
} else {
    die("Error: Tipo de vehículo no reconocido.");
}

// Calcular fechas y diferencia
$fecha_ingreso = new DateTime($vehiculo["fecha_ingreso"]);
$fecha_salida = new DateTime(); // Hora actual
$fecha_salida_str = $fecha_salida->format('Y-m-d H:i:s');

$intervalo = $fecha_ingreso->diff($fecha_salida);
$total_horas = ($intervalo->days * 24) + $intervalo->h + ($intervalo->i > 0 ? 1 : 0); // Redondeo de minutos adicionales

if ($tipo_vehiculo == "ambulante") {
    // Redondear siempre al día completo
    $dias = ceil($total_horas / 24);
    $total_pagar = $dias * $tarifa;
    $detalle_tiempo = "$dias día(s)";
} else {
    // Cobro mínimo de 1 hora
    if ($total_horas < 1) {
        $total_horas = 1;
    }
    $total_pagar = $total_horas * $tarifa;
    $detalle_tiempo = "$total_horas hora(s)";
}

// Guardar en la base de datos
$sql_update = "UPDATE vehiculos SET fecha_salida = '$fecha_salida_str', total_pagado = $total_pagar, tiempo_estacionado = '$detalle_tiempo', estado = 'Retirado' WHERE id = $vehiculo_id";
$conn->query($sql_update);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Factura - Parqueadero</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .invoice-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      border: 1px solid #eee;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
      background: #fff;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Parqueadero</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="listar_vehiculos.php">Vehículos</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="invoice-box container mt-4">
    <h2 class="text-center">Factura de Parqueadero</h2>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <p><strong>Placa:</strong> <?php echo $vehiculo["placa"]; ?></p>
        <p><strong>Tipo:</strong> <?php echo ucfirst($vehiculo["tipo"]); ?></p>
        <p><strong>Propietario:</strong> <?php echo $vehiculo["propietario"]; ?></p>
      </div>
      <div class="col-md-6 text-end">
        <p><strong>Fecha de Ingreso:</strong> <?php echo $vehiculo["fecha_ingreso"]; ?></p>
        <p><strong>Fecha de Salida:</strong> <?php echo $fecha_salida_str; ?></p>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-6">
        <p><strong>Tiempo Estacionado:</strong> <?php echo $detalle_tiempo; ?></p>
      </div>
      <div class="col-md-6 text-end">
        <p><strong>Total a Pagar:</strong> $<?php echo number_format($total_pagar, 0, ',', '.'); ?></p>
      </div>
    </div>
    <div class="text-center mt-4">
      <button class="btn btn-primary" onclick="window.print()">Imprimir Factura</button>
    
      <a href="https://wa.me/<?php echo $vehiculo['telefono']; ?>?text=<?php echo urlencode("Factura de parqueadero\nPlaca: " . $vehiculo['placa'] . "\nTotal a Pagar: $" . number_format($total_pagar, 0, ',', '.')); ?>" target="_blank" class="btn btn-success">Enviar por WhatsApp</a>
    
      <div class="text-center mt-3">
        <a href="listar_vehiculos.php" class="btn btn-secondary">Volver</a>
      </div>
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>