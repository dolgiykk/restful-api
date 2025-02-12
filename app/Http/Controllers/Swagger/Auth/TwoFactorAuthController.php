<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorAuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/enable2FA",
     *      summary="Enable Two Factor Authentification",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Two Factor Authentification enabled successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="two_factor_secret", type="string", example="DAQJRAKOTDG37DVU"),
     *              @OA\Property(property="two_factor_qr_code_base64", type="string")
     *          )
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
    public function enable2FA(Request $request)
    {
    }

    /**
     * @OA\Post(
     *       path="/api/v1/verify2FA",
     *       summary="Verify Two Factor Authentification code",
     *       tags={"Auth"},
     *       security={{"bearerAuth":{}}},
     *
     *       @OA\RequestBody(
     *            @OA\JsonContent(
     *                type="object",
     *                @OA\Property(property="two_factor_code", type="numeric", example="123456"),
     *            )
     *       ),
     *
     *       @OA\Response(
     *            response=200,
     *            description="Two Factor Authentification verified successfully.",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="2FA verified successfully."),
     *            )
     *       ),
     *
     *       @OA\Response(
     *             response=401,
     *             description="Unauthenticated.",
     *             @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Unauthenticated.")
     *             )
     *       ),
     *   )
     */
    public function verify2FA(Request $request)
    {
    }

    /**
     * @OA\Post(
     *        path="/api/v1/forgot-password",
     *        summary="Create token and send mail to reset password",
     *        tags={"Auth"},
     *
     *        @OA\RequestBody(
     *             @OA\JsonContent(
     *                 type="object",
     *                 @OA\Property(property="email", type="string", example="some@email.ru"),
     *             )
     *        ),
     *
     *        @OA\Response(
     *             response=200,
     *             description="Send reset password mail.",
     *             @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="We have emailed your password reset link."),
     *             )
     *        ),
     *
     *        @OA\Response(
     *              response=422,
     *              description="The selected email is invalid.",
     *              @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="Invalid or expired token.")
     *              )
     *        ),
     *    )
     */
}
