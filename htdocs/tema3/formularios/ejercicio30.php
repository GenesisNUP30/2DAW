<?php
$error = "";
$nombre = "";
$apellidos = "";
$sexo = "";
$curso = "";
$fecha = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : "";
    $curso = $_POST["curso"];
    $fecha = $_POST["fecha"];

    if ($nombre == "") {
        $error .= "El nombre es obligatorio<br>";
    }
    if ($apellidos == "") {
        $error .= "Los apellidos son obligatorios<br>";
    }
    if ($sexo == "") {
        $error .= "El sexo es obligatorio<br>";
    }
    if ($curso == "") {
        $error .= "El curso es obligatorio<br>";
    }
    if ($fecha == "") {
        $error .= "La fecha de nacimiento es obligatoria<br>";
    }

    if ($error == "") {
        echo "<h2>Datos enviados</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Valor</th></tr>";
        echo "<tr><td>Nombre</td><td>$nombre</td></tr>";
        echo "<tr><td>Apellidos</td><td>$apellidos</td></tr>";
        echo "<tr><td>Sexo</td><td>" . ($sexo == "m" ? "Masculino" : "Femenino") . "</td></tr>";
        echo "<tr><td>Curso</td><td>";
        switch ($curso) {
            case "1":
                echo "1º SMR";
                break;
            case "2":
                echo "2º SMR";
                break;
            case "3":
                echo "1º ASIR";
                break;
            case "4":
                echo "2º ASIR";
                break;
            case "5":
                echo "1º DAW";
                break;
            case "6":
                echo "2º DAW";
                break;
            case "7":
                echo "1º DAM";
                break;
            case "8":
                echo "2º DAM";
                break;
        }
        echo "</td></tr>";
        echo "<tr><td>Fecha de nacimiento</td><td>$fecha</td></tr>";
        echo "</table>";
        echo '<br><a href="ejercicio30.php">Volver al formulario</a>';
        exit(); 
    }
}

if ($error != "") {
    echo '<p style="color: red">' . $error . '</p>';
}
?>

<form action="ejercicio30.php" method="post">
    Nombre: <input type="text" name="nombre" value="<?=isset($nombre) ? $nombre : "" ?>"><br><br>
    Apellidos: <input type="text" name="apellidos" value="<?=isset($apellidos) ? $apellidos : "" ?>"><br><br>
    Sexo: <input type="radio" name="sexo" value="m" <?= $sexo == "m" ? "checked" : "" ?>>Masculino
    <input type="radio" name="sexo" value="f" <?= $sexo == "f" ? "checked" : "" ?>>Femenino<br><br>
    Curso:
    <select name="curso">
        <option value="" <?= $curso == "" ? "selected" : "" ?>>-- Selecciona uno --</option>
        <option value="1" <?= $curso == "1" ? "selected" : "" ?>>1º SMR</option>
        <option value="2" <?= $curso == "2" ? "selected" : "" ?>>2º SMR</option>
        <option value="3" <?= $curso == "3" ? "selected" : "" ?>>1º ASIR</option>
        <option value="4" <?= $curso == "4" ? "selected" : "" ?>>2º ASIR</option>
        <option value="5" <?= $curso == "5" ? "selected" : "" ?>>1º DAW</option>
        <option value="6" <?= $curso == "6" ? "selected" : "" ?>>2º DAW</option>
        <option value="7" <?= $curso == "7" ? "selected" : "" ?>>1º DAM</option>
        <option value="8" <?= $curso == "8" ? "selected" : "" ?>>2º DAM</option>
    </select><br><br>
    Fecha de nacimiento: <input type="date" name="fecha" value="<?=isset($fecha) ? $fecha : ""?>"><br><br>
    <input type="submit" value="Enviar">