<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_register_successfully(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully.']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }

    /**
     * @return void
     */
    public function test_register_empty_name(): void
    {
        $payload = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_duplicate_email(): void
    {
        User::factory()->create(['email' => 'test@test.com']);

        $payload = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
