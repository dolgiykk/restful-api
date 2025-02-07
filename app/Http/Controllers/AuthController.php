<?php

namespace App\Http\Controllers;

use App\Helpers\QRCodeHelper;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'login' => 'string|required|unique:users',
                'email' => 'string|required|email|unique:users',
                'password' => 'string|required|min:8',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        /** @var string $password */
        $password = $request->input('password');

        $user = User::create([
            'login' => $request->input('login'),
            'email' => $request->input('email'),
            'password' => Hash::make($password),
        ]);

        return response()->json(['message' => 'User registered successfully.'], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'login' => 'string|required',
                'password' => 'string|required|min:8',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        $user = User::where('login', $request->input('login'))->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 401);
        }

        /** @var string $password */
        $password = $request->input('password');

        if (! Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Wrong password.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()?->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        try {
            $request->validate([
                'password' => 'string|required|min:8',
                'new_password' => 'string|required|confirmed|min:8',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        /** @var string $password */
        $password = $request->input('password');
        /** @var string $newPassword */
        $newPassword = $request->input('new_password');

        if (! Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Wrong password.'], 401);
        }

        if ($request->input('password') === $request->input('new_password')) {
            return response()->json(['message' => 'Old and new passwords matched.'], 422);
        }

        $user->update(['password' => Hash::make($newPassword)]);
        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return response()->json(['message' => 'Password changed successfully. All sessions was closed.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function closeOtherSessions(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return response()->json(['message' => 'All sessions on other devices were closed successfully.'], 200);
    }

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
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->two_factor_secret && $user->two_factor_qr_code_url) {
            return response()->json([
                'two_factor_secret' => $user->two_factor_secret,
                'two_factor_qr_code_base64' => QRCodeHelper::toBase64($user->two_factor_qr_code_url),
            ], 200);
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
        ], 200);
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
            return response()->json(['message' => 'Unauthenticated.'], 401);
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
            return response()->json(['message' => 'Wrong verification code.'], 401);
        }

        return response()->json(['message' => '2FA verified successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendResetToken(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email|exists:users,email',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 422);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showResetPasswordForm(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email|exists:users,email',
                'token' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->get('email'))
            ->first();

        /** @var string $token */
        $token = $request->get('token');

        if (! $resetRecord || ! isset($resetRecord->token) || ! Hash::check($token, $resetRecord->token)) {
            return response()->json(['errors' => 'Invalid or expired token.'], 422);
        }

        return response()->json([
            'email' => $request->get('email'),
            'token' => $request->get('token'),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email|exists:users,email',
                'token' => 'required|string',
                'password' => 'required|string|confirmed',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->first();

        /** @var string $token */
        $token = $request->input('token');

        if (! $resetRecord || ! isset($resetRecord->token) || ! Hash::check($token, $resetRecord->token)) {
            return response()->json(['errors' => 'Invalid or expired token.'], 400);
        }

        /** @var string $status */
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 422);
    }
}
