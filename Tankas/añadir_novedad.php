<?php
require_once 'conexionBD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['nombre'];
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
                header("Location: novedades.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Hubo un error subiendo la imagen.";
        }
    } else {
        echo "El archivo no es una imagen.";
    }
} else {
    header("Location: novedades.php");
    exit();
}
?>
