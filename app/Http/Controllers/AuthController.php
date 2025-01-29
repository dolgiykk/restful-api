<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'string|required',
                'email' => 'string|required|email|unique:users',
                'password' => 'string|required|min:8',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        /** @var string $password */
        $password = $request['password'];

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($password),
        ]);

        return response()->json(['message' => 'User registered successfully.'], 201);
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'string|required|email',
                'password' => 'string|required',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        $user = User::where('email', $request['email'])->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 401);
        }

        if (! Hash::check($request['password'], $user->password)) {
            return response()->json(['message' => 'Wrong password.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }
}
