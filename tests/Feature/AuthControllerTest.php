<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\AuthController;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public int $perPage;

    public NewAccessToken $currentToken;

    public User $user;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $this->currentToken = $this->user->createToken('Current token');

        PersonalAccessToken::factory()
            ->count(15)
            ->create([
                'tokenable_type' => User::class,
                'tokenable_id' => $this->user->id,
            ]);

        $this->perPage = AuthController::DEFAULT_PER_PAGE;
    }

    /**
     * @return void
     */
    public function test_login_successfully(): void
    {
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
        Sanctum::actingAs($this->user);

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

    public function test_tokens_without_paginate_query(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/tokens');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                    'next_page_url',
                    'prev_page_url',
                ],
            ]);

        $this->assertCount($this->perPage, $response->json('data'));
    }

    /**
     * @return void
     */
    public function test_tokens_with_custom_paginate_query(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/tokens/?per_page=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    /**
     * @return void
     */
    public function test_2fa_enable_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/2fa/enable');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_logout_other_devices_successfully(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->currentToken->plainTextToken);

        $tokenId1 = $this->user->createToken('Token 1')->accessToken->id;
        $tokenId2 = $this->user->createToken('Token 2')->accessToken->id;

        $response = $this->postJson('/api/v1/logout/other-devices');

        $response->assertStatus(200)
            ->assertJson(['message' => 'All sessions on other devices were closed successfully.']);

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $this->currentToken->accessToken->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId1]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId2]);
    }

    /**
     * @return void
     */
    public function test_revoke_other_tokens_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_logout_device_successfully(): void
    {
        Sanctum::actingAs($this->user);

        $tokenId1 = $this->user->createToken('Token 1')->accessToken->id;
        $response = $this->deleteJson('/api/v1/logout/device/'.$tokenId1);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Token deleted successfully.']);
    }

    /**
     * @return void
     */
    public function test_logout_current_device(): void
    {
        $this->withHeader('Authorization', 'Bearer '.$this->currentToken->plainTextToken);

        $response = $this->deleteJson('/api/v1/logout/device/'.$this->currentToken->accessToken->id);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Cannot delete current token.']);
    }

    /**
     * @return void
     */
    public function test_logout_nonexistent_device(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson('/api/v1/logout/device/'. 9999);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Token not found.']);
    }
}
