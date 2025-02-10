<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
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
