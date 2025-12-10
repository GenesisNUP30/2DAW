<?php
header('Content-Type: application/json');

include 'conexion.php';

$cp = $_GET['cp'];
$nh = $_GET['habitantes'];

$sql = "UPDATE poblacion SET habitantes = '$nh' WHERE codigo_postal = '$cp'";


