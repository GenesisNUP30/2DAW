<?php

include "blade.php";

// Vamos a usar el patron Singleton para obtener el objeto de Blade
$blade = TemplateBlade::GetInstance();

echo $blade->render('03saludos', [
    'nombres' => ['Pepe', 'Juan', 'Maria', 'Andres', 'Pedro', 'Ana', 'Graciela']
]);

