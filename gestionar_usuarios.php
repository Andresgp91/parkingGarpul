<?php
session_start();
include("db.php");

// Verificar si el usuario es administrador
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "admin") {
    die("Acceso denegado.");
}

// Agregar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["agregar"])) {
    $nombre = $_POST["nombre"];
    $usuario = $_POST["usuario"];
    $clave = password_hash($_POST["clave"], PASSWORD_BCRYPT);
    $rol = $_POST["rol"];

    $sql = "INSERT INTO usuarios (nombre, usuario, clave, rol) VALUES ('$nombre', '$usuario', '$clave', '$rol')";
    $conn->query($sql);
}

// Eliminar usuario
if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $conn->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: gestionar_usuarios.php");
    exit();
}

// Obtener usuarios
$result = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Usuarios</title>
</head>
<body>
    <h2>Lista de Usuarios</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row["id"] ?></td>
                <td><?= $row["nombre"] ?></td>
                <td><?= $row["usuario"] ?></td>
                <td><?= $row["rol"] ?></td>
                <td>
                    <a href="gestionar_usuarios.php?eliminar=<?= $row["id"] ?>">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <h2>Agregar Usuario</h2>
    <form method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="clave" placeholder="Clave" required>
        <select name="rol">
            <option value="empleado">Empleado</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit" name="agregar">Agregar</button>
    </form>
</body>
</html>
