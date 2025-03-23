<?php

namespace App\Swagger\Annotations\Controllers;

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
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Count of addresses per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Number of page",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AddressResource")),
     *              @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
     *           )
     *     )
     * )
     */
    public function index(Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/addresses/{id}",
     *     summary="Get address by ID",
     *     tags={"Address"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Address ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Address found",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="country", type="string", example="Russia"),
     *             @OA\Property(property="subject", type="string", example="Tver oblast"),
     *             @OA\Property(property="city", type="string", example="Kalyazin"),
     *             @OA\Property(property="street", type="string", example="Kominterna"),
     *             @OA\Property(property="house", type="string", example="105/2"),
     *             @OA\Property(property="flat", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *             @OA\Property(property="link", type="string", example="http://localhost:8080/api/v1/addresses/1")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Address not found.")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/addresses",
     *     summary="Create address",
     *     tags={"Address"},
     *
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             required={"country", "city", "street", "house"},
     *             @OA\Property(property="country", type="string", example="Russia"),
     *             @OA\Property(property="subject", type="string", example="Tver oblast"),
     *             @OA\Property(property="city", type="string", example="Kalyazin"),
     *             @OA\Property(property="street", type="string", example="Kominterna"),
     *             @OA\Property(property="house", type="string", example="105/2"),
     *             @OA\Property(property="flat", type="integer", example=1)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Address created successfully.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation failed.")
     *         )
     *     )
     * )
     */
    public function store(StoreAddressRequest $request)
    {
    }

    /**
     * @OA\Patch(
     *      path="/api/v1/addresses",
     *      summary="Update address",
     *      tags={"Address"},
     *
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="Address ID",
     *           @OA\Schema(type="integer", example=1)
     *       ),
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="country", type="string", example="Russia"),
     *              @OA\Property(property="subject", type="string", example="Tver oblast"),
     *              @OA\Property(property="city", type="string", example="Kalyazin"),
     *              @OA\Property(property="street", type="string", example="Kominterna"),
     *              @OA\Property(property="house", type="string", example="105/2"),
     *              @OA\Property(property="flat", type="integer", example=1)
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Address updated successfully.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Address created successfully.")
     *          )
     *      ),
     *
     *     @OA\Response(
     *           response=404,
     *           description="Address not found",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Address not found.")
     *           )
     *       )
     *  )
     */
    public function update(UpdateAddressRequest $request, int $id)
    {
    }

    public function destroy(int $id)
    {
    }
}
