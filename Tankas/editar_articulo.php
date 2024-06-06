<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require 'conexionBD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $disponible = $_POST['disponible'];
    $familia = $_POST['familia'];

    if (!empty($id) && !empty($nombre) && !empty($precio) && !empty($disponible) && !empty($familia)) {

        $stmt = $conn->prepare("UPDATE articulos SET nombre = ?, precio = ?, disponible = ?, id_familia = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $precio, $disponible, $familia, $id);

        if ($stmt->execute()) {
            header("Location: articulos.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
