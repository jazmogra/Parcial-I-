<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservaciones del Hotel</title>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hotel = trim($_POST['hotel']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $fecha = trim($_POST['fecha']);
    $observaciones = trim($_POST['observaciones']);
    
    // Validar que todos los campos estén llenos
    if (empty($hotel) || empty($nombre) || empty($apellido) || empty($telefono) || empty($fecha) || empty($observaciones)) {
        die('Todos los campos son requeridos.');
    }
    
    // Validar formato del teléfono (10 dígitos)
    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        die('El teléfono debe tener 10 dígitos.');
    }
    
    // Validar formato de la fecha
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        die('Formato de fecha no válido.');
    }
    
    // Guardar la reservación en un archivo plano
    $reservacion = "$hotel,$nombre,$apellido,$telefono,$fecha,$observaciones\n";
    if (file_put_contents('reservaciones.txt', $reservacion, FILE_APPEND) === false) {
        die('Error al guardar la reservación.');
    }
    
    // Leer y mostrar todas las reservaciones
    echo '<h2>Reservaciones:</h2>';
    echo '<table border="1">';
    echo '<tr><th>Hotel</th><th>Nombre</th><th>Apellido</th><th>Teléfono</th><th>Fecha</th><th>Observaciones</th></tr>';
    if ($file = fopen('reservaciones.txt', 'r')) {
        while (($line = fgets($file)) !== false) {
            list($hotel, $nombre, $apellido, $telefono, $fecha, $observaciones) = explode(',', trim($line));
            echo '<tr>';
            echo "<td>$hotel</td>";
            echo "<td>$nombre</td>";
            echo "<td>$apellido</td>";
            echo "<td>$telefono</td>";
            echo "<td>$fecha</td>";
            echo "<td>$observaciones</td>";
            echo '</tr>';
        }
        fclose($file);
    } else {
        echo '<tr><td colspan="6">No se pudieron leer las reservaciones.</td></tr>';
    }
    echo '</table>';
} else {
    die('Método de solicitud no válido.');
}
?>
</body>
</html>
