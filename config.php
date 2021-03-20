<?php
session_start();
$host='127.0.0.1';
$user='client';
$pass='00110011';
$db='Elecciones';
$port=3306;

$conn = new mysqli($host,$user,$pass,$db,$port);

?>