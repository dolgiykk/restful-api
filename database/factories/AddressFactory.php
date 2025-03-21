<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'country' => $this->faker->country(),
            'subject' => $this->faker->word(),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'house' => $this->faker->word(),
            'flat' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
