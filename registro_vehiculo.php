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
  <title>Registrar Vehículo</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function toggleCantidad() {
      var tipo = document.getElementById('tipo').value;
      var cantidadDiv = document.getElementById('cantidadDiv');
      if (tipo === 'ambulante') {
        cantidadDiv.style.display = 'block';
      } else {
        cantidadDiv.style.display = 'none';
      }
    }
  </script>
</head>
<body>
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Parqueadero</a>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="registro_vehiculo.php">Registrar Vehículo</a></li>
          <li class="nav-item"><a class="nav-link" href="listar_vehiculos.php">Ver Vehículos</a></li>
          <li class="nav-item"><a class="nav-link" href="configurar_tarifas.php">Configurar Tarifas</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
  
  <div class="container mt-4">
    <h2>Registrar Vehículo</h2>
    <form action="guardar_vehiculo.php" method="POST">
      <div class="mb-3">
        <label for="placa" class="form-label">Placa o Articulo</label>
        <input type="text" class="form-control" name="placa" required>
      </div>
      <div class="mb-3">
        <label for="tipo" class="form-label">Tipo de Vehículo</label>
        <select class="form-select" name="tipo" id="tipo" onchange="toggleCantidad()" required>
          <option value="moto">Moto</option>
          <option value="carro">Carro</option>
          <option value="ambulante">Vehículo Ambulante (cobro por día)</option>
        </select>
      </div>
      <!-- Campo para cantidad de carritos (solo se muestra si el tipo es ambulante) -->
      <div class="mb-3" id="cantidadDiv" style="display: none;">
        <label for="cantidad_carritos" class="form-label">Cantidad de Carritos</label>
        <input type="number" class="form-control" name="cantidad_carritos" id="cantidad_carritos" min="1" value="1">
      </div>
      <div class="mb-3">
        <label for="propietario" class="form-label">Nombre del Propietario</label>
        <input type="text" class="form-control" name="propietario" required>
      </div>
      <div class="mb-3">
    <label for="telefono" class="form-label">Teléfono del dueño</label>
    <input type="text" class="form-control" id="telefono" name="telefono" required>
</div>
      <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
    <br>
    <a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
  </div>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>