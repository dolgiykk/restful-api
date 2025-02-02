<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
                'password' => 'string|required',
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
        $auth = $this->login($request);

        if ($auth->status() !== 200) {
            return response()->json(['message' => 'Incorrect credentials.'], 401);
        }

        try {
            $request->validate([
                'new_password' => 'string|required|min:8',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], $e->status);
        }

        if ($request->input('password') === $request->input('new_password')) {
            return response()->json(['message' => 'Old and new passwords matched.'], 422);
        }

        /** @var string $password */
        $password = $request->input('new_password');

        User::where('login', $request->input('login'))
            ->firstOrFail()
            ->update(['password' => Hash::make($password)]);

        Auth::user()?->tokens()->delete();

        return response()->json(['message' => 'Password changed successfully. All sessions was closed.'], 200);
    }
}
