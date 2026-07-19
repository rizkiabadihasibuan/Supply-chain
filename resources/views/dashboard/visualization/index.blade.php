{{--
    Data Visualization Dashboard - Milestone 3.10
    resources/views/dashboard/visualization/index.blade.php

    Layout:
      Header → Toolbar → [Loading|Empty|Error|Main Content]
      Main Content: Left (KPI + Charts + Table) | Right (Insight Panel)
--}}
@extends('layouts.user.app')

@section('title', 'Data Visualization Dashboard – SupplyChain Platform')

@section('styles')
{{-- Modular CSS --}}
<link rel="stylesheet" href="{{ asset('css/dashboard/visualization.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard/chart.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard/toolbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard/table.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard/responsive.css') }}">
@endsection

@section('content')
<div class="viz-page-wrapper">

    {{-- ====================================================
         PAGE HEADER
         ==================================================== --}}
    @include('dashboard.visualization.components.page-header')

    {{-- ====================================================
         TOOLBAR FILTER
         ==================================================== --}}
    @include('dashboard.visualization.components.toolbar-filter')

    {{-- ====================================================
         LOADING SKELETON (shown first)
         ==================================================== --}}
    @include('dashboard.visualization.components.loading-placeholder')

    {{-- ====================================================
         EMPTY STATE
         ==================================================== --}}
    @include('dashboard.visualization.components.empty-state')

    {{-- ====================================================
         ERROR STATE
         ==================================================== --}}
    @include('dashboard.visualization.components.error-state')

    {{-- ====================================================
         MAIN CONTENT (hidden until JS loads data)
         ==================================================== --}}
    <div id="mainContent" style="display:none;">

        {{-- ------------------------------------------------
             KPI CARDS – 6 cards (2 per row on mobile, 3 on tablet, 6 on desktop)
             ------------------------------------------------ --}}
        <div class="row g-3 mb-4">

            {{-- Card 1: GDP --}}
            <div class="col-6 col-md-4 col-xl-2 viz-fade-in viz-stagger-1">
                <div class="card viz-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon-box" style="background:rgba(37,99,235,0.09);">
                            <i class="bi bi-cash-coin text-primary fs-5"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size:.7rem;">Normal</span>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">GDP Global</p>
                        <div class="kpi-value text-dark mb-1">$3.5T</div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="kpi-trend text-success"><i class="bi bi-arrow-up-short"></i>+1.8%</span>
                            <span class="text-secondary" style="font-size:.72rem;">Meningkat YoY</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Inflation --}}
            <div class="col-6 col-md-4 col-xl-2 viz-fade-in viz-stagger-2">
                <div class="card viz-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon-box" style="background:rgba(217,119,6,0.09);">
                            <i class="bi bi-percent text-warning fs-5"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill" style="font-size:.7rem;">Waspada</span>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Rata-rata Inflasi</p>
                        <div class="kpi-value text-warning mb-1">3.2%</div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="kpi-trend text-success"><i class="bi bi-arrow-down-short"></i>-0.4%</span>
                            <span class="text-secondary" style="font-size:.72rem;">Mereda MoM</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Exchange Rate --}}
            <div class="col-6 col-md-4 col-xl-2 viz-fade-in viz-stagger-3">
                <div class="card viz-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon-box" style="background:rgba(2,132,199,0.09);">
                            <i class="bi bi-currency-exchange" style="color:#0284C7;font-size:1.2rem;"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill" style="font-size:.7rem;">Stabil</span>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Kurs USD/IDR</p>
                        <div class="kpi-value text-dark mb-1" style="font-size:1.35rem;">Rp 16.250</div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="kpi-trend text-danger"><i class="bi bi-arrow-up-short"></i>+0.3%</span>
                            <span class="text-secondary" style="font-size:.72rem;">Volatilitas Rendah</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Risk Score --}}
            <div class="col-6 col-md-4 col-xl-2 viz-fade-in viz-stagger-4">
                <div class="card viz-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon-box" style="background:rgba(220,38,38,0.09);">
                            <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill" style="font-size:.7rem;">Sedang</span>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Risk Score Global</p>
                        <div class="kpi-value text-danger mb-1">2.80<span class="fs-6 fw-normal text-secondary">/5</span></div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="kpi-trend text-danger"><i class="bi bi-arrow-up-short"></i>+0.1</span>
                            <span class="text-secondary" style="font-size:.72rem;">Level Sedang</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 5: Population --}}
            <div class="col-6 col-md-4 col-xl-2 viz-fade-in viz-stagger-5">
                <div class="card viz-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon-box" style="background:rgba(37,99,235,0.09);">
                            <i class="bi bi-people text-primary fs-5"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill" style="font-size:.7rem;">Normal</span>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Populasi Terpantau</p>
                        <div class="kpi-value text-dark mb-1">1.45B</div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="kpi-trend text-success"><i class="bi bi-arrow-up-short"></i>+0.5%</span>
                            <span class="text-secondary" style="font-size:.72rem;">Pertumbuhan Stabil</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 6: Last Update --}}
            <div class="col-6 col-md-4 col-xl-2 viz-fade-in viz-stagger-6">
                <div class="card viz-card p-4 h-100 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon-box" style="background:rgba(22,163,74,0.09);">
                            <i class="bi bi-clock text-success fs-5"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size:.7rem;">
                            <span class="me-1">●</span>Live
                        </span>
                    </div>
                    <div>
                        <p class="text-secondary small fw-medium mb-1">Last Update</p>
                        <div class="kpi-value text-dark mb-1" style="font-size:1.3rem;">Baru Saja</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-secondary" style="font-size:.72rem;">Terhubung World Bank</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /KPI row --}}

        {{-- ------------------------------------------------
             MAIN GRID: Left (Charts + Table) | Right (Insight)
             ------------------------------------------------ --}}
        <div class="row g-4">

            {{-- ============================================
                 LEFT COLUMN
                 ============================================ --}}
            <div class="col-12 col-lg-8 viz-main-left">

                {{-- ----------------------------------------
                     CHART GRID: 4 Charts in 2x2
                     ---------------------------------------- --}}
                <div class="row g-4 mb-4">

                    {{-- CHART 1: GDP Trend (Line) --}}
                    <div class="col-12 col-md-6 viz-chart-col viz-fade-in" id="chartWrap_gdp">
                        <div class="card viz-card p-4 h-100">
                            <div class="card-header border-0 bg-transparent p-0 d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">GDP Trend</h6>
                                    <p class="text-secondary mb-0" style="font-size:.8rem;">Perkembangan GDP berdasarkan tahun.</p>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <button class="chart-action-btn" onclick="ChartManager.refresh('gdp','btnRefreshGdp')" id="btnRefreshGdp" data-bs-toggle="tooltip" title="Segarkan grafik GDP">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button class="chart-action-btn" onclick="alert('Simulasi expand GDP Chart')" data-bs-toggle="tooltip" title="Perluas Grafik">
                                        <i class="bi bi-arrows-angle-expand"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button class="chart-action-btn" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li><button class="dropdown-item small py-2" onclick="alert('Simulasi unduh PNG...')"><i class="bi bi-image me-2"></i>Unduh PNG</button></li>
                                            <li><button class="dropdown-item small py-2" onclick="TableManager.exportCSV()"><i class="bi bi-filetype-csv me-2"></i>Ekspor CSV</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-canvas-container">
                                <canvas id="chartGdp"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- CHART 2: Inflation Trend (Bar – orange) --}}
                    <div class="col-12 col-md-6 viz-chart-col viz-fade-in" id="chartWrap_inflation" style="animation-delay:.1s">
                        <div class="card viz-card p-4 h-100">
                            <div class="card-header border-0 bg-transparent p-0 d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Inflation Trend</h6>
                                    <p class="text-secondary mb-0" style="font-size:.8rem;">Perubahan tingkat inflasi.</p>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <button class="chart-action-btn" onclick="ChartManager.refresh('inflation','btnRefreshInflation')" id="btnRefreshInflation" data-bs-toggle="tooltip" title="Segarkan grafik Inflasi">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button class="chart-action-btn" onclick="alert('Simulasi expand Inflation Chart')" data-bs-toggle="tooltip" title="Perluas Grafik">
                                        <i class="bi bi-arrows-angle-expand"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button class="chart-action-btn" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li><button class="dropdown-item small py-2" onclick="alert('Simulasi unduh PNG...')"><i class="bi bi-image me-2"></i>Unduh PNG</button></li>
                                            <li><button class="dropdown-item small py-2" onclick="TableManager.exportCSV()"><i class="bi bi-filetype-csv me-2"></i>Ekspor CSV</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-canvas-container">
                                <canvas id="chartInflation"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- CHART 3: Currency Trend (Area) --}}
                    <div class="col-12 col-md-6 viz-chart-col viz-fade-in" id="chartWrap_currency" style="animation-delay:.2s">
                        <div class="card viz-card p-4 h-100">
                            <div class="card-header border-0 bg-transparent p-0 d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Currency Trend</h6>
                                    <p class="text-secondary mb-0" style="font-size:.8rem;">Perubahan kurs mata uang.</p>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <button class="chart-action-btn" onclick="ChartManager.refresh('currency','btnRefreshCurrency')" id="btnRefreshCurrency" data-bs-toggle="tooltip" title="Segarkan grafik Kurs">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button class="chart-action-btn" onclick="alert('Simulasi expand Currency Chart')" data-bs-toggle="tooltip" title="Perluas Grafik">
                                        <i class="bi bi-arrows-angle-expand"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button class="chart-action-btn" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li><button class="dropdown-item small py-2" onclick="alert('Simulasi unduh PNG...')"><i class="bi bi-image me-2"></i>Unduh PNG</button></li>
                                            <li><button class="dropdown-item small py-2" onclick="TableManager.exportCSV()"><i class="bi bi-filetype-csv me-2"></i>Ekspor CSV</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-canvas-container">
                                <canvas id="chartCurrency"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- CHART 4: Risk Trend (Line – red) --}}
                    <div class="col-12 col-md-6 viz-chart-col viz-fade-in" id="chartWrap_risk" style="animation-delay:.3s">
                        <div class="card viz-card p-4 h-100">
                            <div class="card-header border-0 bg-transparent p-0 d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h6 class="fw-bold text-dark mb-0">Risk Trend</h6>
                                        <span class="risk-badge-line bg-success bg-opacity-10 text-success border border-success border-opacity-25">Low &lt;2.0</span>
                                        <span class="risk-badge-line bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Medium &lt;3.5</span>
                                        <span class="risk-badge-line bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">High ≥3.5</span>
                                    </div>
                                    <p class="text-secondary mb-0" style="font-size:.8rem;">Perubahan tingkat risiko.</p>
                                </div>
                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                    <button class="chart-action-btn" onclick="ChartManager.refresh('risk','btnRefreshRisk')" id="btnRefreshRisk" data-bs-toggle="tooltip" title="Segarkan grafik Risiko">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    <button class="chart-action-btn" onclick="alert('Simulasi expand Risk Chart')" data-bs-toggle="tooltip" title="Perluas Grafik">
                                        <i class="bi bi-arrows-angle-expand"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button class="chart-action-btn" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                            <li><button class="dropdown-item small py-2" onclick="alert('Simulasi unduh PNG...')"><i class="bi bi-image me-2"></i>Unduh PNG</button></li>
                                            <li><button class="dropdown-item small py-2" onclick="TableManager.exportCSV()"><i class="bi bi-filetype-csv me-2"></i>Ekspor CSV</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-canvas-container">
                                <canvas id="chartRisk"></canvas>
                            </div>
                        </div>
                    </div>

                </div>{{-- /chart grid --}}

                {{-- ----------------------------------------
                     ANALYTICS TABLE
                     ---------------------------------------- --}}
                <div class="card viz-card overflow-hidden viz-fade-in">

                    {{-- Table Toolbar --}}
                    <div class="table-toolbar d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-table text-primary"></i>
                            <h6 class="fw-bold text-dark mb-0">Matriks Analisis Makroekonomi</h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2" style="font-size:.72rem;">
                                <span id="tableVisibleCount">6</span> negara
                            </span>
                        </div>

                        {{-- Table search + export + refresh --}}
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- Show entries --}}
                            <div class="d-flex align-items-center gap-2 me-2">
                                <label class="text-secondary small mb-0">Tampilkan</label>
                                <select class="form-select form-select-sm" style="width:68px; border-radius:8px;">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                </select>
                            </div>

                            {{-- Export dropdown --}}
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" style="height:34px; border-radius:8px; font-size:.82rem;">
                                    <i class="bi bi-download"></i> Ekspor
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                    <li><button class="dropdown-item small py-2" onclick="TableManager.exportPDF()"><i class="bi bi-filetype-pdf text-danger me-2"></i>PDF</button></li>
                                    <li><button class="dropdown-item small py-2" onclick="TableManager.exportExcel()"><i class="bi bi-filetype-xls text-success me-2"></i>Excel</button></li>
                                    <li><button class="dropdown-item small py-2" onclick="TableManager.exportCSV()"><i class="bi bi-filetype-csv text-info me-2"></i>CSV</button></li>
                                    <li><button class="dropdown-item small py-2" onclick="TableManager.exportPrint()"><i class="bi bi-printer me-2"></i>Print</button></li>
                                </ul>
                            </div>

                            {{-- Refresh --}}
                            <button class="btn btn-light btn-sm d-flex align-items-center gap-1" onclick="ChartManager.refreshAll('btnRefreshPage')" style="height:34px; border-radius:8px; font-size:.82rem;" data-bs-toggle="tooltip" title="Segarkan data tabel">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table analytics-table table-hover table-striped mb-0">
                            <thead>
                                <tr>
                                    <th data-sort-col="name" onclick="TableManager.sortByColumn('name')" style="cursor:pointer; min-width:140px;">
                                        Negara <i class="bi bi-arrow-down-up ms-1 text-muted opacity-50"></i>
                                    </th>
                                    <th data-sort-col="gdpRaw" onclick="TableManager.sortByColumn('gdpRaw')" style="cursor:pointer;">
                                        GDP <i class="bi bi-arrow-down-up ms-1 text-muted opacity-50"></i>
                                    </th>
                                    <th data-sort-col="inflationRaw" onclick="TableManager.sortByColumn('inflationRaw')" style="cursor:pointer;">
                                        Inflasi <i class="bi bi-arrow-down-up ms-1 text-muted opacity-50"></i>
                                    </th>
                                    <th>Exchange Rate</th>
                                    <th data-sort-col="riskScore" onclick="TableManager.sortByColumn('riskScore')" style="cursor:pointer;">
                                        Risk Score <i class="bi bi-arrow-down-up ms-1 text-muted opacity-50"></i>
                                    </th>
                                    <th>Populasi</th>
                                    <th>Last Update</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="analyticsTableBody">

                                @php
                                $tableData = [
                                    ['flag'=>'🇮🇩','name'=>'Indonesia','region'=>'asia','currency'=>'IDR','gdp'=>'$1.42T','gdpRaw'=>1.42,'inflation'=>'2.8%','inflationRaw'=>2.8,'rate'=>'Rp 16.250','riskScore'=>1.25,'riskLevel'=>'low','population'=>'279.1 Juta','lastUpdate'=>'Baru saja'],
                                    ['flag'=>'🇨🇳','name'=>'China','region'=>'asia','currency'=>'CNY','gdp'=>'$17.90T','gdpRaw'=>17.90,'inflation'=>'1.8%','inflationRaw'=>1.8,'rate'=>'¥ 7.24','riskScore'=>4.25,'riskLevel'=>'high','population'=>'1.41 Miliar','lastUpdate'=>'5 Menit lalu'],
                                    ['flag'=>'🇺🇸','name'=>'Amerika Serikat','region'=>'america','currency'=>'USD','gdp'=>'$28.20T','gdpRaw'=>28.20,'inflation'=>'3.4%','inflationRaw'=>3.4,'rate'=>'USD (Base)','riskScore'=>3.48,'riskLevel'=>'medium','population'=>'335.9 Juta','lastUpdate'=>'1 Jam lalu'],
                                    ['flag'=>'🇳🇱','name'=>'Belanda','region'=>'europe','currency'=>'EUR','gdp'=>'$1.01T','gdpRaw'=>1.01,'inflation'=>'4.1%','inflationRaw'=>4.1,'rate'=>'€ 0.92','riskScore'=>1.85,'riskLevel'=>'low','population'=>'17.9 Juta','lastUpdate'=>'2 Jam lalu'],
                                    ['flag'=>'🇸🇩','name'=>'Sudan','region'=>'africa','currency'=>'SDG','gdp'=>'$0.05T','gdpRaw'=>0.05,'inflation'=>'75.0%','inflationRaw'=>75.0,'rate'=>'SDG 601.5','riskScore'=>4.80,'riskLevel'=>'high','population'=>'47.9 Juta','lastUpdate'=>'Kemarin'],
                                    ['flag'=>'🇸🇬','name'=>'Singapura','region'=>'asia','currency'=>'SGD','gdp'=>'$0.50T','gdpRaw'=>0.50,'inflation'=>'2.4%','inflationRaw'=>2.4,'rate'=>'S$ 1.34','riskScore'=>1.10,'riskLevel'=>'low','population'=>'5.9 Juta','lastUpdate'=>'30 Mnt lalu'],
                                ];
                                @endphp

                                @foreach($tableData as $row)
                                @php
                                    $riskClass = match($row['riskLevel']) {
                                        'high'   => 'danger',
                                        'medium' => 'warning',
                                        default  => 'success',
                                    };
                                    $riskLabel = match($row['riskLevel']) {
                                        'high'   => 'High Risk',
                                        'medium' => 'Medium Risk',
                                        default  => 'Low Risk',
                                    };
                                    $inflationClass = $row['inflationRaw'] > 10 ? 'text-danger fw-bold' : ($row['inflationRaw'] > 5 ? 'text-warning fw-semibold' : 'text-success');
                                @endphp
                                <tr data-table-row
                                    data-name="{{ strtolower($row['name']) }}"
                                    data-region="{{ $row['region'] }}"
                                    data-currency="{{ $row['currency'] }}"
                                    data-risklevel="{{ $row['riskLevel'] }}"
                                    data-gdpraw="{{ $row['gdpRaw'] }}"
                                    data-inflationraw="{{ $row['inflationRaw'] }}"
                                    data-riskscore="{{ $row['riskScore'] }}">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="table-flag">{{ $row['flag'] }}</span>
                                            <span class="table-country-name">{{ $row['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="fw-semibold">{{ $row['gdp'] }}</td>
                                    <td class="{{ $inflationClass }}">{{ $row['inflation'] }}</td>
                                    <td class="text-secondary">{{ $row['rate'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold {{ $riskClass === 'danger' ? 'text-danger' : ($riskClass === 'warning' ? 'text-warning' : 'text-success') }}">
                                                {{ $row['riskScore'] }}
                                            </span>
                                            <div class="progress flex-grow-1" style="height:5px; border-radius:4px; max-width:48px;">
                                                <div class="progress-bar bg-{{ $riskClass }}" style="width:{{ ($row['riskScore'] / 5) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary">{{ $row['population'] }}</td>
                                    <td class="text-secondary" style="font-size:.82rem;">{{ $row['lastUpdate'] }}</td>
                                    <td>
                                        <span class="status-badge bg-{{ $riskClass }} bg-opacity-10 text-{{ $riskClass }} border border-{{ $riskClass }} border-opacity-25">
                                            {{ $riskLabel }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    {{-- Table Footer: Pagination placeholder --}}
                    <div class="table-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <p class="text-secondary small mb-0">
                            Menampilkan <span id="tableVisibleCount" class="fw-semibold text-dark">6</span> dari 6 negara
                        </p>
                        <nav aria-label="Pagination tabel analitik">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled"><a class="page-link rounded-start-3" href="#">«</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item disabled"><a class="page-link rounded-end-3" href="#">»</a></li>
                            </ul>
                        </nav>
                    </div>

                </div>{{-- /analytics table card --}}

            </div>{{-- /left column --}}

            {{-- ============================================
                 RIGHT COLUMN – Insight Panel
                 ============================================ --}}
            <div class="col-12 col-lg-4 viz-main-right">
                <div class="d-flex flex-column gap-3">

                    {{-- Insight 1: Top Performing Country --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.05s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-award-fill text-primary fs-5"></i> Top Performing
                            </h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill" style="font-size:.7rem;">A+ Rank</span>
                        </div>
                        <div class="text-center py-2 border-bottom mb-3">
                            <span class="d-block mb-1" style="font-size:2rem;">🇸🇬</span>
                            <h6 class="fw-bold text-dark mb-1">Singapura</h6>
                            <p class="text-secondary small mb-0">Logistik &amp; Distribusi Superb</p>
                        </div>
                        <div class="d-flex flex-column gap-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary small">Efisiensi Port</span>
                                <span class="text-success fw-semibold small">98.5%</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary small">Risk Score</span>
                                <span class="text-success fw-semibold small">1.10 / 5</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary small">Inflasi</span>
                                <span class="text-dark fw-semibold small">2.4%</span>
                            </div>
                        </div>
                    </div>

                    {{-- Insight 2: Highest GDP --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.10s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-gem text-primary fs-5"></i> Highest GDP
                            </h6>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill" style="font-size:.7rem;">Leader</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-dark fw-bold">🇺🇸 Amerika Serikat</span>
                            <span class="badge bg-primary text-white px-3 py-2 rounded-3">$28.20T</span>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">Perekonomian terbesar di dunia dengan basis konsumsi yang kuat.</p>
                    </div>

                    {{-- Insight 3: Highest Inflation --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.15s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-percent text-danger fs-5"></i> Highest Inflation
                            </h6>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill" style="font-size:.7rem;">Critical</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">🇸🇩 Sudan</span>
                            <span class="text-danger fw-bold fs-5">+75.0%</span>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">Hiperinflasi akibat ketidakstabilan politik dan konflik bersenjata.</p>
                    </div>

                    {{-- Insight 4: Highest Risk --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.20s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-shield-exclamation text-danger fs-5"></i> Highest Risk
                            </h6>
                            <span class="badge bg-danger text-white rounded-pill" style="font-size:.7rem;">Danger Zone</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-dark">🇸🇩 Jalur Laut Merah</span>
                            <span class="badge bg-danger text-white px-3 py-2 rounded-3">4.80 / 5</span>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">Gangguan logistik akibat konflik dan blokade jalur pelayaran.</p>
                    </div>

                    {{-- Insight 5: Strongest Currency --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.25s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-currency-exchange text-success fs-5"></i> Strongest Currency
                            </h6>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size:.7rem;">Forex</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">🇪🇺 Euro (EUR)</span>
                            <span class="text-success fw-semibold">€ 0.92 / USD</span>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">Menguat +0.14% terhadap Dollar minggu ini.</p>
                    </div>

                    {{-- Insight 6: Fastest Economic Growth --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.30s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-graph-up-arrow text-success fs-5"></i> Fastest Growth
                            </h6>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size:.7rem;">Expansion</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-dark">🇮🇩 Indonesia</span>
                            <span class="text-success fw-bold fs-6">+5.05% YoY</span>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">Didorong pertumbuhan konsumsi domestik dan ekspor komoditas.</p>
                    </div>

                    {{-- Worst Growth --}}
                    <div class="card viz-card p-4 viz-fade-in" style="animation-delay:.35s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-graph-down-arrow text-danger fs-5"></i> Worst Growth
                            </h6>
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill" style="font-size:.7rem;">Contraction</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-danger">
                            <span class="fw-bold">🇸🇩 Sudan</span>
                            <span class="fw-bold fs-6">-18.3% YoY</span>
                        </div>
                        <p class="text-secondary small mt-2 mb-0">Kontraksi ekonomi akibat ketidakstabilan geopolitik berkelanjutan.</p>
                    </div>

                </div>
            </div>{{-- /right column --}}

        </div>{{-- /main row --}}

    </div>{{-- /mainContent --}}

    {{-- ====================================================
         EXPORT TOAST Notification
         ==================================================== --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
        <div id="exportToast" class="toast border-0 shadow-sm rounded-3" role="alert" aria-live="assertive">
            <div class="toast-header border-0" style="background:#2563EB;">
                <i class="bi bi-download text-white me-2"></i>
                <strong class="text-white me-auto">Ekspor Data</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body small" id="exportToastMsg">
                Mempersiapkan ekspor data...
            </div>
        </div>
    </div>

</div>{{-- /viz-page-wrapper --}}
@endsection

@section('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

{{-- Modular JS (inline from files to avoid Vite dependency) --}}
<script>
{!! file_get_contents(resource_path('js/dashboard/placeholder-data.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/dashboard/chart-manager.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/dashboard/filter.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/dashboard/table.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/dashboard/visualization.js')) !!}
</script>
@endsection
