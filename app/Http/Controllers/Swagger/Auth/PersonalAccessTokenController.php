<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/tokens",
     *     summary="Tokens list",
     *     tags={"Access token"},
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
     *      path="/api/v1/logout-other-devices",
     *      summary="Logout other devices",
     *      tags={"Access token"},
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

    /**
     * @OA\Delete(
     *      path="/api/v1/logout-device",
     *      summary="Logout device by personal access token ID",
     *      tags={"Access token"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Parameter(
     *           name="id",
     *           in="query",
     *           required=true,
     *           description="Personal access token ID",
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
     *              response=404,
     *              description="Token not found.",
     *              @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Token not found.")
     *              )
     *          ),
     *
     *     @OA\Response(
     *            response=403,
     *            description="Cannot delete current token.",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Cannot delete current token.")
     *            )
     *        ),
     *
     *     @OA\Response(
     *             response=422,
     *             description="Token could not be deleted.",
     *             @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Token could not be deleted.")
     *             )
     *         ),
     *  )
     */
    public function destroy(Request $request)
    {
    }
}
