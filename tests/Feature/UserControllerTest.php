<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function test_get_user_by_exist_id(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/v1/users/'.$user->id);

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_get_user_by_nonexistent_id(): void
    {
        User::query()->delete();

        $response = $this->getJson('/api/v1/users/1');

        $response->assertStatus(404)
            ->assertJson(['message' => 'User not found.']);
    }
}
