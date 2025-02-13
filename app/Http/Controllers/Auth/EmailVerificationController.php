<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmailVerificationController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user?->currentAccessToken()) {
            return response()->json(['message' => 'Unauthenticated.'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], ResponseAlias::HTTP_OK);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent.'], ResponseAlias::HTTP_ACCEPTED);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $user = User::findOrFail($request->route('id'));

        /** @var string $hash */
        $hash = $request->route('hash');

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], ResponseAlias::HTTP_BAD_REQUEST);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], ResponseAlias::HTTP_OK);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email has been successfully verified.'], ResponseAlias::HTTP_OK);
    }
}
