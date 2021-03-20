<?php

include "config.php";
include "mysql/query.php";

if(!(isset($_GET['num_matricula'], $_GET['clave']))) {
    die;
}


$id = $_GET['num_matricula'];
$clave = $_GET['clave'];



if(queryCheck($conn,$id,$clave)){
    print_r($_SESSION);
    $_SESSION['loginID'] = $id;
    header("Location: panel.php");
    die();
}
?>