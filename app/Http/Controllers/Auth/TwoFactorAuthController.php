<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\QRCodeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TwoFactorAuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function enable2FA(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        if ($user->two_factor_secret && $user->two_factor_qr_code_url) {
            return response()->json([
                'two_factor_secret' => $user->two_factor_secret,
                'two_factor_qr_code_base64' => QRCodeHelper::toBase64($user->two_factor_qr_code_url),
            ], ResponseAlias::HTTP_OK);
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

        return response()->json([
            'two_factor_secret' => $secret,
            'two_factor_qr_code_base64' => QRCodeHelper::toBase64($qrCodeUrl),
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function verify2FA(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        try {
            $request->validate([
                'two_factor_code' => 'numeric|required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        /** @var string $twoFactorSecret */
        $twoFactorSecret = $user->two_factor_secret;

        /** @var string $twoFactorCode */
        $twoFactorCode = $request->input('two_factor_code');

        $google2fa = new Google2FA();
        $isValid = $google2fa->verifyKey($twoFactorSecret, $twoFactorCode);

        if (! $isValid) {
            return response()->json(['message' => 'Wrong verification code.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return response()->json(['message' => '2FA verified successfully.'], ResponseAlias::HTTP_OK);
    }
}
