<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Traits\UserAuthenticate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PasswordService
{
    use UserAuthenticate;

    /**
     * @param User|null $user
     * @param array<string, mixed> $data
     * @return array<mixed>
     */
    public function change(?User $user, array $data)
    {
        if ($checkAuth = $this->ensureAuthenticated($user)) {
            return $checkAuth;
        }

        /** @var User $user */
        if (! Hash::check($data['password'], $user->password)) {
            return [
                ['message' => __('auth.password.wrong_password')],
                ResponseAlias::HTTP_UNAUTHORIZED,
            ];
        }

        if ($data['password'] === $data['new_password']) {
            return [
                ['message' => __('auth.password.old_new_match')],
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        $user->update(['password' => Hash::make($data['new_password'])]);
        $user->tokens()
            ->where('id', '!=', $user->currentAccessToken()->id)
            ->delete();

        return [
            ['message' => __('auth.password.changed_successfully')],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<mixed>
     */
    public function sendResetToken(array $data): array
    {
        $status = Password::sendResetLink(['email' => $data['email']]);

        return [
            ['message' => __($status)],
            $status === Password::RESET_LINK_SENT ? ResponseAlias::HTTP_OK : ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }

    /**
     * @param string $email
     * @param string $token
     * @return array<mixed>
     */
    public function showResetForm(string $email, string $token): array
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $resetRecord || ! isset($resetRecord->token) || ! Hash::check($token, $resetRecord->token)) {
            return [
                ['errors' => __('auth.password.invalid_token')],
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        return [
            ['token' => $token, 'email' => $email],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<mixed>
     */
    public function reset(array $data): array
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->first();

        $token = $data['token'];

        if (! $resetRecord || ! isset($resetRecord->token) || ! Hash::check($token, $resetRecord->token)) {
            return [
                ['errors' => __('auth.password.invalid_token')],
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            ];
        }

        /** @var string $status */
        $status = Password::reset(
            Arr::only($data, ['email', 'password', 'password_confirmation', 'token']),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return [
            ['message' => __($status)],
            $status === Password::PASSWORD_RESET ? ResponseAlias::HTTP_OK : ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
        ];
    }
}
