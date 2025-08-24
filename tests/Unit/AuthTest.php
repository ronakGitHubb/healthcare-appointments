<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase; // <- this runs migrations for the test DB

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_user_can_register_and_login()
    {
        // Test user registration
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123'
        ]);
        dump('reg response');
        dump($response->getContent());
        $response->assertStatus(201);

        // Test user login
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'secret123'
        ]);
        dump('login response');
        dump($response->getContent());
        $response->assertStatus(200)->assertJsonStructure(['token']);
    }
}
