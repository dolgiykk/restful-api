<?php

namespace App\Http\Controllers;

use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private AddressService $addressService;

    const int DEFAULT_PER_PAGE = 10;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page') ?: self::DEFAULT_PER_PAGE;

        return response()->json(...$this->addressService->getAll($request, $perPage));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json(...$this->addressService->getOne($id));
    }

    /**
     * @param StoreAddressRequest $request
     * @return JsonResponse
     */
    public function store(StoreAddressRequest $request): JsonResponse
    {
        return response()->json(...$this->addressService->createAddress($request->validated()));
    }

    /**
     * @param UpdateAddressRequest $request
     * @return JsonResponse
     */
    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        return response()->json(...$this->addressService->updateAddress($request->validated(), $id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return response()->json(...$this->addressService->deleteAddress($id));
    }
}
