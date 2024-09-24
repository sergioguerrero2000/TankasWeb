<?php
// Establecer la conexión con la base de datos
require_once '../conexionBD/conexion.php';

// Obtener datos de la tabla Familia
$sql = "SELECT * FROM familia";
$result = $conn->query($sql);

$familias = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $familias[] = $row;
    }
}

// Obtener datos de la tabla Articulos para cada familia
foreach ($familias as &$familia) {
    $sql = "SELECT * FROM articulos WHERE id_familia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $familia['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $articulos = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $articulos[] = $row;
        }
    }
    $familia['articulos'] = $articulos;
}

// Cerrar la conexión
$conn->close();

// Devolver los datos en formato JSON
echo json_encode($familias);
?>

