<?php
header('Content-Type: application/json');
require_once '../conexionBD/conexion.php';

// Consulta para obtener las novedades
$sql = "SELECT titulo, descripcion, imagen FROM novedades";
$result = $conn->query($sql);

$novedades = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $novedades[] = $row;
    }
}

echo json_encode($novedades);

$conn->close();
?>
