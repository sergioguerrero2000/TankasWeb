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

    if (!empty($id) && !empty($nombre)) {

        $stmt = $conn->prepare("UPDATE familia SET nombre = ? WHERE id = ?");
        $stmt->bind_param("si", $nombre, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Familia editada con éxito.";
            $_SESSION['message_type'] = "success";
            header('Location: familia.php');
            exit();
        } else {
            $_SESSION['message'] = "Error al editar.";
            $_SESSION['message_type'] = "error";
            header('Location: familia.php');
            exit();
        }

        $stmt->close();
        $conn->close();
    }
}
?>
