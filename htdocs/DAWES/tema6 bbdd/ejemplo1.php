<?php
try {
    $enlace = mysqli_connect("127.0.0.1", "root", "", "provincias");
    //$enlaceObj  = new mysqli("l127.0.0.1", "root", "", "provincias");º
}
catch (Exception $ex) {
    echo "Ha  fallado la conexión <br>";
    echo $ex->getMessage();
    exit;
}

$sql = "SELECT * FROM provincias";
$rs = mysqli_query($enlace, $sql);

$reg  = mysqli_fetch_assoc($rs);

echo "<p>Registro</p><pre>";
print_r($reg);
mysqli_close($enlace);