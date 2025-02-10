<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_verify_email_successfully(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $hash = sha1($user->getEmailForVerification());

        $response = $this->getJson("/api/v1/email/verify/{$user->id}/{$hash}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Email has been successfully verified.']);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /**
     * @return void
     */
    public function test_verify_email_with_invalid_hash(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $invalidHash = 'invalidhashvalue';

        $response = $this->getJson("/api/v1/email/verify/{$user->id}/{$invalidHash}");

        $response->assertStatus(400)
            ->assertJson(['message' => 'Invalid verification link.']);
    }

    /**
     * @return void
     */
    public function test_verify_email_already_verified(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $hash = sha1($user->getEmailForVerification());

        $response = $this->getJson("/api/v1/email/verify/{$user->id}/{$hash}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Email already verified.']);
    }

    /**
     * @return void
     */
    public function test_verify_email_user_not_found(): void
    {
        $invalidUserId = 9999;
        $hash = sha1('nonexistent@example.com');

        $response = $this->getJson("/api/v1/email/verify/{$invalidUserId}/{$hash}");

        $response->assertStatus(404);
    }
}
