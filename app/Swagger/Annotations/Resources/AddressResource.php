<?php

namespace App\Swagger\Annotations\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="AddressResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="country", type="string", example="Russia"),
 *     @OA\Property(property="subject", type="string", example="Tver oblast"),
 *     @OA\Property(property="city", type="string", example="Kalyazin"),
 *     @OA\Property(property="street", type="string", example="Kominterna"),
 *     @OA\Property(property="house", type="string", example="105/2"),
 *     @OA\Property(property="flat", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2020-01-27T17:50:45Z"),
 *     @OA\Property(property="link", type="string", readOnly=true, example="http://localhost:8080/api/v1/addresses/1")
 * )
 */
class AddressResource extends JsonResource
{
}
