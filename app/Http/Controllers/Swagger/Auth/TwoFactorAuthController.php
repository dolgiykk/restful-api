<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorAuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/2fa/enable",
     *      summary="Enable Two Factor Authentication",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\Response(
     *          response=200,
     *          description="Two Factor Authentication enabled successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="two_factor_secret", type="string", example="DAQJRAKOTDG37DVU"),
     *              @OA\Property(property="two_factor_qr_code_base64", type="string", description="Base64 encoded QR code image for 2FA setup")
     *          )
     *      ),
     *
     *      @OA\Response(
     *           response=401,
     *           description="Unauthenticated. The user must be logged in to enable 2FA.",
     *           @OA\JsonContent(
     *               @OA\Property(property="errors", type="string", example="Unauthenticated.")
     *           )
     *      ),
     *
     *      @OA\Response(
     *           response=400,
     *           description="Two Factor Authentication is already enabled.",
     *           @OA\JsonContent(
     *               @OA\Property(property="errors", type="string", example="Two Factor Authentication is already enabled.")
     *           )
     *      )
     *  )
     */
    public function enable(Request $request)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/2fa/verify",
     *      summary="Verify Two Factor Authentication",
     *      tags={"Auth"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="two_factor_code", type="string", example="123456")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Two Factor Authentication verified successfully.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Two factor authentication verified successfully.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Invalid or expired two-factor authentication code.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Invalid or expired two-factor authentication code.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Invalid input or missing parameters.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The provided two-factor authentication code is invalid.")
     *          )
     *      )
     *  )
     */
    public function verify(Request $request)
    {
    }
}
