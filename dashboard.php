<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Parqueadero</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Fondo con imagen y overlay para legibilidad */
    body {
      background: url('images/parqueo.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      margin: 0;
      padding: 0;
      position: relative;
      font-family: Arial, sans-serif;
    }
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      z-index: 1;
    }
    /* Tarjeta centrada para el contenido */
    .dashboard-card {
      position: relative;
      z-index: 2;
      max-width: 1000px;
      margin: 50px auto;
      background: rgba(255,255,255,0.95);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      color: #333;
    }
    .dashboard-card h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .welcome-text {
      text-align: center;
      font-size: 1.2rem;
      margin-bottom: 30px;
    }
    .card-img-top {
      height: 200px;
      object-fit: cover;
    }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="position: relative; z-index: 3;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Parqueadero</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        

<ul class="navbar-nav ms-auto">
    <li class="nav-item"><a class="nav-link" href="registro_vehiculo.php">Registrar Vehículo</a></li>
    <li class="nav-item"><a class="nav-link" href="listar_vehiculos.php">Vehículos Activos</a></li>
    <?php if ($_SESSION["rol"] == "admin"): ?>
        <li class="nav-item"><a class="nav-link" href="historial_vehiculos.php">Historial Completo</a></li>
        <li class="nav-item"><a class="nav-link" href="reportes.php">Reportes</a></li>
        <li class="nav-item"><a class="nav-link" href="gestionar_usuarios.php">Gestionar Usuarios</a></li> <!-- Nueva opción -->
    <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="reportes_diarios.php">Reportes Diarios</a></li>
    <?php endif; ?>
    <li class="nav-item"><a class="nav-link" href="configurar_tarifas.php">Configurar Tarifas</a></li>
    <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
</ul>




      </div>
    </div>
  </nav>
  
  <!-- Contenido del Dashboard -->
  <div class="dashboard-card">
    <h2>Bienvenido, <?php echo $_SESSION["usuario"]; ?></h2>
    <p class="welcome-text">
      Bienvenido al panel de administración de Parqueadero. Aquí puedes gestionar el ingreso de vehículos, revisar reportes y controlar las tarifas. ¡Explora las opciones para mantener tu negocio en orden!
    </p>
    
    <div class="row">
      <!-- Tarjeta: Registrar Vehículo -->
      <div class="col-md-4 mb-3">
        <div class="card">
          <img src="images/vehiculos.jpg" class="card-img-top" alt="Registrar Vehículo">
          <div class="card-body">
            <h5 class="card-title">Registrar Vehículo</h5>
            <p class="card-text">Ingresa nuevos vehículos al sistema de forma rápida y sencilla.</p>
            <a href="registro_vehiculo.php" class="btn btn-primary">Ir a Registrar</a>
          </div>
        </div>
      </div>
      <!-- Tarjeta: Vehículos Activos (para ambos roles) -->
      <div class="col-md-4 mb-3">
        <div class="card">
          <img src="images/estacion.jpg" class="card-img-top" alt="Vehículos Activos">
          <div class="card-body">
            <h5 class="card-title">Vehículos Activos</h5>
            <p class="card-text">Consulta la lista de vehículos que están actualmente en el parqueadero.</p>
            <a href="listar_vehiculos.php" class="btn btn-primary">Ver Vehículos</a>
          </div>
        </div>
      </div>
      <!-- Tarjeta: Reportes -->
      <div class="col-md-4 mb-3">
        <div class="card">
          <img src="images/reportes.jpg" class="card-img-top" alt="Reportes">
          <div class="card-body">
            <h5 class="card-title">Reportes</h5>
            <p class="card-text">
              <?php 
                if ($_SESSION["rol"] == "admin") {
                  echo "Consulta reportes personalizados y analiza el rendimiento de tu parqueadero.";
                } else {
                  echo "Consulta los reportes diarios de ventas para mantener el control.";
                }
              ?>
            </p>
            <a href="<?php echo ($_SESSION["rol"]=="admin") ? 'reportes.php' : 'reportes_diarios.php'; ?>" class="btn btn-primary">Ver Reportes</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
