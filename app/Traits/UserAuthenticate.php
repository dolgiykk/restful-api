<?php

namespace App\Traits;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait UserAuthenticate
{
    /**
     * @param User|null $user
     * @return array<mixed>|null
     */
    public function ensureAuthenticated(?User $user): array|null
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => __('user.unauthenticated')],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        return null;
    }
}
