<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PhysicalProgress>
 */
class PhysicalProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'weight' => $this->faker->randomFloat(2, 50, 100),
            'measurements' => $this->faker->randomFloat(2, 150, 200),
            'sports_performance' => $this->faker->randomElement(['Good', 'Average', 'Excellent']),
            'status' => 'Pending',
        ];
    }
}