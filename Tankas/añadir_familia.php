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

    if (!empty($nombre)) {

        $stmt = $conn->prepare("INSERT INTO familia (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Familia añadida con éxito.";
            $_SESSION['message_type'] = "success";
            header("Location: familia.php");
            exit();
        } else {
            $_SESSION['message'] = "Error al añadir.";
            $_SESSION['message_type'] = "error";
            header("Location: familia.php");
        }

        $stmt->close();
        $conn->close();
    }
}
?>
