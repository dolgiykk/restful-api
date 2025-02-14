<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      summary="Login via login",
     *      tags={"Auth"},
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="login", type="string", example="SomeName"),
     *              @OA\Property(property="password", type="string", example="password123")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="User successfully authorized.",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="5|EtCJlDMEjmuP7VlQWRJt5iovPD8vxIMBr4BV3jJnb9a2a643")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Wrong credentials.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found.")
     *          )
     *      )
     *  )
     */
    public function login(Request $request)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/logout",
     *      summary="Logout",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Logged out successfully.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Logged out successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthenticated.")
     *          )
     *      )
     *  )
     */
    public function logout()
    {
    }
}
