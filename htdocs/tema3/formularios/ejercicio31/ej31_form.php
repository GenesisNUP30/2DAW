<form action="ej31.php" method="post">
    Nombre: 
    <input type="text" name="nombre" 
        value="<?= isset($_POST['nombre']) ? $_POST['nombre'] : '' ?>">
    <br><br>

    Apellidos: 
    <input type="text" name="apellidos" 
        value="<?= isset($_POST['apellidos']) ? $_POST['apellidos'] : '' ?>">
    <br><br>

    Sexo: 
    <input type="radio" name="sexo" value="m" 
        <?= (isset($_POST['sexo']) && $_POST['sexo'] == 'm') ? 'checked' : '' ?>>Masculino
    <input type="radio" name="sexo" value="f" 
        <?= (isset($_POST['sexo']) && $_POST['sexo'] == 'f') ? 'checked' : '' ?>>Femenino
    <br><br>

    Curso: 
    <select name="curso">
        <option value="">-- Selecciona uno --</option>
        <option value="1" <?= (isset($_POST['curso']) && $_POST['curso'] == '1') ? 'selected' : '' ?>>1º SMR</option>
        <option value="2" <?= (isset($_POST['curso']) && $_POST['curso'] == '2') ? 'selected' : '' ?>>2º SMR</option>
        <option value="3" <?= (isset($_POST['curso']) && $_POST['curso'] == '3') ? 'selected' : '' ?>>1º ASIR</option>
        <option value="4" <?= (isset($_POST['curso']) && $_POST['curso'] == '4') ? 'selected' : '' ?>>2º ASIR</option>
        <option value="5" <?= (isset($_POST['curso']) && $_POST['curso'] == '5') ? 'selected' : '' ?>>1º DAW</option>
        <option value="6" <?= (isset($_POST['curso']) && $_POST['curso'] == '6') ? 'selected' : '' ?>>2º DAW</option>
        <option value="7" <?= (isset($_POST['curso']) && $_POST['curso'] == '7') ? 'selected' : '' ?>>1º DAM</option>
        <option value="8" <?= (isset($_POST['curso']) && $_POST['curso'] == '8') ? 'selected' : '' ?>>2º DAM</option>
    </select>
    <br><br>

    Fecha de nacimiento: 
    <input type="date" name="fecha"
        value="<?= isset($_POST['fecha']) ? $_POST['fecha'] : '' ?>">
    <br><br>

    <button type="submit">Enviar</button>
    <button type="reset">Reset</button>
</form>
