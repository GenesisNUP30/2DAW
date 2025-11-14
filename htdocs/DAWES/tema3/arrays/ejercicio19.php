<?php
function VerificaDNI(string $dni): bool {
$nif = strtoupper($dni);
	$nifRegEx = '/^[0-9]{8}[A-Z]$/';
	$letras = "TRWAGMYFPDXBNJZSQVHLCKE";

	if (preg_match($nifRegEx, $nif)) return ($letras[(substr($nif, 0, 8) % 23)] == $nif[8]);
	else return false;
}

$dni = "123456789";


if (VerificaDNI($dni)) {
    echo "El DNI " . $dni . " es válido.<br>";
} else {
    echo "El DNI " .$dni . " no es válido.<br>";
}


$dni1 = "12345678";
echo $dni1 % 23 . "<br>";
$dni2 = "12345678Z";

if (VerificaDNI($dni2)) {
    echo "El DNI " . $dni2 . " es válido.<br>";
} else {
    echo "El DNI " .$dni2 . " no es válido.<br>";
}
