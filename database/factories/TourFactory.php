<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $starting_date = now()->addDays(rand(1, 8));
        return [
            'name' => fake()->text(20),
            'starting_date' => $starting_date,
            'ending_date' => (clone $starting_date)->addDays(rand(1, 10)),
            'price' => fake()->randomFloat(2, 10, 999),
        ];
    }
}
