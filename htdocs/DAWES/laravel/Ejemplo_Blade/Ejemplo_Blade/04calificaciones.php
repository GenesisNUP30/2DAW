<?php
/**
 * Ejemplo de plantilla que contiene errores. 04calificaciones.blade.php tiene error
 */

include "blade.php";

// Vamos a usar el patron Singleton para obtener el objeto de Blade
$blade = TemplateBlade::GetInstance();

$calificaciones=[
    ['nombre'=>'Juan', 'calificacion'=>5],
    ['nombre'=>'Maria', 'calificacion'=>7],
    ['nombre'=>'Carmen', 'calificacion'=>3],
    ['nombre'=>'Andrea', 'calificacion'=>4],
    ['nombre'=>'Luis', 'calificacion'=>2],
    ['nombre'=>'Manuel', 'calificacion'=>9],
    ['nombre'=>'Pilar', 'calificacion'=>7],
    ['nombre'=>'David', 'calificacion'=>3],
];


echo $blade->render($plantilla, [
    'calificaciones' => $calificaciones
]);
