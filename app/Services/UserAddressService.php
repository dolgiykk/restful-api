<?php

namespace App\Services;

use App\Http\Resources\AddressResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserAddressService
{
    /**
     * @param int $userId
     * @param array<string, mixed> $addressIds
     * @return array<mixed>
     */
    public function attach(array $addressIds, int $userId): array
    {
        $user = User::find($userId);

        if (! $user) {
            throw new ModelNotFoundException(__('user.not_found'));
        }

        $user->addresses()->syncWithoutDetaching($addressIds);

        return [
            ['message' => __('actions.attached_success')],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param int $userId
     * @param array<string, mixed> $addressIds
     * @return array<mixed>
     */
    public function detach(array $addressIds, int $userId): array
    {
        $user = User::find($userId);

        if (! $user) {
            return [
                ['message'=> __('user.not_found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        $user->addresses()->detach($addressIds);

        return [
            ['message' => __('actions.detached_success')],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param int $userId
     * @return array<mixed>
     */
    public function detachAll(int $userId): array
    {
        $user = User::find($userId);

        if (! $user) {
            return [
                ['message'=> __('user.not_found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        $user->addresses()->detach();

        return [
            ['message' => __('actions.detached_success')],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param int $userId
     * @return array<mixed>
     */
    public function getByUserId(int $userId): array
    {
        $cacheKey = "user:{$userId}:addresses";

        /** @var array<mixed> */
        return Cache::rememberForever($cacheKey, function () use ($userId) {
            $user = User::find($userId);

            if (! $user) {
                return [
                    ['message' => __('user.not_found')],
                    ResponseAlias::HTTP_NOT_FOUND,
                ];
            }

            return [
                ['data' => AddressResource::collection($user->addresses)->resolve()],
                ResponseAlias::HTTP_OK,
            ];
        });
    }
}
