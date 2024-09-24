<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    require_once '../conexionBD/conexion.php';

    // Recoger datos del formulario
    $name = $_POST['name'];
    $email = $_POST['email'];
    $telefono = $_POST["phone"];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = $_POST['people'];
    $cake = $_POST['cake'];
    $cake_design = '';

    // Validar y formatear fecha
    $date = date('Y-m-d', strtotime($date));

    // Validar y formatear hora
    $time = date('H:i:s', strtotime($time));

    // Manejo de archivo subido
    if (!empty($_FILES['cake_design']['name'])) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($_FILES["cake_design"]["name"]);
        if (move_uploaded_file($_FILES["cake_design"]["tmp_name"], $target_file)) {
            $cake_design = $target_file;
        } else {
            echo "Error subiendo el archivo.";
            exit;
        }
    }

    // Insertar datos en la base de datos
    $sql = "INSERT INTO reservas (nombre, email, telefono, fecha, hora, personas, tarta, diseño_tarta)
            VALUES ('$name', '$email','$telefono' '$date', '$time', '$people', '$cake', '$cake_design')";

    if ($conn->query($sql) === TRUE) {
        // Configurar PHPMailer
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


            // Correo al cliente
            $mail->setFrom('tankaslaboutiquedelcafe@gmail.com', 'Tanka`s La boutique del cafe');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmación de Reserva';
            $message_client = "Gracias por tu reserva, $name. Aquí tienes los detalles de tu reserva:
            <br>Telefono: $telefono
            <br>Fecha: $date
            <br>Hora: $time
            <br>Personas: $people
            <br>Tarta: $cake";
            if ($cake_design) {
                $message_client .= "<br>Diseño de la tarta: " . "http://" . $_SERVER['HTTP_HOST'] . "/img/" . $cake_design;
            }
            $mail->Body = $message_client;
            $mail->send();

            // Limpiar destinatarios y adjuntos
            $mail->clearAddresses();
            $mail->clearAttachments();

            // Correo al administrador
            $mail->addAddress('admin@tankas.es');
            $mail->Subject = 'Nueva Reserva Realizada';
            $message_admin = "Se ha realizado una nueva reserva. Aquí tienes los detalles:
            <br>Nombre: $name
            <br>Correo: $email
            <br>Fecha: $date
            <br>Hora: $time
            <br>Personas: $people
            <br>Tarta: $cake";
            if ($cake_design) {
                $message_admin .= "<br>Diseño de la tarta: " . "https://" . $_SERVER['HTTP_HOST'] . "/img/" . $cake_design;
            }
            $mail->Body = $message_admin;
            $mail->send();

            header("Location: ../reservation.html?mensaje=Reserva realizada con éxito.&mensaje_tipo=success");
        } catch (Exception $e) {
            header("Location: ../reservation.html?mensaje=Error al enviar el correo: {$mail->ErrorInfo}.&mensaje_tipo=error");
        }
    } else {
        header("Location: ../reservation.html?mensaje=Error al insertar los datos en la base de datos.&mensaje_tipo=error");
    }

    $conn->close();
}
?>

