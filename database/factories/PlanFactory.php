<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
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
            'name' => $this->faker->unique()->randomElement(['Starter', 'Pro', 'Enterprise']),
            'price' => $this->faker->randomElement([29.99, 59.99, 99.99]),
            'device_limit' => $this->faker->randomElement([5, 15, 50]),
        ];
    }
}
