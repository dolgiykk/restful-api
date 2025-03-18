<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * @param int $perPage
     * @return array<mixed>
     */
    public function getAll(int $perPage, Request $request): array
    {
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
    }

    /**
     * @param int $id
     * @return UserResource
     */
    public function getOne(int $id): UserResource
    {
        $user = User::find($id);

        if (! $user) {
            throw new ModelNotFoundException('User not found.');
        }

        return new UserResource($user);
    }

    /**
     * @param array<string, mixed> $data
     * @return UserResource
     */
    public function createUser(array $data): UserResource
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * @param array<string, mixed> $data
     * @param int $id
     * @return UserResource
     */
    public function updateUser(array $data, int $id): UserResource
    {
        $user = User::find($id);

        if (! $user) {
            throw new ModelNotFoundException(__('user.not_found'));
        }

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * @param int $id
     * @return bool|null
     */
    public function deleteUser(int $id): bool|null
    {
        $user = User::find($id);

        if (! $user) {
            throw new ModelNotFoundException(__('user.not_found'));
        }

        return $user->delete();
    }
}
