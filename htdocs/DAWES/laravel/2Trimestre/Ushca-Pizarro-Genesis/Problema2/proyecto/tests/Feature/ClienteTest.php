<?php

use App\Models\User;
use App\Models\Cliente;
use App\Models\Pais;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function admin()
{
    return User::factory()->create([
        'tipo' => 'administrador'
    ]);
}

function operario()
{
    return User::factory()->create([
        'tipo' => 'operario'
    ]);
}

function pais()
{
    return Pais::factory()->create([
        'iso2' => 'ES',
        'iso_moneda' => 'EUR'
    ]);
}

/*
|--------------------------------------------------------------------------
| LISTADO
|--------------------------------------------------------------------------
*/

test('un admin puede ver listado de clientes', function () {
    $response = $this->actingAs(admin())
        ->get('/clientes');

    $response->assertStatus(200);
});

test('un operario no puede ver clientes', function () {
    $response = $this->actingAs(operario())
        ->get('/clientes');

    $response->assertStatus(403);
});

/*
|--------------------------------------------------------------------------
| CREATE FORM
|--------------------------------------------------------------------------
*/

test('un admin puede ver formulario de creación', function () {
    $response = $this->actingAs(admin())
        ->get('/clientes/crear');

    $response->assertStatus(200);
});

/*
|--------------------------------------------------------------------------
| STORE
|--------------------------------------------------------------------------
*/

test('un admin puede crear un cliente correctamente', function () {

    $response = $this->actingAs(admin())
        ->post('/clientes', [
            'cif' => 'B79483731',
            'nombre' => 'Cliente Test',
            'telefono' => '600000000',
            'correo' => 'test@test.com',
            'cuenta_corriente' => 'ES123456789',
            'pais' => pais()->iso2,
            'fecha_alta' => now()->format('Y-m-d'),
            'importe_cuota_mensual' => 120
        ]);

    $response->assertRedirect('/clientes');

    $this->assertDatabaseHas('clientes', [
        'cif' => 'B79483731',
        'nombre' => 'Cliente Test'
    ]);
});

test('falla si faltan campos obligatorios', function () {

    $response = $this->actingAs(admin())
        ->post('/clientes', []);

    $response->assertSessionHasErrors([
        'cif',
        'nombre',
        'telefono',
        'correo',
        'cuenta_corriente',
        'pais',
        'fecha_alta',
        'importe_cuota_mensual'
    ]);
});

/*
|--------------------------------------------------------------------------
| MOSTRAR
|--------------------------------------------------------------------------
*/

test('un admin puede ver detalle de cliente', function () {

    $cliente = Cliente::factory()->create();

    $response = $this->actingAs(admin())
        ->get("/clientes/{$cliente->id}");

    $response->assertStatus(200);
});

test('un operario no puede ver detalle de cliente', function () {

    $cliente = Cliente::factory()->create();

    $response = $this->actingAs(operario())
        ->get("/clientes/{$cliente->id}");

    $response->assertStatus(403);
});