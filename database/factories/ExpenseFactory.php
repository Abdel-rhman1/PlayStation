<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Tenant;
use App\Enums\ExpenseType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'type' => $this->faker->randomElement(ExpenseType::cases()),
            'description' => $this->faker->sentence(),
            'date' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ];
    }
}
