<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require_once 'conexionBD/conexion.php';

$sql = "SELECT id, nombre FROM familia";
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
    <title>Artículos</title>
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
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Gestión de Familias</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#añadirFamiliaModal">Añadir Nuevo Familia</button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['nombre'] . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editarFamiliaModal' data-id='" . $row['id'] . "' data-nombre='" . $row['nombre'] . "'>Editar</button> ";
                            echo "<a href='borrar_familia.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Borrar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay registros</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Añadir Artículo -->
<div class="modal fade" id="añadirFamiliaModal" tabindex="-1" aria-labelledby="añadirFamiliaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="añadir_familia.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirFamiliaModalLabel">Añadir Nueva Familia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
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

<!-- Modal Editar Artículo -->
<div class="modal fade" id="editarFamiliaModal" tabindex="-1" aria-labelledby="editarFamiliaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="editar_familia.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarFamiliaModalLabel">Editar Familia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar-id">
                    <div class="form-group">
                        <label for="editar-nombre">Nombre</label>
                        <input type="text" name="nombre" id="editar-nombre" class="form-control" required>
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


    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
    <script>
    $('#editarFamiliaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var id = button.data('id'); 
        var nombre = button.data('nombre'); 

        var modal = $(this);
        modal.find('#editar-id').val(id);
        modal.find('#editar-nombre').val(nombre);
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
