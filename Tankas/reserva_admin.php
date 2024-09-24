<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

// archivo: get_reservas.php
function obtenerReservas() {
    require_once 'conexionBD/conexion.php';

    // Consultar reservas
    $sql = "SELECT nombre, fecha, hora, tarta, personas, email, telefono FROM reservas";
    $result = $conn->query($sql);

    $reservas = array();

    if ($result->num_rows > 0) {
        // Output de datos de cada fila
        while ($row = $result->fetch_assoc()) {
            $reservas[] = array(
                'title' => $row["nombre"] . " - " . $row["tarta"] . " (" . $row["personas"] . " personas)",
                'start' => $row["fecha"] . "T" . $row["hora"],
                'nombre' => $row["nombre"],
                'fecha' => $row["fecha"],
                'hora' => $row["hora"],
                'tarta' => $row["tarta"],
                'personas' => $row["personas"],
                'email' => $row["email"],
                'telefono' => $row["telefono"]
            );
        }
    }
    $conn->close();

return json_encode($reservas);
}

// Si se hace una petición AJAX para obtener las reservas, devolver el JSON
if (isset($_GET['action']) && $_GET['action'] == 'get_reservas') {
header('Content-Type: application/json');
echo obtenerReservas();
exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Reservas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js'></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Administración</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="articulos.php">Artículos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="familia.php">Familia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="novedades.php">Novedades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reserva_admin.php">Reservas</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div id='calendar'></div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detalles de la Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <span id="modalNombre"></span></p>
                    <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                    <p><strong>Hora:</strong> <span id="modalHora"></span></p>
                    <p><strong>Tarta:</strong> <span id="modalTarta"></span></p>
                    <p><strong>Personas:</strong> <span id="modalPersonas"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
            $('#calendar').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                firstDay: 1, // Para que el lunes sea el primer día de la semana
                editable: false,
                events: function(start, end, timezone, callback) {
                    $.ajax({
                        url: 'reserva_admin.php?action=get_reservas',
                        dataType: 'json',
                        success: function(data) {
                            var events = [];
                            $(data).each(function() {
                                events.push({
                                    title: $(this).attr('title'),
                                    start: $(this).attr('start'),
                                    nombre: $(this).attr('nombre'),
                                    fecha: $(this).attr('fecha'),
                                    hora: $(this).attr('hora'),
                                    tarta: $(this).attr('tarta'),
                                    personas: $(this).attr('personas'),
                                    email: $(this).attr('email')
                                });
                            });
                            callback(events);
                        }
                    });
                },
                eventClick: function(calEvent, jsEvent, view) {
                    // Llenar el modal con los detalles del evento
                    $('#modalNombre').text(calEvent.nombre);
                    $('#modalFecha').text(calEvent.fecha);
                    $('#modalHora').text(calEvent.hora);
                    $('#modalTarta').text(calEvent.tarta);
                    $('#modalPersonas').text(calEvent.personas);
                    $('#modalEmail').text(calEvent.email);
                    // Mostrar el modal
                    $('#eventModal').modal('show');
                }
            });
        });
    </script>
</body>
</html>