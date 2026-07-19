<?php

namespace Database\Factories;

use App\Models\RiskScore;
use App\Models\Country;
use App\Models\RiskSnapshot;
use App\Models\RiskClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiskScoreFactory extends Factory
{
    protected $model = RiskScore::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'classification_id' => RiskClassification::first() ? RiskClassification::first()->id : RiskClassification::factory(),
            'snapshot_id' => RiskSnapshot::factory(),
            'weather_score' => fake()->randomFloat(2, 0, 100),
            'economic_score' => fake()->randomFloat(2, 0, 100),
            'political_score' => fake()->randomFloat(2, 0, 100),
            'logistics_score' => fake()->randomFloat(2, 0, 100),
            'overall_score' => fake()->randomFloat(2, 0, 100),
            'final_risk_score' => fake()->randomFloat(2, 0, 100),
            'risk_level' => fake()->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'calculated_at' => now(),
            'source_version' => '1.0.0',
        ];
    }
}
