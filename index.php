<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once('view/funcHTML.php');
require_once('model/bd.php');


$db = new Conexion();
$db->conectar();

HTMLinicio("Inicio");
HTMLheader();
HTMLnav();

HTMLmainContentStart();
// Aquí va tu contenido principal
HTMLasideStart();
HTMLaside();
HTMLasideEnd();

HTMLfooter();
HTMLfin();

if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    // Borrar el mensaje una vez que se ha mostrado
    unset($_SESSION['message']);
    session_abort();
}

?>