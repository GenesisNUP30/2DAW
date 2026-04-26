<?php

use App\Models\User;
use App\Models\Cliente;
use App\Models\Tarea;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| RUTAS
|--------------------------------------------------------------------------
*/

test('un admin puede ver listado de tareas', function () {
    $admin = User::factory()->create(['tipo' => 'administrador']);

    $this->actingAs($admin)
        ->get('/tareas')
        ->assertStatus(200);
});

test('un operario no puede acceder a crear tareas', function () {
    $operario = User::factory()->create(['tipo' => 'operario']);

    $this->actingAs($operario)
        ->get('/tareas/crear')
        ->assertStatus(403);
});

/*
|--------------------------------------------------------------------------
| CREACIÓN DE TAREAS
|--------------------------------------------------------------------------
*/

test('un admin puede crear una tarea correctamente', function () {
    $admin = User::factory()->create(['tipo' => 'administrador']);
    $cliente = Cliente::factory()->create();
    $operario = User::factory()->create(['tipo' => 'operario']);

    $data = [
        'cliente_id' => $cliente->id,
        'operario_id' => $operario->id,
        'persona_contacto' => 'Juan',
        'telefono_contacto' => '666666666',
        'descripcion' => 'Descripcion valida larga',
        'correo_contacto' => 'test@test.com',
        'direccion' => 'Calle test',
        'poblacion' => 'Madrid',
        'codigo_postal' => '28001',
        'provincia' => '28',
        'estado' => 'P',
        'fecha_realizacion' => now()->addDay()->toDateString()
    ];

    $this->actingAs($admin)
        ->post('/tareas', $data)
        ->assertRedirect('/tareas');

    $this->assertDatabaseHas('tareas', [
        'descripcion' => 'Descripcion valida larga'
    ]);
});

test('falla si la provincia no coincide con el codigo postal', function () {
    $admin = User::factory()->create(['tipo' => 'administrador']);
    $cliente = Cliente::factory()->create();
    $operario = User::factory()->create(['tipo' => 'operario']);

    $data = [
        'cliente_id' => $cliente->id,
        'operario_id' => $operario->id,
        'persona_contacto' => 'Juan',
        'telefono_contacto' => '666666666',
        'descripcion' => 'Descripcion valida larga',
        'correo_contacto' => 'test@test.com',
        'direccion' => 'Calle test',
        'poblacion' => 'Madrid',
        'codigo_postal' => '28001',
        'provincia' => '41', // incorrecto
        'estado' => 'P',
        'fecha_realizacion' => now()->addDay()->toDateString()
    ];

    $this->actingAs($admin)
        ->post('/tareas', $data)
        ->assertSessionHasErrors('provincia');
});

/*
|--------------------------------------------------------------------------
| COMPLETAR TAREAS
|--------------------------------------------------------------------------
*/

test('un operario puede completar su tarea con fichero', function () {
    Storage::fake('private');

    $operario = User::factory()->create(['tipo' => 'operario']);
    $cliente = Cliente::factory()->create();

    $tarea = Tarea::factory()->create([
        'operario_id' => $operario->id,
        'cliente_id' => $cliente->id,
        'estado' => 'P'
    ]);

    $file = UploadedFile::fake()->create('informe.pdf', 100);

    $this->actingAs($operario)
        ->post("/tareas/{$tarea->id}/completar", [
            'estado' => 'R',
            'fecha_realizacion' => now()->toDateString(),
            'fichero_resumen' => $file
        ])
        ->assertRedirect('/tareas');

    $this->assertDatabaseHas('tareas', [
        'id' => $tarea->id,
        'estado' => 'R'
    ]);
});

test('no puede completar una tarea de otro operario', function () {
    $operario1 = User::factory()->create(['tipo' => 'operario']);
    $operario2 = User::factory()->create(['tipo' => 'operario']);
    $cliente = Cliente::factory()->create();

    $tarea = Tarea::factory()->create([
        'operario_id' => $operario1->id,
        'cliente_id' => $cliente->id
    ]);

    $this->actingAs($operario2)
        ->post("/tareas/{$tarea->id}/completar", [])
        ->assertStatus(403);
});