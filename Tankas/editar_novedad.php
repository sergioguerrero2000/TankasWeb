<?php
require_once 'conexionBD/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    if (!empty($_FILES["imagen"]["name"])) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["imagen"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
                $sql = "UPDATE novedades SET titulo=?, descripcion=?, imagen=? WHERE id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $titulo, $descripcion, $_FILES["imagen"]["name"], $id);

                if ($stmt->execute()) {
                    header("Location: novedades.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Hubo un error subiendo la imagen.";
            }
        } else {
            echo "El archivo no es una imagen.";
        }
    } else {
        $sql = "UPDATE novedades SET titulo=?, descripcion=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $titulo, $descripcion, $id);

        if ($stmt->execute()) {
            header("Location: novedades.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
} else {
    header("Location: novedades.php");
    exit();
}
?>

