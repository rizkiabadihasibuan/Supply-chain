<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Region;
use App\Models\Currency;
use App\Models\Country;
use App\Models\RiskClassification;
use App\Models\RiskScore;
use App\Models\RiskAlert;
use App\Models\Port;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin Account ──────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@supplychain.com'],
            [
                'name'  => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // ── User Account ───────────────────────────────────
        User::updateOrCreate(
            ['email' => 'user@supplychain.com'],
            [
                'name'  => 'User Operator',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // ── Risk Classifications ───────────────────────────
        $classifications = [
            ['name' => 'Very Low', 'min_score' => 0.00, 'max_score' => 20.00, 'color_code' => '#10B981', 'description' => 'Negligible threat to supply chain operations.'],
            ['name' => 'Low', 'min_score' => 20.01, 'max_score' => 40.00, 'color_code' => '#3B82F6', 'description' => 'Minor disruptions, easily manageable.'],
            ['name' => 'Medium', 'min_score' => 40.01, 'max_score' => 60.00, 'color_code' => '#F59E0B', 'description' => 'Moderate risks requiring close monitoring.'],
            ['name' => 'High', 'min_score' => 60.01, 'max_score' => 80.00, 'color_code' => '#EF4444', 'description' => 'Significant threats likely to delay cargo.'],
            ['name' => 'Critical', 'min_score' => 80.01, 'max_score' => 100.00, 'color_code' => '#7F1D1D', 'description' => 'Extreme disruption risking supply chain halt.'],
        ];

        $classificationModels = [];
        foreach ($classifications as $c) {
            $classificationModels[strtolower($c['name'])] = RiskClassification::updateOrCreate(['name' => $c['name']], $c);
        }

        // ── Seed Regions ──────────────────────────────────
        $regions = [];
        foreach (['Global', 'Asia', 'Europe', 'America', 'Africa'] as $rName) {
            $regions[strtolower($rName)] = Region::firstOrCreate(['name' => $rName]);
        }

        // ── Seed Currencies ───────────────────────────────
        $currencies = [];
        $currencyData = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$'],
            ['code' => 'IDR', 'name' => 'Indonesian Rupiah', 'symbol' => 'Rp'],
            ['code' => 'CNY', 'name' => 'Chinese Yuan', 'symbol' => '¥'],
            ['code' => 'BRL', 'name' => 'Brazilian Real', 'symbol' => 'R$'],
            ['code' => 'ZAR', 'name' => 'South African Rand', 'symbol' => 'R'],
        ];
        foreach ($currencyData as $c) {
            $currencies[$c['code']] = Currency::firstOrCreate(['code' => $c['code']], $c);
        }

        // ── Seed Countries ────────────────────────────────
        $countriesData = [
            ['name' => 'Sudan', 'code' => 'SD', 'region' => 'africa', 'currency' => 'USD', 'score' => 88.00, 'level' => 'Critical', 'lat' => 12.86, 'lng' => 30.22, 'flag' => 'https://flagcdn.com/w320/sd.png', 'langs' => ['Arabic', 'English']],
            ['name' => 'Yemen', 'code' => 'YE', 'region' => 'asia', 'currency' => 'USD', 'score' => 85.00, 'level' => 'Critical', 'lat' => 15.55, 'lng' => 48.51, 'flag' => 'https://flagcdn.com/w320/ye.png', 'langs' => ['Arabic']],
            ['name' => 'Syria', 'code' => 'SY', 'region' => 'asia', 'currency' => 'USD', 'score' => 82.00, 'level' => 'Critical', 'lat' => 34.80, 'lng' => 38.99, 'flag' => 'https://flagcdn.com/w320/sy.png', 'langs' => ['Arabic']],
            ['name' => 'Ukraine', 'code' => 'UA', 'region' => 'europe', 'currency' => 'EUR', 'score' => 79.00, 'level' => 'High', 'lat' => 48.37, 'lng' => 31.16, 'flag' => 'https://flagcdn.com/w320/ua.png', 'langs' => ['Ukrainian']],
            ['name' => 'Somalia', 'code' => 'SO', 'region' => 'africa', 'currency' => 'USD', 'score' => 76.00, 'level' => 'High', 'lat' => 5.15, 'lng' => 46.19, 'flag' => 'https://flagcdn.com/w320/so.png', 'langs' => ['Somali', 'Arabic']],
            
            ['name' => 'Singapore', 'code' => 'SG', 'region' => 'asia', 'currency' => 'SGD', 'score' => 9.50, 'level' => 'Very Low', 'lat' => 1.35, 'lng' => 103.81, 'flag' => 'https://flagcdn.com/w320/sg.png', 'langs' => ['English', 'Malay', 'Tamil', 'Chinese']],
            ['name' => 'Switzerland', 'code' => 'CH', 'region' => 'europe', 'currency' => 'EUR', 'score' => 11.00, 'level' => 'Very Low', 'lat' => 46.81, 'lng' => 8.22, 'flag' => 'https://flagcdn.com/w320/ch.png', 'langs' => ['German', 'French', 'Italian']],
            ['name' => 'Denmark', 'code' => 'DK', 'region' => 'europe', 'currency' => 'EUR', 'score' => 12.00, 'level' => 'Very Low', 'lat' => 56.26, 'lng' => 9.50, 'flag' => 'https://flagcdn.com/w320/dk.png', 'langs' => ['Danish']],
            ['name' => 'Indonesia', 'code' => 'ID', 'region' => 'asia', 'currency' => 'IDR', 'score' => 12.50, 'level' => 'Very Low', 'lat' => -0.78, 'lng' => 113.92, 'flag' => 'https://flagcdn.com/w320/id.png', 'langs' => ['Indonesian']],
            ['name' => 'Japan', 'code' => 'JP', 'region' => 'asia', 'currency' => 'USD', 'score' => 13.00, 'level' => 'Very Low', 'lat' => 36.20, 'lng' => 138.25, 'flag' => 'https://flagcdn.com/w320/jp.png', 'langs' => ['Japanese']],
            
            ['name' => 'China', 'code' => 'CN', 'region' => 'asia', 'currency' => 'CNY', 'score' => 49.20, 'level' => 'Medium', 'lat' => 35.86, 'lng' => 104.19, 'flag' => 'https://flagcdn.com/w320/cn.png', 'langs' => ['Chinese']],
            ['name' => 'Netherlands', 'code' => 'NL', 'region' => 'europe', 'currency' => 'EUR', 'score' => 28.50, 'level' => 'Low', 'lat' => 52.13, 'lng' => 5.29, 'flag' => 'https://flagcdn.com/w320/nl.png', 'langs' => ['Dutch']],
            ['name' => 'United States', 'code' => 'US', 'region' => 'america', 'currency' => 'USD', 'score' => 34.80, 'level' => 'Low', 'lat' => 37.09, 'lng' => -95.71, 'flag' => 'https://flagcdn.com/w320/us.png', 'langs' => ['English']],
            ['name' => 'Brazil', 'code' => 'BR', 'region' => 'america', 'currency' => 'BRL', 'score' => 23.50, 'level' => 'Low', 'lat' => -14.23, 'lng' => -51.92, 'flag' => 'https://flagcdn.com/w320/br.png', 'langs' => ['Portuguese']],
            ['name' => 'South Africa', 'code' => 'ZA', 'region' => 'africa', 'currency' => 'ZAR', 'score' => 31.50, 'level' => 'Low', 'lat' => -30.55, 'lng' => 22.93, 'flag' => 'https://flagcdn.com/w320/za.png', 'langs' => ['Zulu', 'Xhosa', 'Afrikaans', 'English']],
        ];

        foreach ($countriesData as $c) {
            $country = Country::updateOrCreate(
                ['code' => $c['code']],
                [
                    'name' => $c['name'],
                    'region_id' => $regions[$c['region']]->id,
                    'currency_id' => $currencies[$c['currency']]->id,
                    'latitude' => $c['lat'],
                    'longitude' => $c['lng'],
                    'flag_url' => $c['flag'],
                    'languages' => $c['langs'],
                ]
            );

            $classification = RiskClassification::where('name', $c['level'])->first();

            // Seed Risk Scores
            $score = RiskScore::updateOrCreate(
                ['country_id' => $country->id],
                [
                    'classification_id' => $classification->id,
                    'final_risk_score' => $c['score'],
                    'risk_level' => $c['level'],
                    'components' => [
                        'weather' => $c['score'] * 0.9,
                        'inflation' => $c['score'] * 0.8,
                        'currency' => $c['score'] * 0.7,
                        'political' => $c['score'] * 1.0,
                        'economic' => $c['score'] * 0.85,
                        'logistics' => $c['score'] * 0.95,
                    ],
                    'history' => [
                        ['date' => now()->subDays(4)->toDateString(), 'score' => $c['score'] - 1.0],
                        ['date' => now()->subDays(3)->toDateString(), 'score' => $c['score'] - 0.5],
                        ['date' => now()->subDays(2)->toDateString(), 'score' => $c['score'] + 0.5],
                        ['date' => now()->subDays(1)->toDateString(), 'score' => $c['score'] - 0.2],
                        ['date' => now()->toDateString(), 'score' => $c['score']],
                    ]
                ]
            );

            // Seed Risk Alerts
            RiskAlert::create([
                'country_id' => $country->id,
                'title' => 'Geopolitical tension alerts for ' . $country->name,
                'description' => 'Monitoring potential delays and shipping risk anomalies.',
                'severity' => $c['level'] === 'Critical' ? 'high' : 'medium',
                'status' => 'active',
                'detected_at' => now(),
            ]);
        }

        // ── Seed Ports ────────────────────────────────────
        $portsData = [
            ['name' => 'Tanjung Priok, Jakarta', 'code' => 'IDTPP', 'country_code' => 'ID', 'lat' => -6.10, 'lng' => 106.88, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of Singapore', 'code' => 'SGSGP', 'country_code' => 'SG', 'lat' => 1.26, 'lng' => 103.80, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of Shanghai', 'code' => 'CNSHA', 'country_code' => 'CN', 'lat' => 30.62, 'lng' => 122.05, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of Rotterdam', 'code' => 'NLRTM', 'country_code' => 'NL', 'lat' => 51.90, 'lng' => 4.13, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of Los Angeles', 'code' => 'USLAX', 'country_code' => 'US', 'lat' => 33.72, 'lng' => -118.26, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of New York', 'code' => 'USNYC', 'country_code' => 'US', 'lat' => 40.67, 'lng' => -74.05, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of Santos', 'code' => 'BRSSS', 'country_code' => 'BR', 'lat' => -23.95, 'lng' => -46.33, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
            ['name' => 'Port of Durban', 'code' => 'ZADUR', 'country_code' => 'ZA', 'lat' => -29.87, 'lng' => 31.02, 'size' => 'Large', 'type' => 'Seaport', 'harbor' => 'Coastal Natural'],
        ];

        foreach ($portsData as $p) {
            $country = Country::where('code', $p['country_code'])->first();
            if ($country) {
                Port::updateOrCreate(
                    ['code' => $p['code']],
                    [
                        'name' => $p['name'],
                        'country_id' => $country->id,
                        'latitude' => $p['lat'],
                        'longitude' => $p['lng'],
                        'size' => $p['size'],
                        'type' => $p['type'],
                        'harbor_type' => $p['harbor'],
                        'facilities' => ['crane' => true, 'customs' => true, 'storage' => true],
                    ]
                );
            }
        }

        // ── Seed News Articles ─────────────────────────────
        $newsData = [
            [
                'title' => 'Kemacetan Pelabuhan Global Berpotensi Hambat Distribusi Barang Q3',
                'description' => 'Lonjakan volume kontainer dan badai cuaca ekstrem di Asia memicu penumpukan armada kargo.',
                'content' => 'Laporan intelijen maritim menunjukkan kenaikan waktu tunggu kapal hingga 48 jam.',
                'url' => 'https://news.supplychain.com/article-1',
                'author' => 'Global Logistics Review',
                'source' => 'Reuters Trade',
                'sentiment_status' => 'warning',
            ],
            [
                'title' => 'Krisis Terusan Suez: Fluktuasi Tarif Kontainer & Asuransi Perkapalan',
                'description' => 'Ketegangan geopolitik memaksa rute pelayaran dialihkan mengitari Tanjung Harapan.',
                'content' => 'Biaya bahan bakar dan premi asuransi membengkak hingga 15% bagi koridor Asia-Eropa.',
                'url' => 'https://news.supplychain.com/article-2',
                'author' => 'Maritime Intelligence',
                'source' => 'Bloomberg Logistics',
                'sentiment_status' => 'danger',
            ],
            [
                'title' => 'Kestabilan Jalur Logistik Asia Tenggara Dorong Pertumbuhan Impor Q2',
                'description' => 'Efisiensi hub Singapura dan Tanjung Priok mempercepat pergerakan bahan baku manufaktur.',
                'content' => 'Indeks kelancaran arus barang mencatatkan kenaikan positif 4.2%.',
                'url' => 'https://news.supplychain.com/article-3',
                'author' => 'Trade Analytics Group',
                'source' => 'Asia Supply Network',
                'sentiment_status' => 'stable',
            ],
        ];

        foreach ($newsData as $n) {
            \App\Models\NewsArticle::updateOrCreate(
                ['title' => $n['title']],
                $n
            );
        }
    }
}
