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
     *          required=true,
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
     *              @OA\Property(property="token", type="string", example="5|EtCJlDMEjmuP7VlQWRJt5iovPD8vxIMBr4BV3jJnb9a2a643"),
     *              @OA\Property(property="message", type="string", example="Logged in successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Wrong credentials.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User not found or wrong password.")
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
     *      description="Revokes the current access token of the authenticated user.",
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

    /**
     * @OA\Post(
     *      path="/api/v1/logout/other-devices",
     *      summary="Logout from other devices",
     *      description="Revokes all access tokens except the current one. The current session remains active.",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Response(
     *           response=200,
     *           description="All sessions on other devices were closed successfully.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="All sessions on other devices were closed successfully.")
     *           )
     *      ),
     *
     *      @OA\Response(
     *           response=401,
     *           description="Unauthenticated.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthenticated.")
     *           )
     *      )
     *  )
     */
    public function logoutOtherDevices()
    {
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/logout/device/{id}",
     *      summary="Logout specific device by token ID",
     *      description="Revokes a personal access token by ID. Current active token cannot be deleted.",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="Personal access token ID to delete",
     *           @OA\Schema(type="integer", example=10)
     *      ),
     *
     *      @OA\Response(
     *           response=200,
     *           description="Token deleted successfully.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Token deleted successfully.")
     *           )
     *      ),
     *
     *      @OA\Response(
     *           response=401,
     *           description="Unauthenticated.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthenticated.")
     *           )
     *       ),
     *
     *      @OA\Response(
     *           response=404,
     *           description="Token not found.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Token not found.")
     *           )
     *      ),
     *
     *      @OA\Response(
     *           response=403,
     *           description="Cannot delete current token.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Cannot delete current token.")
     *           )
     *      ),
     *
     *      @OA\Response(
     *           response=422,
     *           description="Token could not be deleted.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Token could not be deleted.")
     *           )
     *      )
     * )
     */
    public function logoutDevice()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tokens",
     *     summary="Tokens list",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of tokens per page (default: 15)",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Get all user tokens.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="auth_token"),
     *                 @OA\Property(property="tokenable_type", type="string", example="App\\Models\\User"),
     *                 @OA\Property(property="tokenable_id", type="integer", example=1),
     *                 @OA\Property(property="abilities", type="array",
     *                     @OA\Items(type="string", example="*")
     *                 ),
     *                 @OA\Property(property="last_used_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z")
     *             )),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="total", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=1),
     *                 @OA\Property(property="next_page_url", type="string", example="http://localhost:8080/api/v1/tokens?page=2"),
     *                 @OA\Property(property="prev_page_url", type="string", example=null)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function tokens()
    {
    }
}
