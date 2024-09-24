<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// Configuración del servidor SMTP
$mail = new PHPMailer(true);

try {
    //Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // Coloca aquí el servidor SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sergioguevi10@gmail.com'; // Coloca aquí tu dirección de correo electrónico
    $mail->Password   = 'Messi2011'; // Coloca aquí tu contraseña
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587; // Puedes cambiar el puerto SMTP si es necesario

    // Destinatario y remitente
    $mail->setFrom('sergioguevi10@gmail.com', 'Tankas');
    $mail->addAddress($_POST['email']); // Coloca aquí la dirección de correo electrónico del destinatario

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = $_POST['subject'];
    $mail->Body    = "Nombre: {$_POST['name']}<br>Email: {$_POST['email']}<br>Mensaje: {$_POST['message']}";

    // Envío del correo electrónico
    $mail->send();
    echo 'Correo enviado correctamente';
} catch (Exception $e) {
    echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
}
?>