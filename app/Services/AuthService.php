<?php

namespace App\Services;

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
                ['message' => 'User not found.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        if (! Hash::check($data['password'], $user->password)) {
            return [
                ['message' => 'Wrong password.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            ['token' => $token],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @return array<mixed>
     */
    public function logout(): array
    {
        Auth::user()?->tokens()->delete();

        return [
            ['message' => 'Logged out successfully.'],
            ResponseAlias::HTTP_OK,
        ];
    }
}
