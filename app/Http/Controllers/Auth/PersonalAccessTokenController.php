<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PersonalAccessTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
    const int DEFAULT_PER_PAGE = 10;

    private PersonalAccessTokenService $personalAccessTokenService;

    public function __construct(PersonalAccessTokenService $personalAccessTokenService)
    {
        $this->personalAccessTokenService = $personalAccessTokenService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $perPage = (int) $request->query('per_page') ?: self::DEFAULT_PER_PAGE;

        return response()->json(...$this->personalAccessTokenService->getUserTokens($user, $perPage));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function revokeOtherTokens(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json(...$this->personalAccessTokenService->revokeOtherTokens($user));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        /** @var int|null $tokenId */
        $tokenId = $request->route('id');

        return response()->json(...$this->personalAccessTokenService->destroy($user, $tokenId));
    }
}
