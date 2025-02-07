<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\Controller;

/**
 * @OA\Post(
 *     path="/api/v1/register",
 *     summary="Register user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="login", type="string", example="SomeName"),
 *             @OA\Property(property="email", type="string", example="some@email.ru"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="User registered successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User registered successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The email has already been taken.")
 *         )
 *     ),
 * ),
 * @OA\Post(
 *     path="/api/v1/login",
 *     summary="Login via login",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="login", type="string", example="SomeName"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully authorized.",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="5|EtCJlDMEjmuP7VlQWRJt5iovPD8vxIMBr4BV3jJnb9a2a643")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Wrong credentials.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="User not found.")
 *         )
 *     )
 * ),
 * @OA\Post(
 *     path="/api/v1/logout",
 *     summary="Logout",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Logged out successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Logged out successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * ),
 * @OA\Post(
 *     path="/api/v1/change-password",
 *     summary="Change password",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="password", type="string", example="password123"),
 *              @OA\Property(property="new_password", type="string", example="new_password123"),
 *              @OA\Property(property="new_password_confirmation", type="string", example="new_password123"),
 *          )
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="Change password and drop all sessions.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Password changed successfully. All sessions was closed.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Old and new passwords matched.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Old and new passwords matched.")
 *         )
 *     ),
 *     @OA\Response(
 *          response=401,
 *          description="Invalid credentials.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Invalid credentials.")
 *          )
 *      )
 * ),
 * @OA\Post(
 *     path="/api/v1/close-other-sessions",
 *     summary="Close other sessions",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *          response=200,
 *          description="All sessions on other devices were closed.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="All sessions on other devices were closed successfully.")
 *          )
 *     ),
 *     @OA\Response(
 *          response=401,
 *          description="Unauthenticated.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Unauthenticated.")
 *          )
 *      )
 * ),
 * @OA\Post(
 *     path="/api/v1/enable2FA",
 *     summary="Enable Two Factor Authentification",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Two Factor Authentification enabled successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="two_factor_secret", type="string", example="DAQJRAKOTDG37DVU"),
 *             @OA\Property(property="two_factor_qr_code_base64", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *          response=401,
 *          description="Unauthenticated.",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Unauthenticated.")
 *          )
 *     )
 * ),
 * @OA\Post(
 *      path="/api/v1/verify2FA",
 *      summary="Verify Two Factor Authentification code",
 *      tags={"Auth"},
 *      security={{"bearerAuth":{}}},
 *      @OA\RequestBody(
 *           @OA\JsonContent(
 *               type="object",
 *               @OA\Property(property="two_factor_code", type="numeric", example="123456"),
 *           )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Two Factor Authentification verified successfully.",
 *           @OA\JsonContent(
 *               @OA\Property(property="message", type="string", example="2FA verified successfully."),
 *           )
 *      ),
 *      @OA\Response(
 *            response=401,
 *            description="Unauthenticated.",
 *            @OA\JsonContent(
 *                @OA\Property(property="message", type="string", example="Unauthenticated.")
 *            )
 *      ),
 *  ),
 * @OA\Post(
 *       path="/api/v1/forgot-password",
 *       summary="Create token and send mail to reset password",
 *       tags={"Auth"},
 *       @OA\RequestBody(
 *            @OA\JsonContent(
 *                type="object",
 *                @OA\Property(property="email", type="string", example="some@email.ru"),
 *            )
 *       ),
 *       @OA\Response(
 *            response=200,
 *            description="Send reset password mail.",
 *            @OA\JsonContent(
 *                @OA\Property(property="message", type="string", example="We have emailed your password reset link."),
 *            )
 *       ),
 *       @OA\Response(
 *             response=422,
 *             description="The selected email is invalid.",
 *             @OA\JsonContent(
 *                 @OA\Property(property="message", type="string", example="Invalid or expired token.")
 *             )
 *       ),
 *   ),
 * @OA\Get(
 *      path="/api/v1/reset-password",
 *      summary="Display reset password form",
 *      tags={"Auth"},
 *      @OA\Parameter(
 *          name="email",
 *          in="query",
 *          description="The email address for which the password reset is requested",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *              example="user@example.com"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="token",
 *          in="query",
 *          description="The token for password reset sent to the user's email",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *              example="2bcc51a1eae1daef3b8390a2d9dd5ba00d0ffa8641ea671da4a274b8bac325"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="The password reset form is successfully displayed",
 *          @OA\JsonContent(
 *              @OA\Property(property="email", type="string", example="user@example.com"),
 *              @OA\Property(property="token", type="string", example="2bcc51a1eae1daef3b8390a2d9dd5ba00d0ffa8641ea671da4a274b8bac325")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Invalid or expired token",
 *          @OA\JsonContent(
 *              @OA\Property(property="errors", type="string", example="Invalid or expired token.")
 *          )
 *      ),
 *  ),
 * @OA\Post(
 *        path="/api/v1/reset-password",
 *        summary="Create token and send mail to reset password",
 *        tags={"Auth"},
 *        @OA\RequestBody(
 *             @OA\JsonContent(
 *                 type="object",
 *                 @OA\Property(property="email", type="string", example="some@email.ru"),
 *                 @OA\Property(property="token", type="string"),
 *                 @OA\Property(property="password", type="string"),
 *                 @OA\Property(property="password_confirmation", type="string"),
 *             )
 *        ),
 *        @OA\Response(
 *             response=200,
 *             description="Send reset password mail.",
 *             @OA\JsonContent(
 *                 @OA\Property(property="message", type="string", example="Your password has been reset."),
 *             )
 *        ),
 *        @OA\Response(
 *              response=422,
 *              description="The selected email is invalid.",
 *              @OA\JsonContent(
 *                  @OA\Property(property="errors", type="object")
 *              )
 *        ),
 *        @OA\Response(
 *              response=400,
 *              description="Invalid or expired token.",
 *              @OA\JsonContent(
 *                  @OA\Property(property="errors", type="string", example="Invalid or expired token.")
 *              )
 *         ),
 *    ),
 */
class AuthController extends Controller
{
}
