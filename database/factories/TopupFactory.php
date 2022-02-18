<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recharge>
 */
class TopupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "number"=>$this->faker->phoneNumber,
            "type"=>$this->faker->randomElement(['prepaid', 'postpaid']),
            "amount"=>$this->faker->randomFloat(2, 0, 100),
            "user_id"=>$this->faker->numberBetween(1, 2),
            "status"=>$this->faker->randomElement(['requested', 'pending', 'successful', 'failed', 'cancelled', 'confirmed']),
        ];
    }
}
