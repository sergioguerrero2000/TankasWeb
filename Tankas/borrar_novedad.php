<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require 'conexionBD/conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    echo('<script> confirm("JSDKFLJSD")</script>');
    $sql = "DELETE FROM novedades WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Novedad eliminada con éxito.";
        $_SESSION['message_type'] = "success";
        
    } else {
        $_SESSION['message'] = "Error al eliminar la novedad.";
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();
    $_SESSION['message'] = "Novedad eliminada con éxito.";
    $_SESSION['message_type'] = "success";
    header("Location: novedades.php");
} else {
    $_SESSION['message'] = "ID de artículo no válido.";
    $_SESSION['message_type'] = "error";
    header("Location: novedades.php");
}
?>