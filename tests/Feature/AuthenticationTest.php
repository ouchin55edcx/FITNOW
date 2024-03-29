<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->json('POST', 'api/register', $userData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'User registred ']);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->json('POST', 'api/login', $loginData);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'message']);
    }


    public function test_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('AuthToken')->plainTextToken;

        $this->actingAs($user);

        $response = $this->json('POST', 'api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'logged out']);
    }
}