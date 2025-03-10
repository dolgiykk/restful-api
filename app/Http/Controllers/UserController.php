<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    private UserService $userService;

    const int DEFAULT_PER_PAGE = 10;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page') ?: self::DEFAULT_PER_PAGE;

        return response()->json($this->userService->getAll($perPage), ResponseAlias::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $userResource = $this->userService->getOne($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json($userResource, ResponseAlias::HTTP_OK);
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], ResponseAlias::HTTP_CREATED);
    }

    /**
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($request->validated(), $id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user,
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], ResponseAlias::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'User deleted successfully.'], ResponseAlias::HTTP_OK);
    }
}
