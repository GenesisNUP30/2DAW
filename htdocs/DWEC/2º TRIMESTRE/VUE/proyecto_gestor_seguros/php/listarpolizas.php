<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Incluimos la conexión
include "conexion.php";

// Seleccionamos campos de póliza y el nombre combinado del cliente
$sql = "SELECT 
            p.id, 
            p.cliente_id, 
            p.numero_poliza, 
            p.importe_total, 
            p.fecha, 
            p.estado, 
            p.observaciones,
            CASE 
                WHEN c.tipo_cliente = 'Empresa' THEN c.nombre
                ELSE CONCAT(c.nombre, ' ', IFNULL(c.apellidos, ''))
            END AS nombre_cliente
        FROM polizas p
        INNER JOIN clientes c ON p.cliente_id = c.id
        ORDER BY p.fecha DESC";
        
$resultado = $conexion->query($sql);

$polizas = [];

if ($resultado && $resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $polizas[] = $fila;
    }
    echo json_encode([
        "status" => true,
        "data" => $polizas
    ]);
} else {
    echo json_encode([
        "status" => false,
        "mensaje" => "No hay pólizas registradas",
        "data" => []
    ]);
}