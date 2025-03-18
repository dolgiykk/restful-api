<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public int $perPage;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->perPage = UserController::DEFAULT_PER_PAGE;

        User::factory()->count(15)->create();
    }

    /**
     * @return void
     */
    public function test_store_successfully(): void
    {
        $payload = [
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/users', $payload);

        $user = User::where('email', 'test@test.com')->first();

        $response->assertStatus(201)
            ->assertJson(['message' => 'Created successfully.'])
            ->assertJsonStructure([
                'message',
                'user' => array_keys((new UserResource($user))->toArray(new Request())),

            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }

    /**
     * @return void
     */
    public function test_store_with_invalid_data(): void
    {
        $payload = [
            'login' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/v1/users', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login', 'email', 'password']);
    }

    /**
     * @return void
     */
    public function test_store_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'test@test.com']);

        $payload = [
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/users', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @return void
     */
    public function test_index_without_paginate_query(): void
    {
        $response = $this->getJson('/api/v1/users');

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
    public function test_index_with_custom_paginate_query(): void
    {
        $response = $this->getJson('/api/v1/users?per_page=5');
        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    /**
     * @return void
     */
    public function test_index_when_empty_database(): void
    {
        User::query()->delete();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [],
                'pagination' => [
                    'total' => 0,
                ],
            ]);
    }

    /**
     * @return void
     */
    public function test_show_successfully(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/v1/users/'.$user->id);

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_show_with_nonexistent_id(): void
    {
        User::query()->delete();

        $response = $this->getJson('/api/v1/users/1');

        $response->assertStatus(404)
            ->assertJson(['message' => 'User not found.']);
    }

    /**
     * @return void
     */
    public function test_update_successfully(): void
    {
        $user = User::factory()->create();

        $payload = [
            'login' => 'newlogin',
            'email' => 'newemail@example.com',
            'birthday' => '1995-05-15',
            'sex' => 'male',
        ];

        $response = $this->patchJson('/api/v1/users/'.$user->id, $payload);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'user' => array_keys((new UserResource($user))->toArray(new Request())),

            ])
            ->assertJsonFragment([
                'login' => $payload['login'],
                'email' => $payload['email'],
                'birthday' => $payload['birthday'],
                'sex' => $payload['sex'],
            ]);

        $this->assertDatabaseHas('users', array_merge(['id' => $user->id], $payload));
    }

    /**
     * @return void
     */
    public function test_updating_non_existent_user(): void
    {
        $payload = [
            'login' => 'newlogin',
            'email' => 'newemail@example.com',
        ];

        $response = $this->patchJson('/api/v1/users/999', $payload);

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'User not found.']);
    }

    /**
     * @return void
     */
    public function test_validation_fails_when_email_or_login_is_duplicate(): void
    {
        $user1 = User::factory()->create(['email' => 'existing@example.com', 'login' => 'existinglogin']);
        $user2 = User::factory()->create();

        $payload = [
            'login' => 'existinglogin',
            'email' => 'existing@example.com',
        ];

        $response = $this->putJson('/api/v1/users/'.$user2->id, $payload);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['login', 'email']);
    }

    /**
     * @return void
     */
    public function test_destroy_user_successfully(): void
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);

        $response = $this->deleteJson('api/v1/users/'.$user->id);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson([
                'message' => 'Deleted successfully.',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * @return void
     */
    public function test_destroy_nonexistent_user(): void
    {
        $response = $this->deleteJson('/api/v1/users/999');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'User not found.',
            ]);
    }
}
