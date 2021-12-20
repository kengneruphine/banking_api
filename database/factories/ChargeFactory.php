<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'low_range' =>$this->faker->randomFloat(2, 10, 100),
            'high_range' =>$this->faker->randomFloat(2, 1000, 100000),
            'amount' =>$this->faker->randomFloat(2, 10, 10)

        ];
    }
}
