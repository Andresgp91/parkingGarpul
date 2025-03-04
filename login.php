<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Parqueadero</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Fondo con la imagen del parqueadero */
    body {
      background: url('images/parqueo.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Tarjeta de Login */
    .login-card {
      width: 100%;
      max-width: 400px;
      background: rgba(255, 255, 255, 0.9); /* Fondo blanco con transparencia */
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .login-card h2 {
      text-align: center;
      color: #333;
      font-weight: bold;
    }

    /* Estilo para los inputs */
    .form-control {
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    /* Botón atractivo */
    .btn-custom {
      background: linear-gradient(45deg, #ff416c, #ff4b2b);
      border: none;
      color: white;
      padding: 10px;
      font-size: 18px;
      border-radius: 8px;
      transition: 0.3s;
    }

    .btn-custom:hover {
      background: linear-gradient(45deg, #ff4b2b, #ff416c);
    }

  </style>
</head>
<body>
  <!-- Tarjeta de Login -->
  <div class="login-card">
    <h2>Iniciar Sesión</h2>
    <form action="validar.php" method="POST">
      <div class="mb-3">
        <label for="usuario" class="form-label">Usuario:</label>
        <input type="text" class="form-control" name="usuario" required>
      </div>
      <div class="mb-3">
        <label for="clave" class="form-label">Contraseña:</label>
        <input type="password" class="form-control" name="clave" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-custom">Ingresar</button>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>