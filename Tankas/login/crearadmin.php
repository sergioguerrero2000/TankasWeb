<?php

    require_once '../conexionBD/conexion.php';

    $username = 'admin';
    $password = 'Tankas_Huelva';  // Contraseña en texto plano (solo para inserción inicial)
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo "Administrador creado exitosamente.";
    } else {
        echo "Error al crear el administrador.";
    }
    
    $stmt->close();
    $conn->close();
    ?>
    
?>