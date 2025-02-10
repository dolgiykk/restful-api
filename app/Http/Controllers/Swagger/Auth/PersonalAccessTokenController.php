<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/tokens",
     *     summary="Close other sessions",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="All sessions on other devices were closed.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="auth_token"),
     *                     @OA\Property(property="tokenable_type", type="string", example="App\\Models\\User"),
     *                     @OA\Property(property="tokenable_id", type="integer", example=1),
     *                     @OA\Property(property="abilities", type="array",
     *                         @OA\Items(type="string", example="*")
     *                     ),
     *                     @OA\Property(property="last_used_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                     @OA\Property(property="expires_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z")
     *             )),
     *             @OA\Property(property="pagination", type="object", @OA\Property(property="total", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=10),
     *                 @OA\Property(property="last_page", type="integer", example=4),
     *                 @OA\Property(property="next_page_url", type="string", example="http://localhost:8080/api/v1/users?page=2"),
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
    public function index(Request $request)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/close-other-sessions",
     *      summary="Close other sessions",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Response(
     *           response=200,
     *           description="All sessions on other devices were closed.",
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
     *       )
     *  )
     */
    public function revokeOtherTokens(Request $request)
    {
    }
}
