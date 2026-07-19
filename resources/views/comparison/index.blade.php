{{--
    Country Comparison Engine – Milestone 3.11
    resources/views/comparison/index.blade.php
--}}
@extends('layouts.user.app')

@section('title', 'Country Comparison – SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/comparison/comparison.css') }}">
@endsection

@section('content')
<div class="cmp-page-wrapper">

{{-- ======================================================
     PAGE HEADER
     ====================================================== --}}
<div class="row align-items-center mb-4 g-3">
    <div class="col-12 col-md-7">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-3" style="background:rgba(37,99,235,0.08);">
                <i class="bi bi-columns-gap text-primary fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">Country Comparison Engine</h4>
                <p class="text-secondary small mb-0 mt-1">Bandingkan kondisi ekonomi, cuaca, mata uang, dan tingkat risiko dua negara dalam satu dashboard.</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-5 d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
        <span class="badge bg-white text-secondary border shadow-sm px-3 py-2 rounded-pill d-flex align-items-center gap-1" style="font-size:.78rem;">
            <i class="bi bi-calendar3 text-primary"></i>
            {{ now()->translatedFormat('d F Y') }}
        </span>
        <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" style="min-height:38px;" onclick="alert('Simulasi ekspor laporan perbandingan...')">
            <i class="bi bi-download"></i>
            <span class="d-none d-sm-inline">Ekspor Laporan</span>
        </button>
    </div>
</div>

{{-- ======================================================
     COMPARISON TOOLBAR
     ====================================================== --}}
<div class="cmp-toolbar mb-4 cmp-fade">
    <div class="row g-3 align-items-end">
        {{-- Country A --}}
        <div class="col-12 col-md-4">
            <label for="selectCountryA" class="cmp-label-a mb-1 d-block">
                <i class="bi bi-flag-fill me-1"></i> Negara A
            </label>
            <select id="selectCountryA" class="form-select" style="border-left:3px solid #2563EB !important;">
                <option value="">-- Pilih Negara A --</option>
                <option value="ID">🇮🇩 Indonesia</option>
                <option value="CN">🇨🇳 China</option>
                <option value="US">🇺🇸 Amerika Serikat</option>
                <option value="NL">🇳🇱 Belanda</option>
                <option value="SD">🇸🇩 Sudan</option>
                <option value="SG">🇸🇬 Singapura</option>
                <option value="JP">🇯🇵 Jepang</option>
                <option value="DE">🇩🇪 Jerman</option>
            </select>
        </div>

        {{-- VS Badge --}}
        <div class="col-12 col-md-auto d-flex justify-content-center align-items-center pb-1">
            <div class="cmp-vs-badge">VS</div>
        </div>

        {{-- Country B --}}
        <div class="col-12 col-md-4">
            <label for="selectCountryB" class="cmp-label-b mb-1 d-block">
                <i class="bi bi-flag-fill me-1"></i> Negara B
            </label>
            <select id="selectCountryB" class="form-select" style="border-left:3px solid #0891B2 !important;">
                <option value="">-- Pilih Negara B --</option>
                <option value="ID">🇮🇩 Indonesia</option>
                <option value="CN">🇨🇳 China</option>
                <option value="US" selected>🇺🇸 Amerika Serikat</option>
                <option value="NL">🇳🇱 Belanda</option>
                <option value="SD">🇸🇩 Sudan</option>
                <option value="SG">🇸🇬 Singapura</option>
                <option value="JP">🇯🇵 Jepang</option>
                <option value="DE">🇩🇪 Jerman</option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="col-12 col-md-auto d-flex gap-2 ms-md-auto">
            <button id="btnCompare"
                    class="btn btn-primary d-flex align-items-center gap-2 flex-grow-1 justify-content-center"
                    style="min-height:42px; min-width:130px; border-radius:10px;">
                <i class="bi bi-play-circle-fill"></i> Bandingkan
            </button>
            <button id="btnReset"
                    class="btn btn-outline-secondary d-flex align-items-center gap-2"
                    style="min-height:42px; border-radius:10px;"
                    data-bs-toggle="tooltip" title="Reset perbandingan">
                <i class="bi bi-x-circle"></i>
                <span class="d-none d-sm-inline">Reset</span>
            </button>
        </div>
    </div>
</div>

{{-- ======================================================
     EMPTY STATE
     ====================================================== --}}
<div id="secEmpty" class="py-5 text-center cmp-fade" style="display:none;">
    <div class="d-flex justify-content-center mb-4">
        <svg width="200" height="150" viewBox="0 0 200 150" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="100" width="80" height="10" rx="5" fill="#BFDBFE"/>
            <rect x="25" y="60" width="20" height="40" rx="4" fill="#93C5FD"/>
            <rect x="52" y="45" width="20" height="55" rx="4" fill="#2563EB" opacity=".6"/>
            <rect x="110" y="100" width="80" height="10" rx="5" fill="#BAE6FD"/>
            <rect x="125" y="65" width="20" height="35" rx="4" fill="#67E8F9"/>
            <rect x="152" y="50" width="20" height="50" rx="4" fill="#0891B2" opacity=".6"/>
            <line x1="100" y1="20" x2="100" y2="120" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="5,4"/>
            <circle cx="100" cy="15" r="12" fill="#F1F5F9" stroke="#E2E8F0" stroke-width="1.5"/>
            <text x="100" y="19" text-anchor="middle" font-size="12" fill="#94A3B8">⟺</text>
        </svg>
    </div>
    <h5 class="fw-bold text-dark mb-2">Pilih dua negara untuk mulai melakukan perbandingan.</h5>
    <p class="text-secondary mb-4" style="max-width:380px; margin:0 auto;">Gunakan dropdown di atas untuk memilih <span class="fw-semibold text-primary">Negara A</span> dan <span class="fw-semibold" style="color:#0891B2;">Negara B</span>, lalu klik <strong>Bandingkan</strong>.</p>
    <button class="btn btn-primary px-4" style="border-radius:10px; min-height:40px;" onclick="document.getElementById('btnCompare').click()">
        <i class="bi bi-play-circle-fill me-2"></i>Mulai Perbandingan
    </button>
</div>

{{-- ======================================================
     SKELETON LOADING
     ====================================================== --}}
<div id="secSkeleton" style="display:none;">
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex gap-3 mb-3 align-items-center">
                    <span class="placeholder" style="width:52px;height:52px;border-radius:50%;background:#E2E8F0;"></span>
                    <div class="flex-grow-1">
                        <span class="placeholder col-7 d-block mb-2" style="height:14px;border-radius:6px;"></span>
                        <span class="placeholder col-4 d-block" style="height:10px;border-radius:6px;"></span>
                    </div>
                </div>
                @for($i=0;$i<6;$i++)
                <div class="d-flex justify-content-between mb-2">
                    <span class="placeholder col-4" style="height:12px;border-radius:5px;"></span>
                    <span class="placeholder col-3" style="height:12px;border-radius:5px;"></span>
                </div>
                @endfor
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex gap-3 mb-3 align-items-center">
                    <span class="placeholder" style="width:52px;height:52px;border-radius:50%;background:#BAE6FD;"></span>
                    <div class="flex-grow-1">
                        <span class="placeholder col-7 d-block mb-2" style="height:14px;border-radius:6px;"></span>
                        <span class="placeholder col-4 d-block" style="height:10px;border-radius:6px;"></span>
                    </div>
                </div>
                @for($i=0;$i<6;$i++)
                <div class="d-flex justify-content-between mb-2">
                    <span class="placeholder col-4" style="height:12px;border-radius:5px;"></span>
                    <span class="placeholder col-3" style="height:12px;border-radius:5px;"></span>
                </div>
                @endfor
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm p-4 rounded-4">
        <span class="placeholder col-4 d-block mb-3" style="height:16px;border-radius:6px;"></span>
        <div class="placeholder-glow" style="height:240px;border-radius:12px;background:#F1F5F9;overflow:hidden;">
            <span class="placeholder w-100 h-100"></span>
        </div>
    </div>
</div>

{{-- ======================================================
     RESULT SECTION (hidden until JS renders data)
     ====================================================== --}}
<div id="secResult" style="display:none;">

    {{-- ------------------------------------------------
         KPI COMPARISON CARDS
         ------------------------------------------------ --}}
    <div class="row g-4 mb-4">

        {{-- Country A Card --}}
        <div class="col-12 col-md-6">
            <div class="card viz-card cmp-strip-a p-4 cmp-fade cmp-fade-1">
                {{-- Header --}}
                <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                    <span id="kpiAFlag" class="d-flex align-items-center justify-content-center rounded-circle border shadow-sm" style="width:52px;height:52px;font-size:1.6rem;background:#F8FAFC;flex-shrink:0;">🏳</span>
                    <div>
                        <p class="cmp-label-a mb-0">Negara A</p>
                        <h5 id="kpiAName" class="fw-bold text-dark mb-0">–</h5>
                        <small id="kpiARegion" class="text-secondary"></small>
                        <small class="text-muted"> · Ibu Kota: </small>
                        <small id="kpiACapital" class="text-secondary"></small>
                    </div>
                </div>

                {{-- KPI rows --}}
                @php
                $kpiRowsA = [
                    ['id'=>'kpiAGdp',       'label'=>'GDP',          'icon'=>'bi-cash-coin',          'color'=>'primary'],
                    ['id'=>'kpiAInflation',  'label'=>'Inflasi',      'icon'=>'bi-percent',            'color'=>'warning'],
                    ['id'=>'kpiAPopulation', 'label'=>'Populasi',     'icon'=>'bi-people',             'color'=>'primary'],
                    ['id'=>'kpiACurrency',   'label'=>'Kurs',         'icon'=>'bi-currency-exchange',  'color'=>'info'],
                    ['id'=>'kpiAWeather',    'label'=>'Cuaca',        'icon'=>'bi-thermometer-half',   'color'=>'danger'],
                    ['id'=>'kpiARisk',       'label'=>'Risk Score',   'icon'=>'bi-shield-exclamation', 'color'=>'danger'],
                    ['id'=>'kpiAExport',     'label'=>'Ekspor',       'icon'=>'bi-arrow-up-right-circle','color'=>'success'],
                    ['id'=>'kpiAImport',     'label'=>'Impor',        'icon'=>'bi-arrow-down-left-circle','color'=>'secondary'],
                    ['id'=>'kpiAGrowth',     'label'=>'Pertumbuhan',  'icon'=>'bi-graph-up-arrow',     'color'=>'success'],
                ];
                @endphp

                <div class="d-flex flex-column gap-2">
                    @foreach($kpiRowsA as $row)
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-secondary small d-flex align-items-center gap-2">
                            <i class="bi {{ $row['icon'] }} text-{{ $row['color'] }}"></i>
                            {{ $row['label'] }}
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <span id="{{ $row['id'] }}" class="fw-semibold text-dark">–</span>
                            @if($row['label'] === 'Risk Score')
                                <span id="kpiARiskBadge" class="badge rounded-pill" style="font-size:.7rem;"></span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Country B Card --}}
        <div class="col-12 col-md-6">
            <div class="card viz-card cmp-strip-b p-4 cmp-fade cmp-fade-2">
                {{-- Header --}}
                <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                    <span id="kpiBFlag" class="d-flex align-items-center justify-content-center rounded-circle border shadow-sm" style="width:52px;height:52px;font-size:1.6rem;background:#F0FDFF;flex-shrink:0;">🏳</span>
                    <div>
                        <p class="cmp-label-b mb-0">Negara B</p>
                        <h5 id="kpiBName" class="fw-bold text-dark mb-0">–</h5>
                        <small id="kpiBRegion" class="text-secondary"></small>
                        <small class="text-muted"> · Ibu Kota: </small>
                        <small id="kpiBCapital" class="text-secondary"></small>
                    </div>
                </div>

                {{-- KPI rows --}}
                @php
                $kpiRowsB = [
                    ['id'=>'kpiBGdp',       'label'=>'GDP',          'icon'=>'bi-cash-coin',          'color'=>'primary'],
                    ['id'=>'kpiBInflation',  'label'=>'Inflasi',      'icon'=>'bi-percent',            'color'=>'warning'],
                    ['id'=>'kpiBPopulation', 'label'=>'Populasi',     'icon'=>'bi-people',             'color'=>'primary'],
                    ['id'=>'kpiBCurrency',   'label'=>'Kurs',         'icon'=>'bi-currency-exchange',  'color'=>'info'],
                    ['id'=>'kpiBWeather',    'label'=>'Cuaca',        'icon'=>'bi-thermometer-half',   'color'=>'danger'],
                    ['id'=>'kpiBRisk',       'label'=>'Risk Score',   'icon'=>'bi-shield-exclamation', 'color'=>'danger'],
                    ['id'=>'kpiBExport',     'label'=>'Ekspor',       'icon'=>'bi-arrow-up-right-circle','color'=>'success'],
                    ['id'=>'kpiBImport',     'label'=>'Impor',        'icon'=>'bi-arrow-down-left-circle','color'=>'secondary'],
                    ['id'=>'kpiBGrowth',     'label'=>'Pertumbuhan',  'icon'=>'bi-graph-up-arrow',     'color'=>'success'],
                ];
                @endphp

                <div class="d-flex flex-column gap-2">
                    @foreach($kpiRowsB as $row)
                    <div class="d-flex justify-content-between align-items-center py-1">
                        <span class="text-secondary small d-flex align-items-center gap-2">
                            <i class="bi {{ $row['icon'] }} text-{{ $row['color'] }}"></i>
                            {{ $row['label'] }}
                        </span>
                        <div class="d-flex align-items-center gap-2">
                            <span id="{{ $row['id'] }}" class="fw-semibold text-dark">–</span>
                            @if($row['label'] === 'Risk Score')
                                <span id="kpiBRiskBadge" class="badge rounded-pill" style="font-size:.7rem;"></span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>{{-- /KPI row --}}

    {{-- ------------------------------------------------
         CHARTS
         ------------------------------------------------ --}}
    <div class="row g-4 mb-4">

        {{-- Radar Chart (full width on mobile, half on desktop) --}}
        <div class="col-12 col-lg-6">
            <div class="card viz-card p-4 h-100 cmp-fade cmp-fade-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Radar Perbandingan</h6>
                        <p class="text-secondary mb-0" style="font-size:.8rem;">Perbandingan semua indikator utama.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge rounded-pill px-2 py-1" style="background:rgba(37,99,235,.1);color:#2563EB;font-size:.7rem;" id="labelCountryA">Negara A</span>
                        <span class="badge rounded-pill px-2 py-1" style="background:rgba(8,145,178,.1);color:#0891B2;font-size:.7rem;" id="labelCountryB">Negara B</span>
                    </div>
                </div>
                <div class="cmp-chart-box">
                    <canvas id="chartRadar"></canvas>
                </div>
            </div>
        </div>

        {{-- GDP Bar --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card viz-card p-4 h-100 cmp-fade cmp-fade-4">
                <h6 class="fw-bold text-dark mb-1">GDP ($T)</h6>
                <p class="text-secondary mb-3" style="font-size:.8rem;">Perbandingan GDP.</p>
                <div class="cmp-chart-box">
                    <canvas id="chartGdpBar"></canvas>
                </div>
            </div>
        </div>

        {{-- Inflation Bar + Risk Bar stacked --}}
        <div class="col-12 col-md-6 col-lg-3 d-flex flex-column gap-4">
            <div class="card viz-card p-4 cmp-fade cmp-fade-5">
                <h6 class="fw-bold text-dark mb-1">Inflasi (%)</h6>
                <p class="text-secondary mb-3" style="font-size:.8rem;">Perbandingan Inflasi.</p>
                <div class="cmp-chart-box" style="height:160px;">
                    <canvas id="chartInflationBar"></canvas>
                </div>
            </div>
            <div class="card viz-card p-4 cmp-fade cmp-fade-6">
                <h6 class="fw-bold text-dark mb-1">Risk Score (/5)</h6>
                <p class="text-secondary mb-3" style="font-size:.8rem;">Perbandingan Risiko.</p>
                <div class="cmp-chart-box" style="height:160px;">
                    <canvas id="chartRiskBar"></canvas>
                </div>
            </div>
        </div>

    </div>{{-- /charts --}}

    {{-- ------------------------------------------------
         COMPARISON TABLE
         ------------------------------------------------ --}}
    <div class="card viz-card overflow-hidden mb-4 cmp-fade">
        {{-- Table header toolbar --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-4" style="background:#FAFCFF; border-bottom:1px solid #E2E8F0;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-table text-primary fs-5"></i>
                <h6 class="fw-bold text-dark mb-0">Tabel Perbandingan Indikator</h6>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm d-flex align-items-center gap-1 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="height:34px;border-radius:8px;font-size:.82rem;">
                        <i class="bi bi-download"></i> Ekspor
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                        <li><button class="dropdown-item small py-2" onclick="alert('Simulasi ekspor PDF...')"><i class="bi bi-filetype-pdf text-danger me-2"></i>PDF</button></li>
                        <li><button class="dropdown-item small py-2" onclick="alert('Simulasi ekspor Excel...')"><i class="bi bi-filetype-xls text-success me-2"></i>Excel</button></li>
                        <li><button class="dropdown-item small py-2" onclick="alert('Simulasi ekspor CSV...')"><i class="bi bi-filetype-csv text-info me-2"></i>CSV</button></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table cmp-table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th><span id="labelCountryA2" class="cmp-label-a">Negara A</span></th>
                        <th><span id="labelCountryB2" class="cmp-label-b">Negara B</span></th>
                        <th>Selisih</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="cmpTableBody">
                    {{-- Populated by comparison.js --}}
                    <tr>
                        <td colspan="5" class="text-center text-secondary py-4">
                            <i class="bi bi-info-circle me-2"></i>Tekan "Bandingkan" untuk menampilkan data.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ------------------------------------------------
         SUMMARY PANEL
         ------------------------------------------------ --}}
    <div class="card viz-card p-4 cmp-fade">
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="p-2 rounded-3" style="background:rgba(37,99,235,.08);">
                <i class="bi bi-lightbulb-fill text-primary fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-0">Ringkasan Analisis</h6>
                <p class="text-secondary small mb-0">Kesimpulan otomatis berdasarkan data perbandingan.</p>
            </div>
        </div>
        <div id="summaryList" class="d-flex flex-column gap-2">
            <p class="text-secondary text-center py-3">Panel ringkasan akan muncul setelah perbandingan selesai.</p>
        </div>
    </div>

</div>{{-- /secResult --}}

</div>{{-- /cmp-page-wrapper --}}
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
{!! file_get_contents(resource_path('js/comparison/comparison-data.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/comparison/comparison-charts.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/comparison/comparison.js')) !!}
</script>
@endsection
