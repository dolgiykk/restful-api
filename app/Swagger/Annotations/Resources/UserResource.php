<?php

namespace App\Swagger\Annotations\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="login", type="string", example="login"),
 *     @OA\Property(property="email", type="string", example="test@test.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="second_name", type="string", example="Smith"),
 *     @OA\Property(property="birthday", type="string", format="date", example="1995-05-16"),
 *     @OA\Property(property="sex", type="string", example="male"),
 *     @OA\Property(property="link", type="string", readOnly=true, example="http://localhost:8080/api/v1/users/1")
 * )
 */
class UserResource extends JsonResource
{
}
