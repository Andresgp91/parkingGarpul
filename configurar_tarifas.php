<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Permitir acceso tanto a admin como a empleado
if ($_SESSION["rol"] != "admin" && $_SESSION["rol"] != "empleado") {
    header("Location: dashboard.php");
    exit();
}

include("db.php");

// Obtener tarifas actuales
$sql = "SELECT * FROM configuracion WHERE id = 1";
$resultado = $conn->query($sql);
$config = $resultado->fetch_assoc();

// Actualizar tarifas si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarifa_hora = $_POST["tarifa_hora"];
    $tarifa_dia = $_POST["tarifa_dia"];
    $tarifa_carro = $_POST["tarifa_carro"];

    $sql = "UPDATE configuracion SET 
                tarifa_hora = $tarifa_hora, 
                tarifa_dia = $tarifa_dia, 
                tarifa_carro = $tarifa_carro 
            WHERE id = 1";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Tarifas actualizadas correctamente.</div>";
        header("refresh:2;url=dashboard.php");
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Configurar Tarifas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Parqueadero</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="registro_vehiculo.php">Registrar Vehículo</a></li>
          <li class="nav-item"><a class="nav-link" href="reportes_diarios.php">Reportes Diarios</a></li>
          <li class="nav-item"><a class="nav-link active" href="configurar_tarifas.php">Configurar Tarifas</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="container mt-4">
    <h2>Configurar Tarifas</h2>
    <form action="" method="POST">
      <div class="mb-3">
        <label for="tarifa_hora" class="form-label">Tarifa Moto por Hora:</label>
        <input type="number" name="tarifa_hora" class="form-control" value="<?php echo $config['tarifa_hora']; ?>" required>
      </div>
      <div class="mb-3">
        <label for="tarifa_dia" class="form-label">Tarifa Ambulante por Día:</label>
        <input type="number" name="tarifa_dia" class="form-control" value="<?php echo $config['tarifa_dia']; ?>" required>
      </div>
      <div class="mb-3">
        <label for="tarifa_carro" class="form-label">Tarifa Carro por Hora:</label>
        <input type="number" name="tarifa_carro" class="form-control" value="<?php echo $config['tarifa_carro']; ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Guardar Tarifas</button>
    </form>
    <br>
    <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
