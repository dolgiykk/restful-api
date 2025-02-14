<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    const int DEFAULT_PER_PAGE = 10;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page') ?: self::DEFAULT_PER_PAGE;
        $users = User::query()->paginate($perPage);

        return response()->json([
            'data' => UserResource::collection($users),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
            ],
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(new UserResource($user), ResponseAlias::HTTP_OK);
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        /** @var string $password */
        $password = $request->input('password');

        $user = User::create([
            'login' => $request->input('login'),
            'email' => $request->input('email'),
            'password' => Hash::make($password),
        ]);

        return response()->json(['message' => 'User created successfully.'], ResponseAlias::HTTP_CREATED);
    }

    /**
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], ResponseAlias::HTTP_NOT_FOUND);
        }

        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => new UserResource($user),
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found.'], ResponseAlias::HTTP_NOT_FOUND);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.'], ResponseAlias::HTTP_OK);
    }
}
