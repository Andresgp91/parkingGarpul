<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = md5($_POST["clave"]); // Encriptamos la clave con MD5

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'";
    $resultado = $conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $_SESSION["usuario"] = $fila["usuario"];
        $_SESSION["rol"] = $fila["rol"];

        echo "Inicio de sesión exitoso. Redirigiendo...";
        header("refresh:2;url=dashboard.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}
?>