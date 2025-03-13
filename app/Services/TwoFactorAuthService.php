<?php

namespace App\Services;

use App\Helpers\QRCodeHelper;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TwoFactorAuthService
{
    /**
     * @param User|null $user
     * @return array<mixed>
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function enable2FA(?User $user):array
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => 'Unauthenticated.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        if ($user->two_factor_secret && $user->two_factor_qr_code_url) {
            return [
                [
                    'two_factor_secret' => $user->two_factor_secret,
                    'two_factor_qr_code_base64' => QRCodeHelper::toBase64($user->two_factor_qr_code_url),
                ],
                ResponseAlias::HTTP_OK,
            ];
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $user->update(['two_factor_secret' => $secret]);

        /** @var string $appName */
        $appName = config('app.name');

        $qrCodeUrl = $google2fa->getQRCodeUrl($appName, $user->login, $secret);
        $user->update([
            'two_factor_secret' => $secret,
            'two_factor_qr_code_url' => $qrCodeUrl,
        ]);

        return [
            [
                'two_factor_secret' => $secret,
                'two_factor_qr_code_base64' => QRCodeHelper::toBase64($qrCodeUrl),
            ],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param User|null $user
     * @param array<mixed> $data
     * @return array<mixed>
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function verify2FA(?User $user, array $data):array
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => 'Unauthenticated.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        /** @var string $twoFactorSecret */
        $twoFactorSecret = $user->two_factor_secret;

        /** @var string $twoFactorCode */
        $twoFactorCode = $data['two_factor_code'];

        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($twoFactorSecret, $twoFactorCode);

        if (! $isValid) {
            return [
                ['message' => 'Wrong verification code.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        return [
            ['message' => '2FA verified successfully.'],
            ResponseAlias::HTTP_OK,
        ];
    }
}
