<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\SendResetTokenRequest;
use App\Http\Requests\Auth\ShowResetPasswordFormRequest;
use App\Services\PasswordService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    private PasswordService $passwordService;

    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    /**
     * @param ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function change(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(...$this->passwordService->change($user, $request->validated()));
    }

    /**
     * @param SendResetTokenRequest $request
     * @return JsonResponse
     */
    public function sendResetToken(SendResetTokenRequest $request): JsonResponse
    {
        return response()->json(...$this->passwordService->sendResetToken($request->validated()));
    }

    /**
     * @param ShowResetPasswordFormRequest $request
     * @return JsonResponse
     */
    public function showResetForm(ShowResetPasswordFormRequest $request): JsonResponse
    {
        /** @var string $token */
        $token = $request->get('token');
        /** @var string $email */
        $email = $request->get('email');

        return response()->json(...$this->passwordService->showResetForm($email, $token));
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        return response()->json(...$this->passwordService->reset($request->validated()));
    }
}
