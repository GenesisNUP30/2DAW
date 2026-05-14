<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test; // Para evitar los Warnings

class ClienteApiTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com', 
            'password' => bcrypt('password'),
            'tipo' => 'administrador'
        ]);
    }

    #[Test]
    public function un_admin_puede_obtener_lista_de_clientes()
    {
        // Creamos un cliente de prueba
        Cliente::create([
            'nombre' => 'Cliente A',
            'cif' => '12345678A',
            'correo' => 'a@test.com', 
            'moneda' => 'EUR'
        ]);

        $response = $this->actingAs($this->admin)
                         ->getJson('/api/clientes');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'nombre', 'cif', 'correo', 'moneda']
                 ]);
    }

    #[Test]
    public function devuelve_404_si_el_cliente_no_existe()
    {
        $response = $this->actingAs($this->admin)
                         ->getJson('/api/clientes/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function no_permite_acceso_a_usuarios_sin_token_o_sesion()
    {
        $response = $this->getJson('/api/clientes');

        $response->assertStatus(401);
    }
}