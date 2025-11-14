<?php
include "blade.php";

// Vamos a usar el patron Singleton para obtener el objeto de Blade
$blade= TemplateBlade::GetInstance();

// Podríamos haber hecho 
// $blade=new Blade('carpte_vistas', 'carpeta_cache');

echo $blade->render('01holamundo');