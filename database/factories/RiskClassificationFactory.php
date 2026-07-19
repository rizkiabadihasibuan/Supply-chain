<?php

namespace Database\Factories;

use App\Models\RiskClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiskClassificationFactory extends Factory
{
    protected $model = RiskClassification::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Very Low', 'Low', 'Medium', 'High', 'Critical']),
            'min_score' => fake()->randomFloat(2, 0, 49),
            'max_score' => fake()->randomFloat(2, 50, 100),
            'color_code' => fake()->hexColor(),
            'description' => fake()->sentence(),
        ];
    }
}
