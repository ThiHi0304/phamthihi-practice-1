<?php

namespace Database\Factories;

use App\Models\Phone;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->phoneNumber,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
