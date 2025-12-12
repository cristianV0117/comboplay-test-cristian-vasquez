<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Basic Plan',
                'Standard Plan',
                'Premium Plan'
            ]),
            'price' => fake()->randomElement([9.99, 19.99, 29.99]),
            'duration_days' => fake()->randomElement([30, 90, 180]),
        ];
    }
}
