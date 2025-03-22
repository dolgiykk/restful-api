<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserService
{
    /**
     * @param int $perPage
     * @param Request $request
     * @return array<mixed>
     */
    public function getAll(int $perPage, Request $request): array
    {
        /** @var string $page */
        $page = $request->query('page') ?? '1';

        $filterParams = http_build_query($request->except('page', 'perPage'));
        $cacheKey = "users:page:{$page}:per_page:{$perPage}:filters:{$filterParams}";

        return Cache::tags(['users_list'])->rememberForever($cacheKey, function () use ($perPage, $request) {
            $users = User::query()
                ->filter($request)
                ->paginate($perPage);

            return [
                'data' => UserResource::collection($users)->resolve(),
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'next_page_url' => $users->nextPageUrl(),
                    'prev_page_url' => $users->previousPageUrl(),
                ],
            ];
        });
    }

    /**
     * @param int $id
     * @return array<mixed>
     */
    public function getOne(int $id): mixed
    {
        /** @var array<mixed> */
        return Cache::rememberForever("user:{$id}", function () use ($id) {
            $user = User::find($id);

            if (! $user) {
                return [
                    ['message'=> __('user.not_found')],
                    ResponseAlias::HTTP_NOT_FOUND,
                ];
            }

            return [
                ['data'=> new UserResource($user)],
                ResponseAlias::HTTP_OK,
            ];
        });
    }

    /**
     * @param array<string, mixed> $data
     * @return array<mixed>
     */
    public function createUser(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        Cache::tags(['users_list'])->flush();

        return [
            [
                'message'=> __('actions.created_success'),
                'data'=> new UserResource($user),
            ],
            ResponseAlias::HTTP_CREATED,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @param int $id
     * @return array<mixed>
     */
    public function updateUser(array $data, int $id): array
    {
        $user = User::find($id);

        if (! $user) {
            return [
                ['message'=> __('user.not_found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        if (! $user->update($data)) {
            return [
                ['message'=> __('actions.update_failed')],
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
            ];
        }

        Cache::lock('cache_update_lock', 10)->block(0, function () use ($id) {
            Cache::tags(['users_list'])->flush();
            Cache::forget("user:{$id}");
        });

        return [
            [
                'message'=> __('actions.updated_success'),
                'data'=> new UserResource($user),
            ],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param int $id
     * @return array<mixed>
     * @throws LockTimeoutException
     */
    public function deleteUser(int $id): array
    {
        $user = User::find($id);

        if (! $user) {
            return [
                ['message'=> __('user.not_found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        if (! $user->delete()) {
            return [
                ['message'=> __('actions.delete_failed')],
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
            ];
        }

        Cache::lock('cache_update_lock', 10)->block(0, function () use ($id) {
            Cache::tags(['users_list'])->flush();
            Cache::forget("user:{$id}");
        });

        return [
            ['message'=> __('actions.deleted_success')],
            ResponseAlias::HTTP_OK,
        ];
    }
}
