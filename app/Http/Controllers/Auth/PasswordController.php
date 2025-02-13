<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetTokenRequest;
use App\Http\Requests\Auth\ShowResetPasswordFormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PasswordController extends Controller
{
    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function change(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        /** @var string $password */
        $password = $request->input('password');
        /** @var string $newPassword */
        $newPassword = $request->input('new_password');

        if (! Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Wrong password.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        if ($request->input('password') === $request->input('new_password')) {
            return response()->json(['message' => 'Old and new passwords matched.'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update(['password' => Hash::make($newPassword)]);
        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return response()->json(['message' => 'Password changed successfully. All sessions was closed.'], ResponseAlias::HTTP_OK);
    }

    /**
     * @param SendResetTokenRequest $request
     * @return JsonResponse
     */
    public function sendResetToken(SendResetTokenRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], ResponseAlias::HTTP_OK)
            : response()->json(['message' => __($status)], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param ShowResetPasswordFormRequest $request
     * @return JsonResponse
     */
    public function showResetForm(ShowResetPasswordFormRequest $request): JsonResponse
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->get('email'))
            ->first();

        /** @var string $token */
        $token = $request->get('token');

        if (! $resetRecord || ! isset($resetRecord->token) || ! Hash::check($token, $resetRecord->token)) {
            return response()->json(['errors' => 'Invalid or expired token.'], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'email' => $request->get('email'),
            'token' => $request->get('token'),
        ]);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->input('email'))
            ->first();

        /** @var string $token */
        $token = $request->input('token');

        if (! $resetRecord || ! isset($resetRecord->token) || ! Hash::check($token, $resetRecord->token)) {
            return response()->json(['errors' => 'Invalid or expired token.'], ResponseAlias::HTTP_BAD_REQUEST);
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
            ? response()->json(['message' => __($status)], ResponseAlias::HTTP_OK)
            : response()->json(['message' => __($status)], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}
