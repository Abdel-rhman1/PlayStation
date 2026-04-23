<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 week', 'now');
        $end = (clone $start)->modify('+' . $this->faker->numberBetween(30, 240) . ' minutes');

        return [
            'tenant_id' => \App\Models\Tenant::factory(),
            'device_id' => \App\Models\Device::factory(),
            'user_id' => \App\Models\User::factory(),
            'started_at' => $start,
            'ended_at' => $end,
            'total_price' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
