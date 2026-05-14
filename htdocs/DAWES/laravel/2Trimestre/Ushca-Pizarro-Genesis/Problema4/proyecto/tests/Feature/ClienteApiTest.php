<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ClienteApiTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        \App\Models\Pais::factory()->create([
            'iso2' => 'ES'
        ]);
        // Creamos el admin usando el factory para asegurar que todos los campos obligatorios del SQL estén
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'tipo' => 'administrador'
        ]);
    }

    #[Test]
    public function un_admin_puede_listar_clientes()
    {
        Cliente::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)
            ->getJson('/api/clientes');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function un_admin_puede_crear_un_cliente()
    {
        $datos = [
            'cif' => 'R4752075D',
            'nombre' => 'Nuevo Cliente SL',
            'correo' => 'nuevo@cliente.com',
            'telefono' => '659874302',
            'cuenta_corriente' => 'ES61 1234 5678 90 1234567890',
            'pais' => 'ES',
            'moneda' => 'EUR',
            'importe_cuota_mensual' => 250.50,
            'fecha_alta' => now()
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/clientes', $datos);

        $response->assertStatus(201); // 201 Created
        $this->assertDatabaseHas('clientes', ['cif' => 'R4752075D']);
    }

    #[Test]
    public function un_admin_puede_ver_un_cliente_especifico()
    {
        $cliente = Cliente::factory()->create(['nombre' => 'Cliente VIP']);

        $response = $this->actingAs($this->admin)
            ->getJson("/api/clientes/{$cliente->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'Cliente VIP']);
    }

    #[Test]
    public function un_admin_puede_actualizar_un_cliente()
    {
        $cliente = Cliente::factory()->create([
            'nombre' => 'Nombre Antiguo',
            'cif' => 'R4752075D',
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson("/api/clientes/{$cliente->id}", [
                'nombre' => 'Nombre Actualizado',
                'cif' => $cliente->cif,
                'telefono' => $cliente->telefono,
                'correo' => $cliente->correo,
                'cuenta_corriente' => $cliente->cuenta_corriente,
                'pais' => 'ES',
                'moneda' => 'EUR',
                'importe_cuota_mensual' => $cliente->importe_cuota_mensual,
                'fecha_alta' => now()
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clientes', [
            'id' => $cliente->id,
            'nombre' => 'Nombre Actualizado'
        ]);
    }

    #[Test]
    public function un_admin_puede_eliminar_un_cliente()
    {
        $cliente = Cliente::factory()->create();

        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/clientes/{$cliente->id}");

        // Comprobamos que sea un código de éxito (200 o 204)
        $this->assertContains($response->getStatusCode(), [200, 204]);

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    #[Test]
    public function no_permite_acceso_a_usuarios_no_autenticados()
    {
        $response = $this->getJson('/api/clientes');
        $response->assertStatus(401);
    }
}
