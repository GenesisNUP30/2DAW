<?php
include "blade.php";

// Vamos a usar el patron Singleton para obtener el objeto de Blade
$blade= TemplateBlade::GetInstance();

echo $blade->make('02hola')
           ->with('nombre', 'Pepe')
           ->render();

