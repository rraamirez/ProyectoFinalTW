<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar los datos del formulario de registro
    $nombre = htmlentities($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $apellidos = htmlentities($_POST['apellidos'], ENT_QUOTES, 'UTF-8');
    $email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
    $clave = htmlentities($_POST['clave'], ENT_QUOTES, 'UTF-8');
    $usuario = htmlentities($_POST['usuario'], ENT_QUOTES, 'UTF-8');
    
    // Comprueba si se ha cargado un archivo
    if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
        // Lee el contenido del archivo y lo convierte en un string binario
        $foto = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
    } else {
        $defaultPath = "../img/foto_base.png";
        if (file_exists($defaultPath)) {
            $foto = base64_encode(file_get_contents($defaultPath));
        } else {
            echo "El archivo de imagen predeterminado no se encuentra en la ruta especificada.";
            $foto = null;
        }
    }
    $rol = 'colaborador';

    // Aquí puedes realizar la validación de los datos y otras verificaciones necesarias antes de agregar el usuario a la base de datos

    // Realizar la conexión a la base de datos
    require_once('../model/bd.php'); // Archivo que contiene la clase Conexion
    $conexion = new Conexion();
    $conexion->conectar();


    // Comprobar si el nombre de usuario ya existe
    if ($conexion->usuarioExiste($usuario)) {
        echo 'Error: El nombre de usuario ya existe.';
    } else {
        // Si el nombre de usuario no existe, puedes continuar con el registro del usuario
        // Llamar al método DBaddUsuario() para agregar el usuario a la base de datos
        $idUsuario = $conexion->DBaddUsuario($nombre, $apellidos, $email, $foto, $clave, $usuario, $rol);
        if ($idUsuario) {
            $conexion->addLog($idUsuario, date("Y-m-d H:i:s"), "INFO: Se ha añadido el usuario {$conexion->getUsuario($idUsuario)}");
            // Usuario registrado exitosamente
            echo 'Usuario registrado con ID: ' . $idUsuario;
            echo '<script>alert("Usuario creado correctamente");</script>';
            header('Location: ../index.php');
        } else {
            // Error al registrar el usuario
            echo 'Error al registrar el usuario.';
        }
    }

    // Cerrar la conexión a la base de datos
    $conexion->desconectar();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        h2 {
            text-align: center;
        }

        body {
            padding: 20px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <h2>Registro de Usuario</h2>
    <form method="POST" action="register.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" class="form-control" name="apellidos" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" class="form-control" name="foto">
        </div>

        <div class="form-group">
            <label for="clave">Contraseña:</label>
            <input type="password" class="form-control" name="clave" required>
        </div>

        <div class="form-group">
            <label for="usuario">Usuario:</label>
            <input type="text" class="form-control" name="usuario" required>
        </div>
        <button type="submit" class="btn btn-primary" formaction="register.php">Registrar</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>