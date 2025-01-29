<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/api/v1/register",
 *     summary="Register user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(property="name", type="string", example="Some Name"),
 *                     @OA\Property(property="email", type="string", example="some@email.ru"),
 *                     @OA\Property(property="password", type="string", example="password123"),
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User registered successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *          response=422,
 *          description="Validation error.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="The email has already been taken.")
 *          )
 *      ),
 * ),
 * @OA\Post(
 *      path="/api/v1/login",
 *      summary="Login via email",
 *      tags={"Auth"},
 *      @OA\RequestBody(
 *          @OA\JsonContent(
 *              allOf={
 *                  @OA\Schema(
 *                      @OA\Property(property="email", type="string", example="some@email.ru"),
 *                      @OA\Property(property="password", type="string", example="password123"),
 *                  )
 *              }
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="User successfully authorized.",
 *          @OA\JsonContent(
 *              @OA\Property(property="token", type="string", example="5|EtCJlDMEjmuP7VlQWRJt5iovPD8vxIMBr4BV3jJnb9a2a643")
 *          )
 *      ),
 *      @OA\Response(
 *          response=401,
 *          description="Wrong credentials.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="User not found.")
 *          )
 *      ),
 *  ),
 */
class AuthController extends Controller
{
}
