@extends('layouts.app')

@section('title', 'Country Comparison Engine')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary border-opacity-25">
    <h1 class="h2 text-white"><i class="bi bi-arrow-left-right me-2 text-glow-cyan"></i> Country Comparison Engine</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary text-white glass-card">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
    </a>
</div>

<!-- Comparison Selector Form -->
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-4">
            <form action="{{ route('compare') }}" method="GET">
                <div class="row g-3 align-items-end justify-content-center">
                    <div class="col-md-4">
                        <label for="country1" class="form-label">Negara Pertama</label>
                        <select name="country1" id="country1" class="form-select">
                            <option value="">-- Pilih Negara --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->code }}" {{ $country1Code === $c->code ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 text-center py-2">
                        <span class="fs-3 fw-bold text-glow-purple d-none d-md-inline">VS</span>
                        <span class="fs-5 text-secondary d-md-none">dibandingkan dengan</span>
                    </div>

                    <div class="col-md-4">
                        <label for="country2" class="form-label">Negara Kedua</label>
                        <select name="country2" id="country2" class="form-select">
                            <option value="">-- Pilih Negara --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->code }}" {{ $country2Code === $c->code ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-arrow-left-right me-1"></i> Bandingkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Side-by-Side Comparison Details -->
@if($country1 && $country2)
<div class="row g-4">
    <!-- Country 1 Card -->
    <div class="col-md-6">
        <div class="glass-card p-4 h-100 border-glow-cyan">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3 fs-1">🇩🇪</div> <!-- Fallback or flag icon -->
                    <div>
                        <h2 class="text-white mb-0">{{ $country1->name }}</h2>
                        <span class="badge bg-secondary text-white py-1 px-3 mt-1">{{ $country1->region }}</span>
                    </div>
                </div>
                <div>
                    @if($risk1)
                        <span class="badge {{ $risk1->risk_level === 'Low' ? 'badge-low' : ($risk1->risk_level === 'Medium' ? 'badge-medium' : 'badge-high') }} py-2 px-4" style="font-size: 1rem;">
                            {{ $risk1->risk_level }} Risk ({{ $risk1->total_risk_score }}%)
                        </span>
                    @else
                        <span class="badge bg-secondary text-white py-2 px-3">Belum Sinkron</span>
                    @endif
                </div>
            </div>

            <!-- Country Details Table -->
            <div class="table-responsive">
                <table class="table table-dark table-borderless bg-transparent align-middle">
                    <tbody>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3" style="width: 40%;">GDP (PDB)</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->gdp ? '$' . number_format($country1->gdp / 1e12, 2) . ' Triliun' : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Tingkat Inflasi</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->inflation ? $country1->inflation . '%' : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Jumlah Populasi</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->population ? number_format($country1->population) : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Nilai Ekspor / Impor</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->export_value ? '$' . number_format($country1->export_value / 1e9, 2) . 'M' : 'N/A' }} /
                                {{ $country1->import_value ? '$' . number_format($country1->import_value / 1e9, 2) . 'M' : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Mata Uang & Kurs (ke USD)</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->currency_code }} - {{ $country1->currency_name }}
                                <br>
                                <span class="small text-glow-cyan">
                                    @php
                                        $rate1 = Cache::get("currency_rate_USD_{$country1->currency_code}")['rate'] ?? null;
                                    @endphp
                                    1 USD = {{ $rate1 ? number_format($rate1, 4) : 'N/A' }} {{ $country1->currency_code }}
                                </span>
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Cuaca Saat Ini</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->current_weather_temp ? $country1->current_weather_temp . '°C' : 'N/A' }}
                                <br>
                                <span class="small text-info">{{ $country1->current_weather_condition ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Kecepatan Angin & Hujan</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country1->current_weather_wind_speed ? $country1->current_weather_wind_speed . ' km/h' : 'N/A' }}
                                <br>
                                <span class="small text-muted">{{ $country1->current_weather_precipitation ? $country1->current_weather_precipitation . ' mm' : 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Risiko Badai</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                <span class="text-glow-purple">{{ $country1->current_weather_storm_risk ? $country1->current_weather_storm_risk . '%' : 'N/A' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Risk Score Breakdown Cards -->
            @if($risk1)
            <div class="mt-4">
                <h5 class="text-white mb-3"><i class="bi bi-shield-slash me-2 text-glow-purple"></i> Breakdown Risiko</h5>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Cuaca (30%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk1->weather_risk_score }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Inflasi (20%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk1->inflation_risk_score }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Nilai Tukar (10%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk1->currency_risk_score }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Geopolitik/Berita (40%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk1->political_risk_score }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Country 2 Card -->
    <div class="col-md-6">
        <div class="glass-card p-4 h-100 border-glow-purple">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3 fs-1">🇦🇺</div> <!-- Fallback or flag icon -->
                    <div>
                        <h2 class="text-white mb-0">{{ $country2->name }}</h2>
                        <span class="badge bg-secondary text-white py-1 px-3 mt-1">{{ $country2->region }}</span>
                    </div>
                </div>
                <div>
                    @if($risk2)
                        <span class="badge {{ $risk2->risk_level === 'Low' ? 'badge-low' : ($risk2->risk_level === 'Medium' ? 'badge-medium' : 'badge-high') }} py-2 px-4" style="font-size: 1rem;">
                            {{ $risk2->risk_level }} Risk ({{ $risk2->total_risk_score }}%)
                        </span>
                    @else
                        <span class="badge bg-secondary text-white py-2 px-3">Belum Sinkron</span>
                    @endif
                </div>
            </div>

            <!-- Country Details Table -->
            <div class="table-responsive">
                <table class="table table-dark table-borderless bg-transparent align-middle">
                    <tbody>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3" style="width: 40%;">GDP (PDB)</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->gdp ? '$' . number_format($country2->gdp / 1e12, 2) . ' Triliun' : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Tingkat Inflasi</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->inflation ? $country2->inflation . '%' : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Jumlah Populasi</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->population ? number_format($country2->population) : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Nilai Ekspor / Impor</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->export_value ? '$' . number_format($country2->export_value / 1e9, 2) . 'M' : 'N/A' }} /
                                {{ $country2->import_value ? '$' . number_format($country2->import_value / 1e9, 2) . 'M' : 'N/A' }}
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Mata Uang & Kurs (ke USD)</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->currency_code }} - {{ $country2->currency_name }}
                                <br>
                                <span class="small text-glow-cyan">
                                    @php
                                        $rate2 = Cache::get("currency_rate_USD_{$country2->currency_code}")['rate'] ?? null;
                                    @endphp
                                    1 USD = {{ $rate2 ? number_format($rate2, 4) : 'N/A' }} {{ $country2->currency_code }}
                                </span>
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Cuaca Saat Ini</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->current_weather_temp ? $country2->current_weather_temp . '°C' : 'N/A' }}
                                <br>
                                <span class="small text-info">{{ $country2->current_weather_condition ?? 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Kecepatan Angin & Hujan</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                {{ $country2->current_weather_wind_speed ? $country2->current_weather_wind_speed . ' km/h' : 'N/A' }}
                                <br>
                                <span class="small text-muted">{{ $country2->current_weather_precipitation ? $country2->current_weather_precipitation . ' mm' : 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-secondary py-3">Risiko Badai</td>
                            <td class="text-white fw-semibold py-3 text-end">
                                <span class="text-glow-purple">{{ $country2->current_weather_storm_risk ? $country2->current_weather_storm_risk . '%' : 'N/A' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Risk Score Breakdown Cards -->
            @if($risk2)
            <div class="mt-4">
                <h5 class="text-white mb-3"><i class="bi bi-shield-slash me-2 text-glow-purple"></i> Breakdown Risiko</h5>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Cuaca (30%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk2->weather_risk_score }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Inflasi (20%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk2->inflation_risk_score }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Nilai Tukar (10%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk2->currency_risk_score }}%</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="glass-card p-3 text-center">
                            <small class="text-secondary d-block">Geopolitik/Berita (40%)</small>
                            <span class="fs-5 fw-bold text-white">{{ $risk2->political_risk_score }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-12 text-center py-5">
        <div class="glass-card p-5 d-inline-block" style="max-width: 600px;">
            <i class="bi bi-arrow-left-right text-glow-cyan display-3 mb-4 d-block"></i>
            <h4 class="text-white">Siap untuk Membandingkan?</h4>
            <p class="text-secondary">Pilih dua negara yang ingin Anda bandingkan indikator risiko dan ekonominya menggunakan formulir di atas, lalu klik tombol Bandingkan.</p>
        </div>
    </div>
</div>
@endif
@endsection
