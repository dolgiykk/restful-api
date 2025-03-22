<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthService
{
    /**
     * @param array<string, mixed> $data
     * @return array<mixed>
     */
    public function login(array $data): array
    {
        $user = User::where('login', $data['login'])->first();

        if (! $user) {
            return [
                ['message' => __('auth.auth.user_not_found')],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        if (! Hash::check($data['password'], $user->password)) {
            return [
                ['message' => __('auth.auth.wrong_password')],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            [
                'token' => $token,
                'message' => __('auth.auth.logged_in'),
            ],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @return array<mixed>
     */
    public function logout(): array
    {
        Auth::user()?->currentAccessToken()->delete();

        return [
            ['message' => __('auth.auth.logged_out')],
            ResponseAlias::HTTP_OK,
        ];
    }
}
