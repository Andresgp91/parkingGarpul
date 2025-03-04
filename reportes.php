<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
include("db.php");

$start_date = "";
$end_date = "";
$report_data = [];
$total_ventas = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    
    // Completar el rango del día si se envían solo fechas
    if (strlen($start_date) == 10) {
        $start_date .= " 00:00:00";
    }
    if (strlen($end_date) == 10) {
        $end_date .= " 23:59:59";
    }
    
    $query = "SELECT * FROM vehiculos 
              WHERE estado = 'Retirado' 
              AND fecha_salida BETWEEN '$start_date' AND '$end_date'
              ORDER BY fecha_salida ASC";
    $res = $conn->query($query);
    if ($res) {
        while($row = $res->fetch_assoc()){
            $report_data[] = $row;
            $total_ventas += $row["total_pagado"];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportes - Administrador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Menú de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
          <a class="navbar-brand" href="#">Parqueadero</a>
          <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav ms-auto">
                  <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                  <li class="nav-item"><a class="nav-link" href="registro_vehiculo.php">Registrar Vehículo</a></li>
                  <li class="nav-item"><a class="nav-link active" href="reportes.php">Reportes</a></li>
                  <li class="nav-item"><a class="nav-link" href="historial_vehiculos.php">Historial Completo</a></li>
                  <li class="nav-item"><a class="nav-link" href="configurar_tarifas.php">Configurar Tarifas</a></li>
                  <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
              </ul>
          </div>
      </div>
  </nav>
  
  <div class="container mt-4">
    <h2>Reporte Personalizado</h2>
    <form action="" method="POST" class="row g-3 mb-4">
      <div class="col-md-5">
        <label for="start_date" class="form-label">Fecha de inicio:</label>
        <input type="date" class="form-control" name="start_date" id="start_date" required value="<?php echo isset($_POST["start_date"]) ? $_POST["start_date"] : ''; ?>">
      </div>
      <div class="col-md-5">
        <label for="end_date" class="form-label">Fecha de fin:</label>
        <input type="date" class="form-control" name="end_date" id="end_date" required value="<?php echo isset($_POST["end_date"]) ? $_POST["end_date"] : ''; ?>">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Generar</button>
      </div>
    </form>
    
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
    <?php elseif($_SERVER["REQUEST_METHOD"] == "POST"): ?>
      <div class="alert alert-warning">No se encontraron registros para el rango de fechas seleccionado.</div>
    <?php endif; ?>
    
    <a href="dashboard.php" class="btn btn-secondary mt-3">Volver al Dashboard</a>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
