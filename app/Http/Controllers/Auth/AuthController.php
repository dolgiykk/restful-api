<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use App\Services\Auth\PersonalAccessTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    const int DEFAULT_PER_PAGE = 10;

    private AuthService $authService;

    private PersonalAccessTokenService $personalAccessTokenService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService, PersonalAccessTokenService $personalAccessTokenService)
    {
        $this->authService = $authService;
        $this->personalAccessTokenService = $personalAccessTokenService;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return response()->json(...$this->authService->login($request->validated()));
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return response()->json(...$this->authService->logout());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutOtherDevices(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(...$this->personalAccessTokenService->revokeOtherTokens($user));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutDevice(Request $request): JsonResponse
    {
        $user = $request->user();

        /** @var int|null $tokenId */
        $tokenId = $request->route('id');

        return response()->json(...$this->personalAccessTokenService->destroy($user, $tokenId));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function tokens(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = (int) $request->query('per_page') ?: self::DEFAULT_PER_PAGE;

        return response()->json(...$this->personalAccessTokenService->getUserTokens($user, $perPage));
    }
}
