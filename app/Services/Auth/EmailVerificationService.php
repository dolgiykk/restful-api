<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\UserAuthenticate;
use Illuminate\Auth\Events\Verified;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailVerificationService
{
    use UserAuthenticate;

    /**
     * @param User|null $user
     * @return array<mixed>
     */
    public function send(?User $user): array
    {
        if ($checkAuth = $this->ensureAuthenticated($user)) {
            return $checkAuth;
        }

        /** @var User $user */
        if ($user->hasVerifiedEmail()) {
            return [
                ['message' => __('auth.email_verification.already_verified')],
                ResponseAlias::HTTP_OK,
            ];
        }

        $user->sendEmailVerificationNotification();

        return [
            ['message' => __('auth.email_verification.verification_sent')],
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
                ['message' => __('auth.email_verification.invalid_link')],
                ResponseAlias::HTTP_BAD_REQUEST,
            ];
        }

        if ($user->hasVerifiedEmail()) {
            return [
                ['message' => __('auth.email_verification.already_verified')],
                ResponseAlias::HTTP_OK,
            ];
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return [
            ['message' => __('auth.email_verification.verified_successfully')],
            ResponseAlias::HTTP_OK,
        ];
    }
}
