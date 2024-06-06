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
    } else {
        $_SESSION['message'] = "Error al eliminar la novedad.";
    }

    $stmt->close();
    $conn->close();

    header("Location: novedades.php");
} else {
    $_SESSION['message'] = "ID de artículo no válido.";
    header("Location: articulos.php");
}
?>