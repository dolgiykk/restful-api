<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\UserAuthenticate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PersonalAccessTokenService
{
    use UserAuthenticate;

    /**
     * @param User|null $user
     * @param int $perPage
     * @return array<mixed>
     */
    public function getUserTokens(?User $user, int $perPage): array
    {
        if ($checkAuth = $this->ensureAuthenticated($user)) {
            return $checkAuth;
        }

        /** @var User $user */
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
        if ($checkAuth = $this->ensureAuthenticated($user)) {
            return $checkAuth;
        }

        /** @var User $user */
        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return [
            ['message' => __('auth.personal_access_token.session_closed')],
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
        if ($checkAuth = $this->ensureAuthenticated($user)) {
            return $checkAuth;
        }

        /** @var User $user */
        $token = $user->tokens()->where('id', $tokenId)->first();

        if (! $token) {
            return [
                ['message' => __('auth.personal_access_token.token_not_found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        if ($tokenId == $user->currentAccessToken()->id) {
            return [
                ['message' => __('auth.personal_access_token.cannot_delete_current')],
                ResponseAlias::HTTP_FORBIDDEN,
            ];
        }

        if ($token->delete()) {
            return [
                ['message' => __('auth.personal_access_token.deleted_successfully')],
                ResponseAlias::HTTP_OK,
            ];
        }

        return [
            ['message' => __('auth.personal_access_token.delete_failed')],
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }
}
