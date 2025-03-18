<?php

namespace App\Http\Controllers\Swagger;

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
     *              @OA\Property(property="data", type="array", @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="login", type="string", example="login"),
     *                  @OA\Property(property="email", type="string", example="test@test.com"),
     *                  @OA\Property(property="email_verified_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                  @OA\Property(property="first_name", type="string", example="John"),
     *                  @OA\Property(property="last_name", type="string", example="Doe"),
     *                  @OA\Property(property="second_name", type="string", example="Smith"),
     *                  @OA\Property(property="birthday", type="string", format="date", example="1995-05-16"),
     *                  @OA\Property(property="sex", type="string", example="male")
     *              )),
     *              @OA\Property(property="pagination", type="object", @OA\Property(property="total", type="integer", example=1),
     *                  @OA\Property(property="per_page", type="integer", example=10),
     *                  @OA\Property(property="current_page", type="integer", example=10),
     *                  @OA\Property(property="last_page", type="integer", example=4),
     *                  @OA\Property(property="next_page_url", type="string", example="http://localhost:8080/api/v1/users?page=2"),
     *                  @OA\Property(property="prev_page_url", type="string", example=null)
     *              )
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
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="login", type="string", example="login"),
     *              @OA\Property(property="email", type="string", example="test@test.com"),
     *              @OA\Property(property="email_verified_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *              @OA\Property(property="first_name", type="string", example="John"),
     *              @OA\Property(property="last_name", type="string", example="Doe"),
     *              @OA\Property(property="second_name", type="string", example="Smith"),
     *              @OA\Property(property="birthday", type="string", format="date", example="1995-05-16"),
     *              @OA\Property(property="sex", type="string", example="male")
     *          )
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
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="login", type="string", example="login"),
     *              @OA\Property(property="email", type="string", example="test@test.com"),
     *              @OA\Property(property="email_verified_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *              @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *              @OA\Property(property="first_name", type="string", example="John"),
     *              @OA\Property(property="last_name", type="string", example="Doe"),
     *              @OA\Property(property="second_name", type="string", example="Smith"),
     *              @OA\Property(property="birthday", type="string", format="date", example="1995-05-16"),
     *              @OA\Property(property="sex", type="string", example="male"),
     *          )
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
}
