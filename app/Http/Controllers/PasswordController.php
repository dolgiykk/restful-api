<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function change(Request $request): JsonResponse
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
    public function showResetForm(Request $request): JsonResponse
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
    public function reset(Request $request): JsonResponse
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
