<?php

namespace Database\Seeders;

use App\Models\PositiveWord;
use App\Models\NegativeWord;
use Illuminate\Database\Seeder;

class LexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positives = [
            'growth', 'increase', 'profit', 'stable', 'improve', 
            'recovery', 'surplus', 'boom', 'success', 'expansion', 
            'strengthen', 'gain', 'positive', 'safe', 'secure'
        ];

        $negatives = [
            'war', 'crisis', 'inflation', 'delay', 'disaster', 
            'conflict', 'shortage', 'strike', 'congestion', 'decrease', 
            'drop', 'loss', 'collapse', 'risk', 'disruption', 
            'tariff', 'embargo', 'sanction', 'blockade', 'shutdown'
        ];

        foreach ($positives as $word) {
            PositiveWord::create(['word' => $word]);
        }

        foreach ($negatives as $word) {
            NegativeWord::create(['word' => $word]);
        }
    }
}
