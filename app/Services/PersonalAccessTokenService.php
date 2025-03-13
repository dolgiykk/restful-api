<?php

namespace App\Services;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PersonalAccessTokenService
{
    /**
     * @param User|null $user
     * @param int $perPage
     * @return array<mixed>
     */
    public function getUserTokens(?User $user, int $perPage): array
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => 'Unauthenticated.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        $tokens = $user->tokens()->paginate($perPage);

        return [
            [
                'data' => $tokens->items(),
                'pagination' => [
                    'total' => $tokens->total(),
                    'per_page' => $tokens->perPage(),
                    'current_page' => $tokens->currentPage(),
                    'last_page' => $tokens->lastPage(),
                    'next_page_url' => $tokens->nextPageUrl(),
                    'prev_page_url' => $tokens->previousPageUrl(),
                ],
            ], ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param User|null $user
     * @return array<mixed>
     */
    public function revokeOtherTokens(?User $user): array
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => 'Unauthenticated.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return [
            ['message' => 'All sessions on other devices were closed successfully.'],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param User|null $user
     * @param int|null $tokenId
     * @return array<mixed>
     */
    public function destroy(?User $user, ?int $tokenId): array
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => 'Unauthenticated.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        $token = $user->tokens()->where('id', $tokenId)->first();

        if (! $token) {
            return [
                ['message' => 'Token not found.'],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        if ($tokenId == $user->currentAccessToken()->id) {
            return [
                ['message' => 'Cannot delete current token.'],
                ResponseAlias::HTTP_FORBIDDEN,
            ];
        }

        if ($token->delete()) {
            return [
                ['message' => 'Token deleted successfully.'],
                ResponseAlias::HTTP_OK,
            ];
        }

        return [
            ['message' => 'Token could not be deleted.'],
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }
}
