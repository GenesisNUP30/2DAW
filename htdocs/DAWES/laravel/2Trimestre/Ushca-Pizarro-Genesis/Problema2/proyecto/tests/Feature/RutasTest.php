<?php

use App\Models\User;
use App\Models\Tarea;
use App\Models\Cliente;
use App\Models\Cuota;
use App\Models\Factura;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'tipo' => 'administrador',
    ]);

    $this->operario = User::factory()->create([
        'tipo' => 'operario',
    ]);

    $this->tarea = Tarea::factory()->create();
    $this->cliente = Cliente::factory()->create();
    $this->cuota = Cuota::factory()->create();
    $this->factura = Factura::factory()->create();
});

it('todas las rutas de admin responden', function ($method, $url, $expectedStatus) {

    $url = str_replace('{tarea}', $this->tarea->id, $url);
    $url = str_replace('{cliente}', $this->cliente->id, $url);
    $url = str_replace('{cuota}', $this->cuota->id, $url);
    $url = str_replace('{factura}', $this->factura->id, $url);

    $response = $this->actingAs($this->admin)
        ->$method($url);

    expect($response->status())->toBe($expectedStatus);

})->with([
    ['get', '/dashboard', 200],
    ['get', '/perfil', 200],

    ['get', '/tareas', 200],
    ['get', '/tareas/crear', 200],
    ['get', '/tareas/{tarea}', 200],
    ['get', '/tareas/{tarea}/editar', 200],

    ['get', '/clientes', 200],
    ['get', '/clientes/crear', 200],
    ['get', '/clientes/{cliente}', 200],

    ['get', '/cuotas', 200],
    ['get', '/cuotas/papelera', 200],
    ['get', '/cuotas/remesa', 200],

    ['get', '/empleados', 200],
    ['get', '/empleados/crear', 200],
]);

it('operario no puede acceder a rutas de admin', function () {

    $response = $this->actingAs($this->operario)
        ->get('/clientes');

    expect($response->status())->toBe(403);
});

it('rutas públicas funcionan', function () {

    $this->get('/incidencia')
        ->assertStatus(200);
});