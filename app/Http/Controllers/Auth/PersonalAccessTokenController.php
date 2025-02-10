<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
    const int DEFAULT_PER_PAGE = 10;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $perPage = (int) $request->query('per_page') ?: self::DEFAULT_PER_PAGE;

        $tokens = $user->tokens()->paginate($perPage);

        return response()->json([
            'data' => $tokens->items(),
            'pagination' => [
                'total' => $tokens->total(),
                'per_page' => $tokens->perPage(),
                'current_page' => $tokens->currentPage(),
                'last_page' => $tokens->lastPage(),
                'next_page_url' => $tokens->nextPageUrl(),
                'prev_page_url' => $tokens->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function revokeOtherTokens(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return response()->json(['message' => 'All sessions on other devices were closed successfully.'], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $tokenId = $request->route('id');

        $token = $user->tokens()->where('id', $tokenId)->first();

        if (! $token) {
            return response()->json(['message' => 'Token not found.'], 404);
        }

        if ($tokenId == $user->currentAccessToken()->id) {
            return response()->json(['message' => 'Cannot delete current token.'], 403);
        }

        if ($token->delete()) {
            return response()->json(['message' => 'Token deleted successfully.'], 200);
        }

        return response()->json(['message' => 'Token could not be deleted.'], 422);
    }
}
