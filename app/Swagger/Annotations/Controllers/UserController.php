<?php

namespace App\Swagger\Annotations\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/users",
     *      summary="Users list",
     *      tags={"User"},
     *
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          required=false,
     *          description="Count of users per page",
     *          @OA\Schema(type="integer", example=10)
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Number of page",
     *          @OA\Schema(type="integer", example=2)
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
     *              @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
     *          )
     *      )
     *  )
     */
    public function index(Request $request)
    {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/users/{id}",
     *      summary="Get user by ID",
     *      tags={"User"},
     *
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="User ID",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="User found",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found.")
     *          )
     *      )
     *  )
     */
    public function show(int $id)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/users",
     *      summary="Create user",
     *      tags={"User"},
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              required={"login", "email", "password"},
     *              @OA\Property(property="login", type="string", example="SomeName"),
     *              @OA\Property(property="email", type="string", example="some@email.ru"),
     *              @OA\Property(property="password", type="string", example="password123"),
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="User created successfully.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User created successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Validation error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The email has already been taken.")
     *          )
     *      ),
     *  )
     */
    public function store(Request $request)
    {
    }

    /**
     * @OA\Patch(
     *      path="/api/v1/users",
     *      summary="Update user",
     *      tags={"User"},
     *
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="User ID",
     *           @OA\Schema(type="integer", example=1)
     *       ),
     *
     *      @OA\RequestBody(
     *           @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="User updated successfully.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User created successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Validation error.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The email has already been taken.")
     *          )
     *      ),
     *
     *     @OA\Response(
     *           response=404,
     *           description="User not found",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="User not found.")
     *           )
     *       )
     *  )
     */
    public function update(Request $request)
    {
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     summary="Delete user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="User id for delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully deleted.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=503,
     *         description="Failed to delete user.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete user.")
     *         )
     *     )
     * )
     */
    public function destroy(int $id)
    {
    }
}
