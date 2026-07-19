<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin Account ──────────────────────────────────
        User::factory()->admin()->create([
            'name'  => 'Administrator',
            'email' => 'admin@supplychain.com',
            'password' => Hash::make('password'),
        ]);

        // ── User Account ───────────────────────────────────
        User::factory()->create([
            'name'  => 'User Operator',
            'email' => 'user@supplychain.com',
            'password' => Hash::make('password'),
        ]);

        // ── Risk Classifications ───────────────────────────
        $classifications = [
            ['name' => 'Very Low', 'min_score' => 0.00, 'max_score' => 20.00, 'color_code' => '#10B981', 'description' => 'Negligible threat to supply chain operations.'],
            ['name' => 'Low', 'min_score' => 20.01, 'max_score' => 40.00, 'color_code' => '#3B82F6', 'description' => 'Minor disruptions, easily manageable.'],
            ['name' => 'Medium', 'min_score' => 40.01, 'max_score' => 60.00, 'color_code' => '#F59E0B', 'description' => 'Moderate risks requiring close monitoring.'],
            ['name' => 'High', 'min_score' => 60.01, 'max_score' => 80.00, 'color_code' => '#EF4444', 'description' => 'Significant threats likely to delay cargo.'],
            ['name' => 'Critical', 'min_score' => 80.01, 'max_score' => 100.00, 'color_code' => '#7F1D1D', 'description' => 'Extreme disruption risking supply chain halt.'],
        ];

        foreach ($classifications as $c) {
            \App\Models\RiskClassification::updateOrCreate(['name' => $c['name']], $c);
        }

        // ── Seed Sample Countries (if none exist) ───────────
        if (\App\Models\Country::count() === 0) {
            // Seed a few base countries for demo/testing
            $region = \App\Models\Region::create(['name' => 'Global']);
            $currency = \App\Models\Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$']);
            
            $countries = [
                ['name' => 'United States', 'code' => 'US', 'region_id' => $region->id, 'currency_id' => $currency->id],
                ['name' => 'Singapore', 'code' => 'SG', 'region_id' => $region->id, 'currency_id' => $currency->id],
                ['name' => 'Germany', 'code' => 'DE', 'region_id' => $region->id, 'currency_id' => $currency->id],
            ];

            foreach ($countries as $c) {
                $country = \App\Models\Country::create($c);
                
                // Create Mock snapshots and scores
                $snapshot = \App\Models\RiskSnapshot::factory()->create(['country_id' => $country->id]);
                $score = \App\Models\RiskScore::factory()->create([
                    'country_id' => $country->id,
                    'snapshot_id' => $snapshot->id,
                    'overall_score' => 45.50,
                    'risk_level' => 'Medium',
                    'classification_id' => \App\Models\RiskClassification::where('name', 'Medium')->first()->id
                ]);

                \App\Models\RiskHistory::factory()->count(5)->create([
                    'country_id' => $country->id,
                    'risk_score_id' => $score->id
                ]);

                \App\Models\RiskAlert::factory()->count(2)->create([
                    'country_id' => $country->id,
                    'risk_score_id' => $score->id
                ]);

                \App\Models\RiskTrend::factory()->create([
                    'country_id' => $country->id
                ]);
            }
        }
    }
}
