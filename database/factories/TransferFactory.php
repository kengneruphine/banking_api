<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sender_account_number' =>Str::random(10),
            'destinationaccount_number' =>Str::random(10),
            'destination_account_type' => $this->faker->randomElement(['current' ,'saving']),
            'sender_account_type' => $this->faker->randomElement(['current' ,'saving']),
            'status' => $this->faker->randomElement(['successful' ,'failed']),
            'currency' => $this->faker->words(3, true),
            'amount' =>$this->faker->randomFloat(2, 10, 100),
            'charge' =>$this->faker->randomFloat(2, 10, 100)

        ];
    }
}
