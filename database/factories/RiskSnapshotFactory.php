<?php

namespace Database\Factories;

use App\Models\RiskSnapshot;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiskSnapshotFactory extends Factory
{
    protected $model = RiskSnapshot::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'weather_data' => [
                'temperature' => fake()->randomFloat(2, -10, 40),
                'condition' => fake()->randomElement(['Sunny', 'Rainy', 'Stormy', 'Windy']),
                'humidity' => fake()->numberBetween(20, 100),
            ],
            'economic_data' => [
                'inflation_rate' => fake()->randomFloat(2, 0.5, 15.0),
                'gdp_growth' => fake()->randomFloat(2, -2.0, 8.0),
                'currency_stability' => fake()->randomElement(['Stable', 'Volatile']),
            ],
            'news_data' => [
                'headline' => fake()->sentence(),
                'sentiment' => fake()->randomElement(['Positive', 'Neutral', 'Negative']),
            ],
            'port_data' => [
                'active_vessels' => fake()->numberBetween(10, 200),
                'congestion_index' => fake()->randomFloat(2, 0, 10),
            ],
            'overall_status' => fake()->randomElement(['Normal', 'Warning', 'Critical']),
            'snapshot_time' => now(),
        ];
    }
}
