<form action="ej33.php" method="post">
    <label>Número:</label>
    <input type="text" name="numero" 
        value="<?= isset($_POST['numero']) ? $_POST['numero'] : 0 ?>">
    <button type="submit" name="sumar">+1</button>
</form>
