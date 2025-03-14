<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function test_send_reset_token_successfully(): void
    {
        $user = User::factory()->create(['email' => 'test@test.com']);

        $response = $this->postJson('/api/v1/forgot-password', ['email' => 'test@test.com']);

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function test_send_reset_token_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/v1/forgot-password', ['email' => 'test@test.com']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @return void
     */
    public function test_email_content_on_reset_token_successfully(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email' => 'test@test.com']);

        $this->postJson('/api/v1/forgot-password', ['email' => 'test@test.com']);

        Notification::assertSentTo([$user], ResetPassword::class);
    }

    public function test_show_reset_password_form_successfully(): void
    {
        $user = User::factory()->create(['email' => 'test@test.com']);

        $token = 'test-token';

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/reset-password?email=test@test.com&token='.$token);

        $response->assertStatus(200)
            ->assertJson([
                'email' => 'test@test.com',
                'token' => $token,
            ]);
    }

    /**
     * @return void
     */
    public function test_reset_password_form_with_invalid_token(): void
    {
        $user = User::factory()->create(['email' => 'test@test.com']);

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => Hash::make('correct-token'),
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/reset-password?email=test@test.com&token=wrong-token');

        $response->assertStatus(422)
            ->assertJson(['errors' => 'Invalid or expired token.']);
    }

    /**
     * @return void
     */
    public function test_reset_password_form_with_nonexistent_email(): void
    {
        $response = $this->getJson('/api/v1/reset-password?email=nonexistent@test.com&token=some-token');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @return void
     */
    public function test_reset_password_form_without_token(): void
    {
        $response = $this->getJson('/api/v1/reset-password?email=test@test.com');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['token']);
    }

    /**
     * @return void
     */
    public function test_reset_password_form_without_email(): void
    {
        $response = $this->getJson('/api/v1/reset-password?token=some-token');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * @return void
     */
    public function test_change_password_successfully(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $currentToken = $user->createToken('Current Session');
        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $payload = [
            'password' => 'password123',
            'new_password' => 'new_password123',
            'new_password_confirmation' => 'new_password123',
        ];

        $response = $this->postJson('/api/v1/change-password', $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Password changed successfully. All sessions were closed.']);
    }

    /**
     * @return void
     */
    public function test_change_password_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $currentToken = $user->createToken('Current Session');
        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $payload = [
            'password' => 'wrong_password',
            'new_password' => 'new_password123',
            'new_password_confirmation' => 'new_password123',
        ];

        $response = $this->postJson('/api/v1/change-password', $payload);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Wrong password.']);
    }

    /**
     * @return void
     */
    public function test_change_password_with_same_password(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $currentToken = $user->createToken('Current Session');
        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $payload = [
            'password' => 'password123',
            'new_password' => 'password123',
            'new_password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/v1/change-password', $payload);

        $response->assertStatus(422)
            ->assertJson(['message' => 'Old and new passwords matched.']);
    }

    /**
     * @return void
     */
    public function test_change_password_with_different_new_passwords(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $currentToken = $user->createToken('Current Session');
        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $payload = [
            'password' => 'password123',
            'new_password' => 'new_password123',
            'new_password_confirmation' => 'different_new_password123',
        ];

        $response = $this->postJson('/api/v1/change-password', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['new_password']);
    }
}
