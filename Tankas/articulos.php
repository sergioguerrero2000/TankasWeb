<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

require_once 'conexionBD/conexion.php';

$sql = "SELECT id, nombre, precio, disponible, id_familia FROM articulos";
$result = $conn->query($sql);

// Obtener familias
$sql_familias = "SELECT id, nombre FROM familia";
$result_familias = $conn->query($sql_familias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artículos</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h1>Gestión de Artículos</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#añadirArticuloModal">Añadir Nuevo Artículo</button>
        <!-- Formulario de búsqueda -->
        <input type="text" id="search" class="form-control mb-3" placeholder="Buscar artículo">

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Disponible</th>
                        <th>Familia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="articulosTable">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['nombre'] . "</td>";
                            echo "<td>" . $row['precio'] . "</td>";
                            echo "<td>" . $row['disponible'] . "</td>";
                            echo "<td>" . $row['id_familia'] . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editarArticuloModal' data-id='" . $row['id'] . "' data-nombre='" . $row['nombre'] . "' data-precio='" . $row['precio'] . "' data-disponible='" . $row['disponible'] . "' data-familia='" . $row['id_familia'] ."'>Editar</button> ";
                            echo "<a href='borrar_articulo.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Borrar</a>";
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
<div class="modal fade" id="añadirArticuloModal" tabindex="-1" aria-labelledby="añadirArticuloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="añadir_articulo.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="añadirArticuloModalLabel">Añadir Nuevo Artículo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" name="precio" id="precio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="disponible">Disponible</label>
                        <input type="text" name="disponible" id="disponible" class="form-control" required>
                    </div>
                    <div class="form-group">
                            <label for="familia">Familia</label>
                            <select name="familia" id="familia" class="form-control" required>
                                <?php
                                if ($result_familias->num_rows > 0) {
                                    while ($familia = $result_familias->fetch_assoc()) {
                                        echo "<option value='" . $familia['id'] . "'>" . $familia['nombre'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay familias disponibles</option>";
                                }
                                ?>
                            </select>
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
<div class="modal fade" id="editarArticuloModal" tabindex="-1" aria-labelledby="editarArticuloModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="editar_articulo.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarArticuloModalLabel">Editar Artículo</h5>
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
                    <div class="form-group">
                        <label for="editar-precio">Precio</label>
                        <input type="text" name="precio" id="editar-precio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editar-disponible">Disponible</label>
                        <input type="text" name="disponible" id="editar-disponible" class="form-control" required>
                    </div>
                    <div class="form-group">
                            <label for="editar-familia">Familia</label>
                            <select name="familia" id="editar-familia" class="form-control" required>
                                <?php
                                $result_familias->data_seek(0);
                                if ($result_familias->num_rows > 0) {
                                    while ($familia = $result_familias->fetch_assoc()) {
                                        echo "<option value='" . $familia['id'] . "'>" . $familia['nombre'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>No hay familias disponibles</option>";
                                }
                                ?>
                            </select>
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
    <script>
    $('#editarArticuloModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var id = button.data('id'); 
        var nombre = button.data('nombre'); 
        var precio = button.data('precio');
        var disponible = button.data('disponible');
        var familia = button.data('familia');

        var modal = $(this);
        modal.find('#editar-id').val(id);
        modal.find('#editar-nombre').val(nombre);
        modal.find('#editar-precio').val(precio);
        modal.find('#editar-disponible').val(disponible);
        modal.find('#editar-familia').val(familia);
    });
    $(document).ready(function() {
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#articulosTable tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

</body>
</html>
