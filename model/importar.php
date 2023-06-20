// importar.php
<?php
require_once('dbcredencialesRaul.php');

$backup_file = $_FILES['backupFile']['tmp_name'];

// Comando para importar la base de datos
$command = "mysql --user=" . DB_USER . " --password=" . DB_PASSWD . " --host=" . DB_HOST . " " . DB_DATABASE . " < {$backup_file}";

// Ejecutar el comando
system($command, $output);

// Comprobar si se ha producido un error
if($output == 0) {
    $_SESSION['message'] = 'Importación realizada con éxito.';
} else {
    $_SESSION['message'] = 'Ha ocurrido un error durante la importación.';
}

header("Location: ../index.php");

?>
