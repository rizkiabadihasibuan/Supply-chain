<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Region;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        return [
            'region_id' => Region::factory(),
            'currency_id' => Currency::factory(),
            'code' => fake()->unique()->lexify('??'),
            'name' => fake()->unique()->country(),
            'subregion' => fake()->word(),
            'population' => fake()->numberBetween(100000, 100000000),
            'area' => fake()->randomFloat(2, 100, 9000000),
            'timezone' => fake()->timezone(),
        ];
    }
}
