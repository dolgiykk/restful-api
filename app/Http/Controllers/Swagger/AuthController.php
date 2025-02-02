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
 *             type="object",
 *             @OA\Property(property="login", type="string", example="SomeName"),
 *             @OA\Property(property="email", type="string", example="some@email.ru"),
 *             @OA\Property(property="password", type="string", example="password123"),
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
 *         response=422,
 *         description="Validation error.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The email has already been taken.")
 *         )
 *     ),
 * ),
 * @OA\Post(
 *     path="/api/v1/login",
 *     summary="Login via login",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="login", type="string", example="SomeName"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully authorized.",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="5|EtCJlDMEjmuP7VlQWRJt5iovPD8vxIMBr4BV3jJnb9a2a643")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Wrong credentials.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found.")
 *         )
 *     )
 * ),
 * @OA\Post(
 *     path="/api/v1/logout",
 *     summary="Logout",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logged out successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Logged out successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * ),
 * @OA\Post(
 *     path="/api/v1/change-password",
 *     summary="Change password",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="login", type="string", example="SomeName"),
 *              @OA\Property(property="password", type="string", example="password123"),
 *              @OA\Property(property="new_password", type="string", example="new_password123"),
 *          )
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="Change password and drop all sessions.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password changed successfully. All sessions was closed.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Old and new passwords matched.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Old and new passwords matched.")
 *         )
 *     ),
 *     @OA\Response(
 *          response=401,
 *          description="Invalid credentials.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Invalid credentials.")
 *          )
 *      )
 * ),
 * @OA\Post(
 *     path="/api/v1/close-other-sessions",
 *     summary="Close other sessions",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *          response=200,
 *          description="All sessions on other devices were closed.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="All sessions on other devices were closed successfully.")
 *          )
 *     ),
 *     @OA\Response(
 *          response=401,
 *          description="Unauthenticated.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Unauthenticated.")
 *          )
 *      )
 * )
 */
class AuthController extends Controller
{
}
