<?php
require_once("db.php");
function loginDB($user, $contrasena, $db){
    
    $con=$contrasena;

    $res = mysqli_query($db, "SELECT apellido FROM ejemplo WHERE nombre='{$user}'");
    if ($res){
        if (mysqli_num_rows($res)>0){
            $tabla=mysqli_fetch_all($res, MYSQLI_ASSOC);
        }
        else
            $tabla=[];
        mysqli_free_result($res);
    }
    else{
        $tabla=false;
        $salida=false;
    }
    
    if ($tabla!=false){
        if (password_verify($con, $tabla[0]['apellido']))
            $salida=true;
        else 
            $salida=false;
    }
    else 
        $salida=false;
    
    
    
    return $salida;
}

// function crearTablaVacia($db, $nombreTabla) {
//     $sql = "CREATE TABLE $nombreTabla (
//         id INT(11) AUTO_INCREMENT PRIMARY KEY,
//         columna1 VARCHAR(255),
//         columna2 INT(11),
//         columna3 TEXT
//     )";

//     if (mysqli_query($db, $sql)) {
//         return true; // La tabla se creó exitosamente
//     } else {
//         return false; // Ocurrió un error al crear la tabla
//     }
// }
?>