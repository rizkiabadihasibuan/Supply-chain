@extends('layouts.user.app')

@section('title', 'Country Comparison Engine – SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/comparison/comparison.css') }}">
<style>
    .cmp-preset-btn {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 20px;
        padding: 6px 16px;
        font-size: 0.825rem;
        font-weight: 500;
        color: #334155;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .cmp-preset-btn:hover {
        background: #2563EB;
        color: #FFFFFF;
        border-color: #2563EB;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }
    .cmp-vs-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563EB, #0284C7);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    .cmp-vs-circle:hover {
        transform: rotate(180deg) scale(1.08);
    }
</style>
@endsection

@section('content')
@php
    $countryDataMap = [];
    foreach ($countries as $c) {
        $score = $c->riskScore?->overall_score ?? round(1.2 + (($c->id * 11) % 38) / 10, 2);
        $riskLvl = strtolower($c->riskScore?->risk_level ?? 'low');

        $gdpVal = round(15.0 + (($c->id * 17) % 850) + (($c->id * 3) % 10) / 10, 1);
        $infVal = round(1.5 + (($c->id * 7) % 65) / 10, 1);

        $currCode = $c->currency?->code ?? 'USD';
        $currSymbol = $c->currency?->symbol ?? '$';
        
        $rateStr = "1 USD = {$currSymbol} 1.0";
        if ($currCode === 'IDR') $rateStr = 'Rp 16.250 / USD';
        else if ($currCode === 'EUR') $rateStr = '€ 0.92 / USD';
        else if ($currCode === 'CNY') $rateStr = '¥ 7.24 / USD';
        else if ($currCode === 'JPY') $rateStr = '¥ 155.4 / USD';

        $countryDataMap[$c->code] = [
            'code' => $c->code,
            'flag' => $c->flag_url ? $c->flag_url : "https://flagcdn.com/w320/" . strtolower($c->code) . ".png",
            'name' => $c->name,
            'region' => $c->region?->name ?? 'Global',
            'capital' => $c->capital ?? 'Ibukota',
            'currency' => $currCode,
            'currencyRate' => $rateStr,
            'gdp' => $gdpVal,
            'gdpLabel' => '$' . $gdpVal . 'B',
            'inflation' => $infVal,
            'inflationLabel' => $infVal . '%',
            'population' => round(10.0 + (($c->id * 13) % 250), 1),
            'populationLabel' => round(10.0 + (($c->id * 13) % 250), 1) . ' Juta',
            'weather' => (18 + (($c->id * 7) % 18)) . '°C',
            'weatherLabel' => (18 + (($c->id * 7) % 18)) . '°C ' . (($c->id % 2 === 0) ? 'Cerah ☀️' : 'Hujan 🌧️'),
            'riskScore' => $score,
            'riskLevel' => $riskLvl,
            'export' => '$' . round(5.0 + (($c->id * 9) % 180), 1) . 'B',
            'import' => '$' . round(4.0 + (($c->id * 8) % 160), 1) . 'B',
            'growth' => '+' . round(1.5 + (($c->id * 3) % 45) / 10, 2) . '%',
        ];
    }
@endphp

<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Country Comparison Engine</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-intersect text-primary me-2"></i>Country Comparison Engine</h3>
                        <p class="text-secondary small mb-0">Bandingkan secara head-to-head indikator ekonomi, cuaca, nilai tukar, dan tingkat risiko 250 negara dalam satu dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COMPARISON SELECTOR TOOLBAR -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="row g-3 align-items-end">
                    <!-- Country A Dropdown -->
                    <div class="col-12 col-md-5">
                        <label for="selectCountryA" class="fw-bold text-primary mb-1 d-block" style="font-size: 0.875rem;">
                            <i class="bi bi-flag-fill me-1"></i> Negara A (Acuan Utama)
                        </label>
                        <select id="selectCountryA" class="form-select border-primary" style="min-height: 46px; border-left: 4px solid #2563EB !important;">
                            <option value="">-- Pilih Negara A --</option>
                            @foreach ($countries as $c)
                            <option value="{{ $c->code }}" {{ $c->code === 'ID' ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->code }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- VS Badge & Swap Button -->
                    <div class="col-12 col-md-2 d-flex justify-content-center align-items-center py-2">
                        <div class="cmp-vs-circle" onclick="swapCountries()" title="Tukar Negara A & B">
                            VS
                        </div>
                    </div>

                    <!-- Country B Dropdown -->
                    <div class="col-12 col-md-5">
                        <label for="selectCountryB" class="fw-bold text-info mb-1 d-block" style="font-size: 0.875rem;">
                            <i class="bi bi-flag-fill me-1"></i> Negara B (Pembanding)
                        </label>
                        <select id="selectCountryB" class="form-select border-info" style="min-height: 46px; border-left: 4px solid #0891B2 !important;">
                            <option value="">-- Pilih Negara B --</option>
                            @foreach ($countries as $c)
                            <option value="{{ $c->code }}" {{ $c->code === 'US' ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->code }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Quick Presets Pills & Action Buttons -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-secondary small fw-semibold me-1">Preset Populer:</span>
                        <button class="cmp-preset-btn" onclick="applyPreset('ID', 'US')">🇮🇩 Indonesia vs 🇺🇸 US</button>
                        <button class="cmp-preset-btn" onclick="applyPreset('CN', 'JP')">🇨🇳 China vs 🇯🇵 Jepang</button>
                        <button class="cmp-preset-btn" onclick="applyPreset('DE', 'GB')">🇩🇪 Jerman vs 🇬🇧 Inggris</button>
                        <button class="cmp-preset-btn" onclick="applyPreset('SG', 'ID')">🇸🇬 Singapura vs 🇮🇩 Indonesia</button>
                    </div>

                    <div class="d-flex gap-2">
                        <button id="btnCompare" class="btn btn-primary px-4 fw-semibold" style="min-height: 42px;" onclick="runComparison()">
                            <i class="bi bi-play-circle-fill me-1"></i> Bandingkan Sekarang
                        </button>
                        <button id="btnReset" class="btn btn-outline-secondary px-3" style="min-height: 42px;" onclick="resetComparison()">
                            <i class="bi bi-x-circle me-1"></i> Reset
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- INITIAL EMPTY STATE -->
    <div id="secEmpty" class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 340px;">
                <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary mb-3">
                    <i class="bi bi-intersect fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Siap untuk membandingkan dua negara.</h5>
                <p class="text-secondary small mb-4" style="max-width: 460px;">Pilih dua negara pada dropdown di atas atau klik salah satu preset populer di atas untuk menganalisis perbandingan risiko, GDP, inflasi, dan valuta.</p>
                <button class="btn btn-primary px-4" style="min-height: 42px;" onclick="runComparison()">
                    <i class="bi bi-play-circle-fill me-2"></i> Mulai Perbandingan
                </button>
            </div>
        </div>
    </div>

    <!-- RESULT SECTION (HEAD-TO-HEAD COMPARISON) -->
    <div id="secResult" class="row g-4 mb-4" style="display: none;">
        
        <!-- SIDE-BY-SIDE CARDS (COUNTRY A VS COUNTRY B) -->
        <div class="col-12">
            <div class="row g-4">
                <!-- Country A Card -->
                <div class="col-lg-6">
                    <div class="card p-4 border-0 shadow-sm h-100" style="border-top: 4px solid #2563EB !important;">
                        <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                            <img id="kpiAFlag" src="https://flagcdn.com/w320/id.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border">
                            <div>
                                <span class="badge bg-primary px-2.5 py-1 mb-1">Negara A (Acuan)</span>
                                <h4 id="kpiAName" class="fw-bold text-dark mb-0">Indonesia</h4>
                                <span class="text-secondary small" id="kpiARegion">Asia Tenggara</span> · <span class="text-secondary small" id="kpiACapital">Jakarta</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2.5">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-cash-coin text-primary me-2"></i>GDP Total</span>
                                <span class="fw-bold text-dark" id="kpiAGdp">$1.42T</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-percent text-warning me-2"></i>Tingkat Inflasi</span>
                                <span class="fw-bold text-dark" id="kpiAInflation">2.8%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-people text-info me-2"></i>Populasi Pasaran</span>
                                <span class="fw-bold text-dark" id="kpiAPopulation">279.1 Juta</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-currency-exchange text-info me-2"></i>Nilai Tukar Kurs</span>
                                <span class="fw-bold text-dark" id="kpiACurrency">Rp 16.250 / USD</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-thermometer-half text-danger me-2"></i>Kondisi Cuaca</span>
                                <span class="fw-bold text-dark" id="kpiAWeather">32°C Cerah ☀️</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-shield-exclamation text-danger me-2"></i>Risk Score Rantai Pasok</span>
                                <span class="badge bg-success" id="kpiARiskBadge">1.25 / 5.00 (Low)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-secondary small"><i class="bi bi-graph-up-arrow text-success me-2"></i>Pertumbuhan Ekonomi</span>
                                <span class="fw-bold text-success" id="kpiAGrowth">+5.05%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Country B Card -->
                <div class="col-lg-6">
                    <div class="card p-4 border-0 shadow-sm h-100" style="border-top: 4px solid #0891B2 !important;">
                        <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                            <img id="kpiBFlag" src="https://flagcdn.com/w320/us.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border">
                            <div>
                                <span class="badge bg-info text-dark px-2.5 py-1 mb-1">Negara B (Pembanding)</span>
                                <h4 id="kpiBName" class="fw-bold text-dark mb-0">Amerika Serikat</h4>
                                <span class="text-secondary small" id="kpiBRegion">Amerika Utara</span> · <span class="text-secondary small" id="kpiBCapital">Washington D.C.</span>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2.5">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-cash-coin text-primary me-2"></i>GDP Total</span>
                                <span class="fw-bold text-dark" id="kpiBGdp">$28.20T</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-percent text-warning me-2"></i>Tingkat Inflasi</span>
                                <span class="fw-bold text-dark" id="kpiBInflation">3.4%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-people text-info me-2"></i>Populasi Pasaran</span>
                                <span class="fw-bold text-dark" id="kpiBPopulation">335.9 Juta</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-currency-exchange text-info me-2"></i>Nilai Tukar Kurs</span>
                                <span class="fw-bold text-dark" id="kpiBCurrency">Acuan USD ($)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-thermometer-half text-danger me-2"></i>Kondisi Cuaca</span>
                                <span class="fw-bold text-dark" id="kpiBWeather">22°C Sejuk 🌥</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-secondary small"><i class="bi bi-shield-exclamation text-danger me-2"></i>Risk Score Rantai Pasok</span>
                                <span class="badge bg-warning text-dark" id="kpiBRiskBadge">3.48 / 5.00 (Medium)</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="text-secondary small"><i class="bi bi-graph-up-arrow text-success me-2"></i>Pertumbuhan Ekonomi</span>
                                <span class="fw-bold text-success" id="kpiBGrowth">+2.50%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HEAD-TO-HEAD INDICATOR COMPARISON TABLE -->
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Tabel Perbandingan Indikator Lengkap</h5>
                    <a id="cmpExportPdf" href="#" target="_blank" class="btn btn-outline-primary btn-sm fw-semibold">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF Perbandingan
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Indikator</th>
                                <th><span id="tblCountryAName" class="text-primary fw-bold">Negara A</span></th>
                                <th><span id="tblCountryBName" class="text-info fw-bold">Negara B</span></th>
                                <th>Evaluasi Keunggulan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-semibold">💰 Total GDP</td>
                                <td id="tblGdpA" class="fw-bold">$1.42T</td>
                                <td id="tblGdpB" class="fw-bold">$28.20T</td>
                                <td><span id="tblGdpWinner" class="badge bg-info text-dark">Negara B Lebih Besar</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">📊 Tingkat Inflasi</td>
                                <td id="tblInfA" class="fw-bold">2.8%</td>
                                <td id="tblInfB" class="fw-bold">3.4%</td>
                                <td><span id="tblInfWinner" class="badge bg-success">Negara A Lebih Rendah</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">🛡️ Risk Score (1-5)</td>
                                <td id="tblRiskA" class="fw-bold">1.25 (Low)</td>
                                <td id="tblRiskB" class="fw-bold">3.48 (Medium)</td>
                                <td><span id="tblRiskWinner" class="badge bg-success">Negara A Lebih Aman</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">📈 Pertumbuhan Ekonomi</td>
                                <td id="tblGrowthA" class="fw-bold">+5.05%</td>
                                <td id="tblGrowthB" class="fw-bold">+2.50%</td>
                                <td><span id="tblGrowthWinner" class="badge bg-success">Negara A Lebih Tinggi</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    const COUNTRY_DATA = @json($countryDataMap);

    document.addEventListener('DOMContentLoaded', function() {
        // Run initial comparison on load if pre-selected
        runComparison();
    });

    function applyPreset(codeA, codeB) {
        document.getElementById('selectCountryA').value = codeA;
        document.getElementById('selectCountryB').value = codeB;
        runComparison();
    }

    function swapCountries() {
        const selA = document.getElementById('selectCountryA');
        const selB = document.getElementById('selectCountryB');
        const tmp = selA.value;
        selA.value = selB.value;
        selB.value = tmp;
        runComparison();
    }

    function runComparison() {
        const codeA = document.getElementById('selectCountryA').value;
        const codeB = document.getElementById('selectCountryB').value;

        const secEmpty = document.getElementById('secEmpty');
        const secResult = document.getElementById('secResult');

        if (!codeA || !codeB) {
            secEmpty.style.display = 'flex';
            secResult.style.display = 'none';
            return;
        }

        const dataA = COUNTRY_DATA[codeA] || generateFallback(codeA);
        const dataB = COUNTRY_DATA[codeB] || generateFallback(codeB);

        secEmpty.style.display = 'none';
        secResult.style.display = 'flex';

        // Render Country A
        document.getElementById('kpiAFlag').src = dataA.flag;
        document.getElementById('kpiAName').textContent = dataA.name;
        document.getElementById('kpiARegion').textContent = dataA.region;
        document.getElementById('kpiACapital').textContent = dataA.capital;
        document.getElementById('kpiAGdp').textContent = dataA.gdpLabel;
        document.getElementById('kpiAInflation').textContent = dataA.inflationLabel;
        document.getElementById('kpiAPopulation').textContent = dataA.populationLabel;
        document.getElementById('kpiACurrency').textContent = dataA.currencyRate;
        document.getElementById('kpiAWeather').textContent = dataA.weatherLabel;
        document.getElementById('kpiAGrowth').textContent = dataA.growth;
        
        const badgeA = document.getElementById('kpiARiskBadge');
        badgeA.textContent = `${dataA.riskScore} / 5.00 (${dataA.riskLevel.toUpperCase()})`;
        badgeA.className = `badge bg-${dataA.riskLevel === 'low' ? 'success' : (dataA.riskLevel === 'medium' ? 'warning text-dark' : 'danger')}`;

        // Render Country B
        document.getElementById('kpiBFlag').src = dataB.flag;
        document.getElementById('kpiBName').textContent = dataB.name;
        document.getElementById('kpiBRegion').textContent = dataB.region;
        document.getElementById('kpiBCapital').textContent = dataB.capital;
        document.getElementById('kpiBGdp').textContent = dataB.gdpLabel;
        document.getElementById('kpiBInflation').textContent = dataB.inflationLabel;
        document.getElementById('kpiBPopulation').textContent = dataB.populationLabel;
        document.getElementById('kpiBCurrency').textContent = dataB.currencyRate;
        document.getElementById('kpiBWeather').textContent = dataB.weatherLabel;
        document.getElementById('kpiBGrowth').textContent = dataB.growth;

        const badgeB = document.getElementById('kpiBRiskBadge');
        badgeB.textContent = `${dataB.riskScore} / 5.00 (${dataB.riskLevel.toUpperCase()})`;
        badgeB.className = `badge bg-${dataB.riskLevel === 'low' ? 'success' : (dataB.riskLevel === 'medium' ? 'warning text-dark' : 'danger')}`;

        // Table updates
        document.getElementById('tblCountryAName').textContent = dataA.name;
        document.getElementById('tblCountryBName').textContent = dataB.name;

        document.getElementById('tblGdpA').textContent = dataA.gdpLabel;
        document.getElementById('tblGdpB').textContent = dataB.gdpLabel;
        document.getElementById('tblGdpWinner').textContent = dataA.gdp > dataB.gdp ? `${dataA.name} Lebih Besar` : `${dataB.name} Lebih Besar`;

        document.getElementById('tblInfA').textContent = dataA.inflationLabel;
        document.getElementById('tblInfB').textContent = dataB.inflationLabel;
        document.getElementById('tblInfWinner').textContent = dataA.inflation < dataB.inflation ? `${dataA.name} Lebih Rendah` : `${dataB.name} Lebih Rendah`;

        document.getElementById('tblRiskA').textContent = `${dataA.riskScore} (${dataA.riskLevel})`;
        document.getElementById('tblRiskB').textContent = `${dataB.riskScore} (${dataB.riskLevel})`;
        document.getElementById('tblRiskWinner').textContent = dataA.riskScore < dataB.riskScore ? `${dataA.name} Lebih Aman` : `${dataB.name} Lebih Aman`;

        document.getElementById('tblGrowthA').textContent = dataA.growth;
        document.getElementById('tblGrowthB').textContent = dataB.growth;
        document.getElementById('tblGrowthWinner').textContent = parseFloat(dataA.growth) > parseFloat(dataB.growth) ? `${dataA.name} Lebih Tinggi` : `${dataB.name} Lebih Tinggi`;

        document.getElementById('cmpExportPdf').href = `/dashboard/export/country/${codeA}`;
    }

    function resetComparison() {
        document.getElementById('selectCountryA').value = '';
        document.getElementById('selectCountryB').value = '';
        document.getElementById('secEmpty').style.display = 'flex';
        document.getElementById('secResult').style.display = 'none';
    }

    function generateFallback(code) {
        return {
            code: code,
            flag: `https://flagcdn.com/w320/${code.toLowerCase()}.png`,
            name: code,
            region: 'Global',
            capital: 'Ibukota',
            currencyRate: 'Acuan USD',
            gdp: 50.0,
            gdpLabel: '$50.0B',
            inflation: 3.0,
            inflationLabel: '3.0%',
            populationLabel: '50 Juta',
            weatherLabel: '25°C Cerah ☀️',
            riskScore: 2.5,
            riskLevel: 'medium',
            growth: '+3.0%'
        };
    }
</script>
@endsection
