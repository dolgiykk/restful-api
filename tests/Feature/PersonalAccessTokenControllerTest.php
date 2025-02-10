<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalAccessTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_revoke_other_tokens_successfully(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken('Current Session');

        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $tokenId1 = $user->createToken('Token 1')->accessToken->id;
        $tokenId2 = $user->createToken('Token 2')->accessToken->id;

        $response = $this->postJson('/api/v1/logout-other-devices');

        $response->assertStatus(200)
            ->assertJson(['message' => 'All sessions on other devices were closed successfully.']);

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $currentToken->accessToken->id]);
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
}
