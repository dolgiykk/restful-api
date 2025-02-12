<?php

namespace Database\Factories;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PersonalAccessTokenFactory extends Factory
{
    protected $model = PersonalAccessToken::class;

    public function definition(): array
    {
        return [
            'tokenable_type' => User::class,
            'tokenable_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'token' => Str::random(10),
            'abilities' => $this->faker->word(),
            'last_used_at' => Carbon::now(),
            'expires_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
