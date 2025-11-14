<form action="ejemplo_f1.php" method="post">
        Nombre: <input type="text" name="nombre" 
        value="<?=isset($_POST['nombre']) ? $_POST['nombre'] : '' ?>"/>
        <br>
        Apellidos: <input id="apellidos" type="text" name="apellidos" 
        value="<?= isset($_POST['apellidos']) ? $_POST['apellidos'] : '' ?>"/>
        <br>

        Edad: <input type="date" name="fecha"
        value="<?= isset($_POST['fecha']) ? $_POST['fecha'] : '' ?>"/>
        <br>
        <button type="submit">Enviar</button>
        <button type="reset">Reset</button>
    </form>