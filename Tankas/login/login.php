<?php
session_start();
require_once '../conexionBD/conexion.php';

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);

    if (empty($username) || empty($password)) {
        die('Por favor, completa todos los campos.');
    }

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin'] = $username;
            $_SESSION['admin_id'] = $id;
            $_SESSION['loggedin'] = true;
            $_SESSION['LAST_ACTIVITY'] = time();
            header("Location: ../admin.php");
            exit();
        } else {
            header("Location: ../login.html?error=1");
        }
    } else {
        header("Location: ../login.html?error=1");
    }

    $stmt->close();
    $conn->close();
}
?>
