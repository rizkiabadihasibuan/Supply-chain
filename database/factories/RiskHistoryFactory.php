<?php

namespace Database\Factories;

use App\Models\RiskHistory;
use App\Models\Country;
use App\Models\RiskScore;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiskHistoryFactory extends Factory
{
    protected $model = RiskHistory::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'risk_score_id' => RiskScore::factory(),
            'total_risk_score' => fake()->randomFloat(2, 0, 100),
            'overall_score' => fake()->randomFloat(2, 0, 100),
            'risk_level' => fake()->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'calculated_date' => fake()->date(),
            'recorded_at' => now(),
        ];
    }
}
