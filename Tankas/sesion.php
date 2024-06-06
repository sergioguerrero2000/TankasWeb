<?php
session_start();

define('SESSION_EXPIRATION_TIME', 1800); // 1800 segundos = 30 minutos

// Función para verificar la expiración de la sesión
function checkSessionExpiration() {
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_EXPIRATION_TIME)) {
        // La última solicitud fue hace más de 30 minutos
        session_unset(); // Elimina las variables de sesión
        session_destroy(); // Destruye la sesión
        header("Location: login/login.html"); // Redirige a la página de inicio de sesión con un mensaje
        exit();
    }
    // Actualiza la marca de tiempo de la última actividad
    $_SESSION['LAST_ACTIVITY'] = time();
}

// Llamada a la función para verificar la expiración de la sesión
checkSessionExpiration();
?>
