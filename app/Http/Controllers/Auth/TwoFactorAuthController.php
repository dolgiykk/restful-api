<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Veryfi2FARequest;
use App\Services\Auth\TwoFactorAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;

class TwoFactorAuthController extends Controller
{
    public TwoFactorAuthService $twoFactorAuthService;

    public function __construct(TwoFactorAuthService $twoFactorAuthService)
    {
        $this->twoFactorAuthService = $twoFactorAuthService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws IncompatibleWithGoogleAuthenticatorException
     * @throws InvalidCharactersException
     * @throws SecretKeyTooShortException
     */
    public function enable(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(...$this->twoFactorAuthService->enable($user));
    }

    /**
     * @param Veryfi2FARequest $request
     * @return JsonResponse
     */
    public function verify(Veryfi2FARequest $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(...$this->twoFactorAuthService->verify($user, $request->validated()));
    }
}
