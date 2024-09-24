<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require_once 'conexionBD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["imagen"]["tmp_name"]);
    if ($check !== false) {
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO novedades (titulo, descripcion, imagen) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $titulo, $descripcion, $_FILES["imagen"]["name"]);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Novedad añadida con éxito.";
                $_SESSION['message_type'] = "success";
                header("Location: novedades.php");
            } else {
                $_SESSION['message'] = "Error al añadir.";
                $_SESSION['message_type'] = "error";
                header("Location: novedades.php");
            }
            $stmt->close();
            $conn->close();
        } else {
            $_SESSION['message'] = "Error añadir la imagen.";
            $_SESSION['message_type'] = "error";
            header("Location: novedades.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "El archivo no es una imagen.";
        $_SESSION['message_type'] = "error";
        header("Location: novedades.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Novedad añadida con éxito.";
    $_SESSION['message_type'] = "success";
    header("Location: novedades.php");
    exit();
}
?>
