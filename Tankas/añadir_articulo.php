<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require_once 'conexionBD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $disponible = $_POST['disponible'];
    $familia = $_POST['familia'];

    if (!empty($nombre) && !empty($precio) && !empty($disponible) && !empty($familia)) {

        $stmt = $conn->prepare("INSERT INTO articulos (nombre, precio, disponible, id_familia) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $precio, $disponible, $familia);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Artículo añadido con éxito.";
            $_SESSION['message_type'] = "success";
            header("Location: articulos.php");
            exit();
        } else {
            $_SESSION['message'] = "Error al añadir.";
            $_SESSION['message_type'] = "error";
            header("Location: articulos.php");
        }

        $stmt->close();
        $conn->close();
    }
}
?>
