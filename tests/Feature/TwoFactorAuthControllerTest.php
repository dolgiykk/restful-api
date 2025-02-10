<?php

namespace Tests\Feature;

use App\Helpers\QRCodeHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthControllerTest extends TestCase
{
    use RefreshDatabase;

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
}
