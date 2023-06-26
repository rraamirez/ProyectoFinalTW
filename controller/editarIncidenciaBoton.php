<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../model/bd.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conectar a la base de datos
    $conexion = new Conexion();
    $conexion->conectar();

    $id_incidencia = trim(stripslashes(htmlspecialchars($_POST['id_incidencia'])));
    $incidencia = $conexion->getIncidencia($id_incidencia);

    if(isset($_POST['ini_modify'])){
        $titulo = $incidencia['titulo'];
        $descripcion = $incidencia['descripcion'];
        $ubicacion = $incidencia['ubicacion'];
        $palabras_clave = $incidencia['palabras_clave'];
    }
    else if(isset($_POST['modify'])) {
        // Procesar los datos del formulario de incidencia
        $titulo = trim(stripslashes(htmlspecialchars($_POST['titulo'])));
        $descripcion = trim(stripslashes(htmlspecialchars($_POST['descripcion'])));
        $ubicacion = trim(stripslashes(htmlspecialchars($_POST['ubicacion'])));
        $palabras_clave = trim(stripslashes(htmlspecialchars($_POST['palabras_clave'])));

        // Actualizar la incidencia en la base de datos
        $resultado = $conexion->editarIncidencia($titulo, $descripcion, $ubicacion, $palabras_clave, $id_incidencia);

        $_SESSION['titulo'] = $titulo;
        $_SESSION['descripcion'] = $descripcion;
        $_SESSION['ubicacion'] = $ubicacion;
        $_SESSION['palabras_clave'] = $palabras_clave;

        if ($resultado) {
            header('Location: misIncidencias.php');
        } else {
            // Error al actualizar la incidencia
            echo 'Error al actualizar la incidencia.';
        }
    }
    else if(isset($_POST['upload'])) {
        $titulo = $_SESSION['titulo'] ?? $incidencia['titulo'];
        $descripcion = $_SESSION['descripcion'] ?? $incidencia['descripcion'];
        $ubicacion = $_SESSION['ubicacion'] ?? $incidencia['ubicacion'];
        $palabras_clave = $_SESSION['palabras_clave'] ?? $incidencia['palabras_clave'];

        if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
            $foto = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
            $resultadoFoto = $conexion->addFoto($id_incidencia, $foto);
        }
    }
    // Cerrar la conexión a la base de datos
    $conexion->desconectar();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Editar Incidencia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h2 {
            text-align: center;
        }

        body {
            padding: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <h2>Editar Incidencia</h2>
    <form method="POST" action="editarIncidenciaBoton.php" enctype="multipart/form-data">
        <input type="hidden" name="id_incidencia" value="<?= $_POST['id_incidencia'] ?? '' ?>" />
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" value="<?= $titulo ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <textarea class="form-control" name="descripcion" required><?= $descripcion ?? '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="ubicacion">Ubicación:</label>
            <input type="text" class="form-control" name="ubicacion" value="<?= $ubicacion ?? '' ?>" required>
        </div>

        <div class="form-group">
            <label for="palabras_clave">Palabras Clave:</label>
            <input type="text" class="form-control" name="palabras_clave" value="<?= $palabras_clave ?? '' ?>" required>
        </div>

        <button type="submit" name="modify" class="btn btn-primary">Actualizar Incidencia</button>
        
        <div class="form-group">
            <label for="foto">Selecciona una foto:</label>
            <div class="input-group">
                <input type="file" class="form-control" name="foto">
                <div class="input-group-append">
                    <button type="submit" name="upload" class="btn btn-secondary">Subir Foto</button>
                </div>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>