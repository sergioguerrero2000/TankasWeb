<?php
require_once 'C:\xampp\htdocs\Tankas\Tankas\conexionBD\conexion.php';
    
    $email = $_POST['email'];
    // Destinatario del correo electrónico
    $to = $email;

    // Cuerpo del correo electrónico
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: TANKAS, LA BOUTIQUE DEL CAFE. <tankaslaboutiquedelcafe@email.com>" . "\r\n"; // Cambia esto por tu dirección de correo electrónico
    
    // Contenido HTML del correo electrónico
    $message = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Boletín Informativo</title>
        <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: "C:\xampp\htdocs\Tankas\Tankas\img\portada2.jfif";
            background-size: cover;
            background-position: center;
            height: 100%;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7);
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }
        .logo {
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 20px;
            color: #777;
        }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="http://localhost/Tankas/Tankas/img/portada.jpg" alt="Logo" class="logo">
            <h1>¡Gracias por inscribirte!</h1>
            <p>Te mantendremos informado con las últimas novedades.</p>
            <div class="footer">
                <p>No responder a este mensaje. Para más información, visita nuestro sitio web.</p>
            </div>
        </div>
    </body>
    </html>
    ';

    // Envío del correo electrónico
    mail($to, "Boletín Informativo", $message, $headers);

    $sql = "INSERT INTO emails_clientes (email) VALUES ('$email')";
    $result = $conn->query($sql);

?>
