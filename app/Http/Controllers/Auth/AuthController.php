<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('login', $request->input('login'))->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        /** @var string $password */
        $password = $request->input('password');

        if (! Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Wrong password.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], ResponseAlias::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::user()?->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.'], ResponseAlias::HTTP_OK);
    }
}
