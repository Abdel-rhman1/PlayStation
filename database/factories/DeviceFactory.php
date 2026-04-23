<?php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => \App\Models\Tenant::factory(),
            'branch_id' => \App\Models\Branch::factory(),
            'name' => 'Console ' . $this->faker->unique()->numberBetween(1, 100),
            'ip_address' => $this->faker->ipv4(),
            'hourly_rate' => $this->faker->randomElement([5, 10, 15, 20]),
            'status' => \App\Enums\DeviceStatus::OFF,
        ];
    }
}
