<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la base de datos
    require_once '../conexionBD/conexion.php';
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $comentario = $_POST['comentario'];

    // Manejar la carga del archivo
    $target_dir = "../img/";
    $target_file = $target_dir . basename($_FILES["curriculum"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar si el archivo es un PDF
    if($fileType != "pdf") {
        echo "Solo se permiten archivos PDF.";
        exit;
    }

// Mover el archivo al directorio de destino
if (move_uploaded_file($_FILES["curriculum"]["tmp_name"], $target_file)) {
    // Insertar los datos en la base de datos
    $sql = "INSERT INTO curriculums (nombre, email, comentario, curriculum_path) VALUES ('$nombre', '$email', '$comentario', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'tankaslaboutiquedelcafe@gmail.com'; // Tu correo Gmail
            $mail->Password = 'objk ztba bkjs dpjv'; // Tu contraseña de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Enviar correo electrónico al cliente
            $mail->setFrom('admin@tankas.com', 'Tanka`s La Boutique del Cafe');
            $mail->addAddress($email, $nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmacion de envio de curriculum';
            $mail->Body = "Hola $nombre,<br><br>Tu currículum ha sido enviado exitosamente. Gracias por tu interés.<br><br>Saludos,<br>La Boutique del Café";

            $mail->send();

            // Enviar correo electrónico al administrador
            $mail->clearAddresses();
            $mail->addAddress('tankaslaboutiquedelcafe@gmail.com');
            $mail->Subject = 'Nuevo curriculum recibido';
            $mail->Body = "Se ha recibido un nuevo currículum.<br><br>Nombre: $nombre<br>Correo: $email<br>Comentario: $comentario<br>Curriculum: <a href='http://tankas.es/$target_file'>Descargar</a>";

            $mail->send();

            echo "Currículum enviado exitosamente.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Hubo un error al subir tu archivo.";
}

// Cerrar la conexión
$conn->close();
}
?>