<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_number' =>Str::random(10),
            'type' => $this->faker->randomElement(['current' ,'saving']),
            'balance' =>$this->faker->randomFloat(2, 10, 100),
            'user_id'=>1
        ];
    }
}
