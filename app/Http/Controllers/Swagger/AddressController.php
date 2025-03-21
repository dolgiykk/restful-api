<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\StoreAddressRequest;
use App\Http\Requests\Address\UpdateAddressRequest;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/addresses",
     *     summary="Addresses list",
     *     tags={"Address"},
     *
     *     @OA\Parameter(
     *         name=""
     *     )
     * )
     */
    public function index(Request $request)
    {
    }

    public function show(int $id)
    {
    }

    public function store(StoreAddressRequest $request)
    {
    }

    public function update(UpdateAddressRequest $request, int $id)
    {
    }

    public function destroy(int $id)
    {
    }
}
