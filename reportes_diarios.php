<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
// Permitir acceso tanto a empleado como a admin (si es necesario)
if ($_SESSION["rol"] != "empleado" && $_SESSION["rol"] != "admin") {
    header("Location: dashboard.php");
    exit();
}
include("db.php");

// Rango del día actual
$start_date = date("Y-m-d 00:00:00");
$end_date = date("Y-m-d 23:59:59");

$report_data = [];
$total_ventas = 0;

$query = "SELECT * FROM vehiculos 
          WHERE estado = 'Retirado' 
          AND fecha_salida BETWEEN '$start_date' AND '$end_date'
          ORDER BY fecha_salida ASC";
$res = $conn->query($query);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $report_data[] = $row;
        $total_ventas += $row["total_pagado"];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportes Diarios - Empleado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Menú de navegación similar al dashboard -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Parqueadero</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="registro_vehiculo.php">Registrar Vehículo</a></li>
          <li class="nav-item"><a class="nav-link active" href="reportes_diarios.php">Reportes Diarios</a></li>
          <li class="nav-item"><a class="nav-link" href="configurar_tarifas.php">Configurar Tarifas</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="container mt-4">
      <h2>Reporte Diario</h2>
      <?php if(!empty($report_data)): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Placa</th>
            <th>Tipo</th>
            <th>Propietario</th>
            <th>Fecha Ingreso</th>
            <th>Fecha Salida</th>
            <th>Total Pagado</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($report_data as $row): ?>
          <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><?php echo $row["placa"]; ?></td>
            <td><?php echo ucfirst($row["tipo"]); ?></td>
            <td><?php echo $row["propietario"]; ?></td>
            <td><?php echo $row["fecha_ingreso"]; ?></td>
            <td><?php echo $row["fecha_salida"]; ?></td>
            <td>$<?php echo number_format($row["total_pagado"], 0, ',', '.'); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="6" class="text-end">Total en ventas:</th>
            <th>$<?php echo number_format($total_ventas, 0, ',', '.'); ?></th>
          </tr>
        </tfoot>
      </table>
      <?php else: ?>
        <div class="alert alert-warning">No se encontraron registros para el día actual.</div>
      <?php endif; ?>
      <a href="dashboard.php" class="btn btn-secondary mt-3">Volver al Dashboard</a>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
