<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/change-password",
     *      summary="Change password",
     *      tags={"Password"},
     *      security={{"bearerAuth":{}}},
     *
     *      @OA\RequestBody(
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="password", type="string", example="password123"),
     *               @OA\Property(property="new_password", type="string", example="new_password123"),
     *               @OA\Property(property="new_password_confirmation", type="string", example="new_password123"),
     *           )
     *       ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Change password and drop all sessions.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Password changed successfully. All sessions was closed.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Old and new passwords matched.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Old and new passwords matched.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *           response=401,
     *           description="Invalid credentials.",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Invalid credentials.")
     *           )
     *       )
     *  )
     */
    public function change(Request $request)
    {
    }

    /**
     * @OA\Post(
     *        path="/api/v1/forgot-password",
     *        summary="Create token and send mail to reset password",
     *        tags={"Password"},
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
    public function sendResetToken(Request $request)
    {
    }

    /**
     * @OA\Get(
     *       path="/api/v1/reset-password",
     *       summary="Display reset password form",
     *       tags={"Password"},
     *
     *       @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="The email address for which the password reset is requested",
     *           required=true,
     *           @OA\Schema(
     *               type="string",
     *               example="user@example.com"
     *           )
     *       ),
     *
     *       @OA\Parameter(
     *           name="token",
     *           in="query",
     *           description="The token for password reset sent to the user's email",
     *           required=true,
     *           @OA\Schema(
     *               type="string",
     *               example="2bcc51a1eae1daef3b8390a2d9dd5ba00d0ffa8641ea671da4a274b8bac325"
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=200,
     *           description="The password reset form is successfully displayed",
     *           @OA\JsonContent(
     *               @OA\Property(property="email", type="string", example="user@example.com"),
     *               @OA\Property(property="token", type="string", example="2bcc51a1eae1daef3b8390a2d9dd5ba00d0ffa8641ea671da4a274b8bac325")
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=422,
     *           description="Invalid or expired token",
     *           @OA\JsonContent(
     *               @OA\Property(property="errors", type="string", example="Invalid or expired token.")
     *           )
     *       ),
     *   )
     */
    public function showResetPasswordForm(Request $request)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/reset-password",
     *      summary="Reset password",
     *      tags={"Password"},
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="some@email.ru"),
     *              @OA\Property(property="token", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="password_confirmation", type="string")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Send reset password mail.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Your password has been reset.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="The selected email is invalid.",
     *          @OA\JsonContent(
     *              @OA\Property(property="errors", type="object")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Invalid or expired token.",
     *          @OA\JsonContent(
     *              @OA\Property(property="errors", type="string", example="Invalid or expired token.")
     *          )
     *      )
     *  )
     */
    public function reset(Request $request)
    {
    }
}
