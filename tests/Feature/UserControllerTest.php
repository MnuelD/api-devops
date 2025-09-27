<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_rota_users_retorna_lista_de_usuarios(): void
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_rota_register_cria_um_novo_usuario(): void
    {
        $data = [
            'name' => 'Teste User',
            'email' => 'teste@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user' => ['id', 'name', 'email']
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'teste@example.com']);
    }
}

