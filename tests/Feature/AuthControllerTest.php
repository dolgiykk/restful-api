<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_login_successfully(): void
    {
        User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'login' => 'TestUser',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/login', $payload);
        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'message'])
            ->assertJson(['message' => 'Logged in successfully.']);
    }

    /**
     * @return void
     */
    public function test_login_with_wrong_password(): void
    {
        User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'login' => 'TestUser',
            'password' => 'wrong-password',
        ];

        $response = $this->postJson('/api/v1/login', $payload);
        $response->assertStatus(401)
            ->assertJson(['message' => 'Wrong password.']);
    }

    /**
     * @return void
     */
    public function test_login_with_nonexistent_user(): void
    {
        User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'login' => 'nonexistentLogin',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/login', $payload);
        $response->assertStatus(401)
            ->assertJson(['message' => 'User not found.']);
    }

    /**
     * @return void
     */
    public function test_login_with_invalid_data(): void
    {
        $response = $this->postJson('/api/v1/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login', 'password']);
    }

    /**
     * @return void
     */
    public function test_logout_successfully(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out successfully.']);
    }

    /**
     * @return void
     */
    public function test_logout_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
