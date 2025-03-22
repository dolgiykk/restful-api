<?php

namespace App\Http\Controllers\Swagger\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/password/change",
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
     *          description="Password successfully changed and all sessions (except the current one) were closed.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Password changed successfully. All sessions were closed.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Old password and new password match.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Old password and new password cannot be the same.")
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
     *        path="/api/v1/password/forgot",
     *        summary="Generate reset token and send reset password email",
     *        tags={"Password"},
     *
     *        @OA\RequestBody(
     *             @OA\JsonContent(
     *                 type="object",
     *                 @OA\Property(property="email", type="string", example="some@email.ru", format="email"),
     *             )
     *        ),
     *
     *        @OA\Response(
     *             response=200,
     *             description="Password reset email sent successfully.",
     *             @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="We have emailed your password reset link."),
     *             )
     *        ),
     *
     *        @OA\Response(
     *              response=422,
     *              description="The email is invalid or not registered.",
     *              @OA\JsonContent(
     *                  @OA\Property(property="message", type="string", example="The selected email is invalid or not registered.")
     *              )
     *        ),
     *    )
     */
    public function forgot(Request $request)
    {
    }

    /**
     * @OA\Get(
     *       path="/api/v1/password/reset",
     *       summary="Display password reset form with valid token",
     *       tags={"Password"},
     *
     *       @OA\Parameter(
     *           name="email",
     *           in="query",
     *           description="The email address for which the password reset is requested",
     *           required=true,
     *           @OA\Schema(
     *               type="string",
     *               format="email",
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
     *           description="Password reset token and email are valid",
     *           @OA\JsonContent(
     *               @OA\Property(property="email", type="string", example="user@example.com"),
     *               @OA\Property(property="token", type="string", example="2bcc51a1eae1daef3b8390a2d9dd5ba00d0ffa8641ea671da4a274b8bac325")
     *           )
     *       ),
     *
     *       @OA\Response(
     *           response=422,
     *           description="The provided token is invalid or has expired",
     *           @OA\JsonContent(
     *               @OA\Property(property="errors", type="string", example="Invalid or expired token.")
     *           )
     *       ),
     *   )
     */
    public function showResetForm(Request $request)
    {
    }

    /**
     * @OA\Post(
     *      path="/api/v1/password/reset",
     *      summary="Reset the user's password",
     *      tags={"Password"},
     *
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="some@email.ru"),
     *              @OA\Property(property="token", type="string", example="2bcc51a1eae1daef3b8390a2d9dd5ba00d0ffa8641ea671da4a274b8bac325"),
     *              @OA\Property(property="password", type="string", example="newPassword123"),
     *              @OA\Property(property="password_confirmation", type="string", example="newPassword123")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Password has been successfully reset.",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Your password has been reset.")
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="The selected email is invalid or the password reset process failed.",
     *          @OA\JsonContent(
     *              @OA\Property(property="errors", type="string", example="The provided email is not registered.")
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
