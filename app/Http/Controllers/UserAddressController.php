<?php

namespace App\Http\Controllers;

use App\Http\Requests\Address\UserAddressAttachRequest;
use App\Http\Requests\Address\UserAddressDetachRequest;
use App\Services\UserAddressService;
use Illuminate\Http\JsonResponse;

class UserAddressController extends Controller
{
    private UserAddressService $userAddressService;

    public function __construct(UserAddressService $userAddressService)
    {
        $this->userAddressService = $userAddressService;
    }

    /**
     * @param UserAddressAttachRequest $request
     * @param int $userId
     * @return JsonResponse
     */
    public function attach(UserAddressAttachRequest $request, int $userId): JsonResponse
    {
        return response()->json(...$this->userAddressService->attach($request->validated(), $userId));
    }

    /**
     * @param UserAddressDetachRequest $request
     * @param int $userId
     * @return JsonResponse
     */
    public function detach(UserAddressDetachRequest $request, int $userId): JsonResponse
    {
        return response()->json(...$this->userAddressService->detach($request->validated(), $userId));
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function detachAll(int $userId): JsonResponse
    {
        return response()->json(...$this->userAddressService->detachAll($userId));
    }

    /**
     * @param int $userId
     * @return JsonResponse
     */
    public function getByUserId(int $userId): JsonResponse
    {
        return response()->json(...$this->userAddressService->getByUserId($userId));
    }
}
