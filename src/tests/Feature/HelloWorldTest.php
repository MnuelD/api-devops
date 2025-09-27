<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelloWorldTest extends TestCase
{
    public function test_hello_world_endpoint_returns_success(): void
    {
        $response = $this->getJson('/api/hello');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Olá Mundo!']);
    }
}

