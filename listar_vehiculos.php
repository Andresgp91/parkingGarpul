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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Vehículos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Parqueadero</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="registro_vehiculo.php">Registrar Vehículo</a></li>
                    <li class="nav-item"><a class="nav-link" href="listar_vehiculos.php">Ver Vehículos</a></li>
                    <li class="nav-item"><a class="nav-link" href="configurar_tarifas.php">Configurar Tarifas</a></li>
                    <li class="nav-item"><a class="nav-link" href="historial_vehiculos.php">Historial Completo</a></li>
                    <li class="nav-item"><a class="nav-link" href="reportes.php">Reportes de Ventas</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Vehículos Registrados</h2>
        <div class="accordion" id="vehiculosAccordion">
            <?php
            $sql_tarifas = "SELECT tarifa_hora, tarifa_dia, tarifa_carro FROM configuracion WHERE id = 1";
            $result_tarifas = $conn->query($sql_tarifas);
            $tarifas = $result_tarifas->fetch_assoc();
            
            $sql = "SELECT DISTINCT propietario FROM vehiculos WHERE estado = 'Activo'";
            $resultado = $conn->query($sql);
            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    $propietario = $fila["propietario"];
                    $uniqueId = md5($propietario);
                    echo '<div class="accordion-item">';
                    echo '<h2 class="accordion-header">';
                    echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$uniqueId.'">';
                    echo '<span style="margin-right: 10px;">🚗</span>' . $propietario;
                    echo '</button></h2>';
                    echo '<div id="collapse'.$uniqueId.'" class="accordion-collapse collapse" data-bs-parent="#vehiculosAccordion">';
                    echo '<div class="accordion-body">';
                    echo '<table class="table table-striped">';
                    echo '<thead><tr><th>ID</th><th>Placa</th><th>Tipo</th><th>Fecha Ingreso</th><th>Total a Pagar</th><th>Acciones</th><th>Estado</th></tr></thead>';
                    echo '<tbody>';
                    
                    $sql_vehiculos = "SELECT * FROM vehiculos WHERE estado = 'Activo' AND propietario='$propietario'";
                    $vehiculos = $conn->query($sql_vehiculos);
                    while ($v = $vehiculos->fetch_assoc()) {
                        $fecha_ingreso = new DateTime($v["fecha_ingreso"]);
                        $fecha_actual = new DateTime();
                        $diff = $fecha_ingreso->diff($fecha_actual);
                        $total_pagar = 0;
                        
                        $minutos_totales = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        
                        if ($v["tipo"] == "ambulante") {
                            $dias = ceil($minutos_totales / 1440);
                            $total_pagar = $dias * $tarifas["tarifa_dia"];
                        } else {
                            if ($minutos_totales >= 1440) {
                                $dias = ceil($minutos_totales / 1440);
                                $total_pagar = $dias * $tarifas["tarifa_dia"];
                            } else {
                                $horas = ceil($minutos_totales / 60);
                                $tarifa = ($v["tipo"] == "moto") ? $tarifas["tarifa_hora"] : $tarifas["tarifa_carro"];
                                $total_pagar = $horas * $tarifa;
                            }
                        }
                        
                        echo "<tr>
                                <td>" . $v["id"] . "</td>
                                <td>" . $v["placa"] . "</td>
                                <td>" . ucfirst($v["tipo"]) . "</td>
                                <td>" . $v["fecha_ingreso"] . "</td>
                                <td>\$" . number_format($total_pagar, 0, ',', '.') . "</td>
                                <td>
                                    <a href='salida_vehiculo.php?id=" . $v["id"] . "' class='btn btn-primary btn-sm'>
                                        <img src='images/whatsapp_icon.png' alt='WhatsApp' style='width: 16px; height: 16px; margin-right: 5px;'>
                                        Factura y Salida
                                    </a>
                                </td>
                                <td><span class='badge badge-success'>Activo</span></td>
                              </tr>";
                    }
                    echo '</tbody></table>';
                    echo '</div></div></div>';
                }
            } else {
                echo "<p class='text-center'>No hay vehículos activos.</p>";
            }
            ?>
        </div>
        <a href="dashboard.php" class="btn btn-secondary mt-3">Volver al Dashboard</a>
    </div>
</body>
</html>
