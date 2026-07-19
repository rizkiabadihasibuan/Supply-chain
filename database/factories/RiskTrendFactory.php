<?php

namespace Database\Factories;

use App\Models\RiskTrend;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class RiskTrendFactory extends Factory
{
    protected $model = RiskTrend::class;

    public function definition(): array
    {
        $prev = fake()->randomFloat(2, 0, 100);
        $curr = fake()->randomFloat(2, 0, 100);
        $diff = $curr - $prev;
        $change = $prev > 0 ? ($diff / $prev) * 100 : 0;
        
        return [
            'country_id' => Country::factory(),
            'trend_type' => fake()->randomElement(['Short-term', 'Medium-term', 'Long-term']),
            'previous_score' => $prev,
            'current_score' => $curr,
            'change_percentage' => $change,
            'trend_direction' => $diff > 1 ? 'Up' : ($diff < -1 ? 'Down' : 'Stable'),
            'analyzed_at' => now(),
        ];
    }
}
