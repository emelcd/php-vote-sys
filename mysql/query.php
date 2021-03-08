<?php

function errorCheck($conn) {
    if($conn->error){
        $error = $conn->error;
        $n_error = $conn->errno;
        header("Location: error.php?error=$error&n_error=$n_error");
        die;
    }
}

function getUserState($conn, $id) {
    $sql_get_user_info = "SELECT id, Nombre, Apellido1, Apellido2, TIMESTAMPDIFF(YEAR,FecNacimiento,CURDATE()) edad, Sexo, Foto, Propuesta FROM elecciones.alumnos WHERE id=$id;";
    $res = $conn->query($sql_get_user_info);
    errorCheck($conn);
    $sql_get_user_voto = "SELECT id, Candidato, Fecha_hora FROM elecciones.votaciones WHERE id=$id;";
    $checkV = $conn->query($sql_get_user_voto);
    errorCheck($conn);
    $sql_get_user_candidato = "SELECT id, Num_Votos FROM elecciones.candidatos WHERE id=$id;";
    $checkC = $conn->query($sql_get_user_candidato);
    errorCheck($conn);
    return array($res, $checkV, $checkC);
}

function getGlobalStates($conn){
    $sql_get_global = "SELECT id, Num_Votos FROM elecciones.candidatos ORDER BY Num_Votos DESC; ";
    $res = $conn->query($sql_get_global);
    return $res->fetch_all();
}


function queryCheck($conn, $id, $clave) {
    $sql_q = "SELECT * FROM usuarios WHERE id=$id AND Clave=$clave;";
    $conn->query($sql_q);
    print_r($sql_q);
    errorCheck($conn);
    if($conn->affected_rows > 0){
        return true;
    } 
    header("Location: index.php?banner='No se encuentra el usuario'");
}

?>