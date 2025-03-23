<?php

namespace App\Swagger\Annotations;

/**
 * @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     @OA\Property(property="per_page", type="integer", example=10),
 *     @OA\Property(property="current_page", type="integer", example=10),
 *     @OA\Property(property="last_page", type="integer", example=4),
 *     @OA\Property(property="next_page_url", type="string", example="http://localhost:8080/api/v1/****?page=2"),
 *     @OA\Property(property="prev_page_url", type="string", example=null)
 * )
 */
class Pagination
{
}
