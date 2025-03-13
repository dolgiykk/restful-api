<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailVerificationService
{
    /**
     * @param User|null $user
     * @return array<mixed>
     */
    public function send(?User $user): array
    {
        if (! $user?->currentAccessToken()) {
            return [
                ['message' => 'Unauthenticated.'],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                ['message' => 'Email already verified.'],
                ResponseAlias::HTTP_OK,
            ];
        }

        $user->sendEmailVerificationNotification();

        return [
            ['message' => 'Verification email sent.'],
            ResponseAlias::HTTP_ACCEPTED,
        ];
    }

    /**
     * @param User $user
     * @param string $hash
     * @return array<mixed>
     */
    public function verify(User $user, string $hash): array
    {
        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return [
                ['message' => 'Invalid verification link.'],
                ResponseAlias::HTTP_BAD_REQUEST,
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                ['message' => 'Email already verified.'],
                ResponseAlias::HTTP_OK,
            ];
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return [
            ['message' => 'Email has been successfully verified.'],
            ResponseAlias::HTTP_OK,
        ];
    }
}
