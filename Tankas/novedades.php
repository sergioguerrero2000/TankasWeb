<?php
require_once 'sesion.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require_once 'conexionBD/conexion.php';

// Obtener novedades
$sql = "SELECT id, titulo, descripcion, imagen FROM novedades";
$result = $conn->query($sql);
// Obtener el mensaje de la sesión si existe
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$message_type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : '';

// Limpiar el mensaje de la sesión
unset($_SESSION['message']);
unset($_SESSION['message_type']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novedades</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Favicon -->
    <link href="img/logoTankas.png" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.min.css" rel="stylesheet">
    <!-- Alertify -->
    <link href="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css" rel="stylesheet"/>
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
        <h1>Gestión de Novedades</h1>

        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#añadirNovedadModal">Añadir Nueva Novedad</button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Descripción</th>
                        <th>Imagen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['titulo'] . "</td>";
                            echo "<td>" . $row['descripcion'] . "</td>";
                            echo "<td><img src='img/" . $row['imagen'] . "' alt='" . $row['titulo'] . "' style='width: 100px;'></td>";
                            echo "<td>";
                            echo "<button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editarNovedadModal' data-id='" . $row['id'] . "' data-titulo='" . $row['titulo'] . "' data-descripcion='" . $row['descripcion'] . "' data-imagen='" . $row['imagen'] ."'>Editar</button> ";
                            echo "<a href='borrar_novedad.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Borrar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No hay registros</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modales para Añadir y Editar Novedades -->
    <!-- Modal Añadir Novedad -->
    <div class="modal fade" id="añadirNovedadModal" tabindex="-1" aria-labelledby="añadirNovedadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="añadir_novedad.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="añadirNovedadModalLabel">Añadir Nueva Novedad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombre">Titulo</label>
                            <input type="text" name="titulo" id="titulo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="imagen">Imagen</label>
                            <input type="file" name="imagen" id="imagen" class="form-control-file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Añadir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Novedad -->
    <div class="modal fade" id="editarNovedadModal" tabindex="-1" aria-labelledby="editarNovedadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="editar_novedad.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarNovedadModalLabel">Editar Novedad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editar-id">
                        <div class="form-group">
                            <label for="editar-nombre">Titulo</label>
                            <input type="text" name="titulo" id="editar-titulo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="editar-descripcion">Descripción</label>
                            <textarea name="descripcion" id="editar-descripcion" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editar-imagen">Imagen</label>
                            <input type="file" name="imagen" id="editar-imagen" class="form-control-file">
                            <small class="form-text text-muted">Dejar en blanco si no desea cambiar la imagen</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>

    <!-- Script para pasar datos al modal de edición -->
    <script>
        $('#editarNovedadModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var titulo = button.data('titulo');
            var descripcion = button.data('descripcion');
            var imagen = button.data('imagen');

            var modal = $(this);
            modal.find('#editar-id').val(id);
            modal.find('#editar-titulo').val(titulo);
            modal.find('#editar-descripcion').val(descripcion);
        });
        // Mostrar el mensaje si existe
        var message = "<?php echo $message; ?>";
        var messageType = "<?php echo $message_type; ?>";

        if (message) {
            if (messageType === 'success') {
                alertify.success(message);
            } else if (messageType === 'error') {
                alertify.error(message);
            }
        }
    </script>
</body>
</html>
