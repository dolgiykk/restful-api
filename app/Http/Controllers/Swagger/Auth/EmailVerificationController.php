<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * @OA\Post(
     *       path="/api/v1/email/verify/send",
     *       summary="Send Verification Email",
     *       description="Send an email verification link to the authenticated user.",
     *       tags={"Auth"},
     *       security={{"bearerAuth":{}}},
     *
     *       @OA\Response(
     *           response=202,
     *           description="Verification email sent.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Verification email sent.")
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=200,
     *           description="Email already verified.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Email already verified.")
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=401,
     *           description="Unauthenticated.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthenticated.")
     *           )
     *       )
     *   ),
     */
    public function send(Request $request)
    {
    }

    /**
     * @OA\Get(
     *       path="/api/v1/email/verify/{id}/{hash}",
     *       summary="Verify Email",
     *       description="Verify the user's email using the link from the verification email.",
     *       tags={"Auth"},
     *
     *       @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="User ID",
     *           @OA\Schema(type="integer")
     *       ),
     *
     *       @OA\Parameter(
     *           name="hash",
     *           in="path",
     *           required=true,
     *           description="Email verification hash",
     *           @OA\Schema(type="string")
     *       ),
     *
     *       @OA\Response(
     *           response=200,
     *           description="Email has been successfully verified or already verified.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Email has been successfully verified.")
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=400,
     *           description="Invalid verification link.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Invalid verification link.")
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=404,
     *           description="User not found."
     *       )
     *   )
     */
    public function verify(Request $request)
    {
    }
}
