<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
include("db.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Historial de Vehículos</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Parqueadero</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="registro_vehiculo.php">Registrar Vehículo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="listar_vehiculos.php">Vehículos Activos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="historial_vehiculos.php">Historial Completo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Contenedor principal -->
  <div class="container mt-4">
    <h2>Historial Completo de Vehículos</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Placa</th>
          <th>Tipo</th>
          <th>Propietario</th>
          <th>Fecha Ingreso</th>
          <th>Fecha Salida</th>
          <th>Estado</th>
          <th>Total Pagado</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM vehiculos"; // Sin filtro para mostrar todos los registros
        $resultado = $conn->query($sql);
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["id"] . "</td>";
                echo "<td>" . $fila["placa"] . "</td>";
                echo "<td>" . ucfirst($fila["tipo"]) . "</td>";
                echo "<td>" . $fila["propietario"] . "</td>";
                echo "<td>" . $fila["fecha_ingreso"] . "</td>";
                echo "<td>" . ($fila["fecha_salida"] ? $fila["fecha_salida"] : '---') . "</td>";
               echo "<td><span class='badge " . ($fila["estado"] == 'Activo' ? 'bg-success text-white' : 'bg-danger text-white') . "'>" . $fila["estado"] . "</span></td>";
;
                echo "<td>" . ($fila["total_pagado"] ? "$" . number_format($fila["total_pagado"], 0, ',', '.') : '---') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' class='text-center'>No hay registros.</td></tr>";
        }
        ?>
      </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>