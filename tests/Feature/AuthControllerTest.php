<?php

namespace Tests\Feature;

use App\Helpers\QRCodeHelper;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
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
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User registered successfully.']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }

    /**
     * @return void
     */
    public function test_register_with_invalid_data(): void
    {
        $payload = [
            'login' => '',
            'email' => 'invalid-email',
            'password' => 'short',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['login', 'email', 'password']);
    }

    /**
     * @return void
     */
    public function test_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'test@test.com']);

        $payload = [
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/v1/register', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

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
            ->assertJsonStructure(['token']);
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
            ->assertJson(['message' => 'Password changed successfully. All sessions was closed.']);
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

    /**
     * @return void
     */
    public function test_close_other_sessions_successfully(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken('Current Session');

        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $tokenId1 = $user->createToken('Token 1')->accessToken->id;
        $tokenId2 = $user->createToken('Token 2')->accessToken->id;

        $response = $this->postJson('/api/v1/close-other-sessions');

        $response->assertStatus(200)
            ->assertJson(['message' => 'All sessions on other devices were closed successfully.']);

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $currentToken->accessToken->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId1]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId2]);
    }

    /**
     * @return void
     */
    public function test_close_other_sessions_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/logout');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_enable2FA_successfully(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
        ]);

        $currentToken = $user->createToken('Current Session');

        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $response = $this->postJson('/api/v1/enable2FA');

        $response->assertStatus(200)
            ->assertJsonStructure(['two_factor_secret', 'two_factor_qr_code_base64']);
    }

    /**
     * @return void
     */
    public function test_enable2FA_without_authentication(): void
    {
        $response = $this->postJson('/api/v1/enable2FA');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    /**
     * @return void
     */
    public function test_enable2FA_when_already_enabled(): void
    {
        $twoFactorCodeUrl = 'otpauth://totp/:pidrsi123la?secret=DAQJRAKOTDG37DVU&issuer=&algorithm=SHA1&digits=6&period=30';

        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => 'DAQJRAKOTDG37DVU',
            'two_factor_qr_code_url' => $twoFactorCodeUrl,
        ]);

        $currentToken = $user->createToken('Current Session');

        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);

        $response = $this->postJson('/api/v1/enable2FA');

        $response->assertStatus(200)
            ->assertJson([
                'two_factor_secret' => 'DAQJRAKOTDG37DVU',
                'two_factor_qr_code_base64' => QRCodeHelper::toBase64($twoFactorCodeUrl),
            ]);
    }

    /**
     * @return void
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function test_verify2FA_successfully(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => 'DAQJRAKOTDG37DVU',
        ]);

        $currentToken = $user->createToken('Current Session');

        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);
        $google2fa = new Google2FA();
        $code = $google2fa->getCurrentOtp('DAQJRAKOTDG37DVU');

        $response = $this->postJson('/api/v1/verify2FA', ['two_factor_code' => $code]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => '2FA verified successfully.',
        ]);
    }

    /**
     * @return void
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function test_verify2FA_with_wrong_two_factor_code(): void
    {
        $user = User::factory()->create([
            'login' => 'TestUser',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'two_factor_secret' => 'DAQJRAKOTDG37DVU',
        ]);

        $currentToken = $user->createToken('Current Session');

        $this->withHeader('Authorization', 'Bearer '.$currentToken->plainTextToken);
        $google2fa = new Google2FA();
        $code = $google2fa->getCurrentOtp('FAQJRAKOTDG37DVU');

        $response = $this->postJson('/api/v1/verify2FA', ['two_factor_code' => $code]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Wrong verification code.']);
    }

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
