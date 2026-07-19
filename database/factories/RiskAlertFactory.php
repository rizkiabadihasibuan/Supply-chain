<?php

namespace Database\Factories;

use App\Models\RiskAlert;
use App\Models\Country;
use App\Models\RiskScore;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiskAlertFactory extends Factory
{
    protected $model = RiskAlert::class;

    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'risk_score_id' => RiskScore::factory(),
            'alert_type' => fake()->randomElement(['Weather Disruption', 'Economic Collapse', 'Geopolitical Conflict', 'Port Strike']),
            'severity' => fake()->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['Active', 'Resolved']),
            'resolved_at' => fake()->boolean(30) ? now() : null,
        ];
    }
}
