@extends('layouts.app')

@section('title', 'Data Visualization Dashboard - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Data Visualization Dashboard" subtitle="Visualisasikan kondisi ekonomi, cuaca, kurs, dan risiko secara interaktif." :breadcrumbs="['Data Visualization' => '#']">
    <x-slot name="actions">
        <button class="btn btn-primary" id="btn-refresh-charts" onclick="triggerChartsSync()" style="min-height: 44px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Grafik
        </button>
    </x-slot>
</x-page-header>

<!-- Summary KPI Cards Row (6 Cards) -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="GDP Global (Monitored)" value="$3.5T" description="Tren Meningkat" icon="bi-graph-up-arrow" type="primary" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Rata-rata Inflasi" value="+3.2%" description="Siaga Stabil" icon="bi-percent" type="warning" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Kurs USD/IDR" value="Rp16.250" description="Volatilitas Rendah" icon="bi-currency-exchange" type="success" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Skor Risiko Global" value="2.80" description="Level Sedang" icon="bi-exclamation-triangle" type="warning" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Negara Teranalisis" value="12" description="Total Pemantauan" icon="bi-check-circle" type="info" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Update Terakhir" value="Live" description="Terhubung World Bank" icon="bi-arrow-repeat" type="success" />
    </div>
</div>

<!-- Header Toolbar Component -->
<x-toolbar>
    <!-- Search Box Component -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-12">
        <x-search-input placeholder="Cari negara..." id="search-chart-country" oninput="applyChartFilters()" />
    </div>
    
    <!-- Region Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-chart-region" onchange="applyChartFilters()">
            <option value="all">Semua Wilayah</option>
            <option value="asia">Asia</option>
            <option value="europe">Eropa</option>
            <option value="america">Amerika</option>
            <option value="africa">Afrika</option>
            <option value="oceania">Oceania</option>
        </x-filter-dropdown>
    </div>

    <!-- Year Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-chart-year" onchange="applyChartFilters()">
            <option value="2026" selected>Tahun 2026</option>
            <option value="2025">Tahun 2025</option>
            <option value="2024">Tahun 2024</option>
        </x-filter-dropdown>
    </div>

    <!-- Chart Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-chart-category" onchange="toggleChartsDisplay()">
            <option value="all">Semua Grafik</option>
            <option value="gdp">GDP Trend</option>
            <option value="inflation">Inflation Trend</option>
            <option value="currency">Currency Trend</option>
            <option value="risk">Risk Trend</option>
        </x-filter-dropdown>
    </div>

    <!-- Date Picker Placeholder (Sort dropdown repurposed) -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-6">
        <x-filter-dropdown id="filter-chart-sort" onchange="applyChartFilters()">
            <option value="name">Urutkan: Nama Negara</option>
            <option value="gdp-desc">Urutkan: GDP Tertinggi</option>
            <option value="inflation-desc">Urutkan: Inflasi Terbesar</option>
            <option value="risk-desc">Urutkan: Risiko Terbesar</option>
        </x-filter-dropdown>
    </div>

    <x-slot name="simulations">
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateSkeletonLoading()">
            <i class="bi bi-hourglass-split me-2"></i>Simulasikan Loading
        </button>
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateEmptyState()">
            <i class="bi bi-x-circle me-2"></i>Simulasikan Data Kosong
        </button>
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateErrorState()">
            <i class="bi bi-exclamation-octagon me-2"></i>Simulasikan Error
        </button>
    </x-slot>
</x-toolbar>

<!-- Skeleton Loading wrapper -->
<div id="skeleton-container" style="display: none;">
    <x-loading-state type="table" count="1" height="400px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada data analisis visual." description="Negara yang Anda cari tidak ditemukan dalam database analitik." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Gagal Menghubungkan Visualisasi." description="Koneksi satelit data makroekonomi terputus. Silakan coba kembali." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Kolom Kiri: Charts Grid + Table -->
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- SECTION 1: Charts Grid (4 Canvas) -->
            <div class="row g-4" id="charts-grid-container">
                <!-- GDP Trend (Line Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-gdp">
                    <div class="card p-4 border-0 h-100">
                        <h6 class="fw-bold text-dark mb-1">GDP Trend (Line Chart)</h6>
                        <p class="text-secondary small mb-3">Tren pertumbuhan PDB regional terpantau (Tahun 2026).</p>
                        <div style="height: 220px; position: relative;">
                            <canvas id="gdpChartCanvas"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Inflation Trend (Bar Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-inflation">
                    <div class="card p-4 border-0 h-100">
                        <h6 class="fw-bold text-dark mb-1">Inflation Trend (Bar Chart)</h6>
                        <p class="text-secondary small mb-3">Tingkat inflasi bulanan negara-negara fokus (Tahun 2026).</p>
                        <div style="height: 220px; position: relative;">
                            <canvas id="inflationChartCanvas"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Currency Trend (Area Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-currency">
                    <div class="card p-4 border-0 h-100">
                        <h6 class="fw-bold text-dark mb-1">Currency Trend (Area Chart)</h6>
                        <p class="text-secondary small mb-3">Volatilitas pergerakan nilai tukar Dollar terhadap Rupiah.</p>
                        <div style="height: 220px; position: relative;">
                            <canvas id="currencyChartCanvas"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Risk Trend (Line Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-risk">
                    <div class="card p-4 border-0 h-100">
                        <h6 class="fw-bold text-dark mb-1">Risk Trend (Line Chart)</h6>
                        <p class="text-secondary small mb-3">Perkembangan indeks kerawanan rantai pasok global.</p>
                        <div style="height: 220px; position: relative;">
                            <canvas id="riskChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Analytics Table component -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-table text-primary me-2"></i>Matriks Analisis Komparatif Makroekonomi</h5>
                
                <x-analytics-table id="visualization-macro-table">
                    <thead>
                        <tr>
                            <th>Negara</th>
                            <th>GDP (Triliun USD)</th>
                            <th>Tingkat Inflasi</th>
                            <th>Kurs Valuta Asing</th>
                            <th>Skor Risiko</th>
                        </tr>
                    </thead>
                    <tbody id="macro-table-body">
                        <!-- Indonesia -->
                        <tr class="macro-table-row" data-name="Indonesia" data-region="asia" data-gdp="1.37" data-inflation="2.8" data-risk="1.25">
                            <td data-label="Negara" class="fw-bold text-dark">🇮🇩 Indonesia</td>
                            <td data-label="GDP">$1.37T</td>
                            <td data-label="Tingkat Inflasi" class="text-success fw-bold">+2.8%</td>
                            <td data-label="Kurs Valuta Asing">Rp16.250 / USD</td>
                            <td data-label="Skor Risiko"><x-badge type="success" text="Low (1.25)" /></td>
                        </tr>

                        <!-- China -->
                        <tr class="macro-table-row" data-name="China" data-region="asia" data-gdp="17.9" data-inflation="1.8" data-risk="4.25">
                            <td data-label="Negara" class="fw-bold text-dark">🇨🇳 China</td>
                            <td data-label="GDP">$17.90T</td>
                            <td data-label="Tingkat Inflasi" class="text-success fw-bold">+1.8%</td>
                            <td data-label="Kurs Valuta Asing">¥7.24 / USD</td>
                            <td data-label="Skor Risiko"><span class="badge" style="background-color: #F97316; color: white;">High (4.25)</span></td>
                        </tr>

                        <!-- Amerika Serikat -->
                        <tr class="macro-table-row" data-name="Amerika Serikat" data-region="america" data-gdp="25.4" data-inflation="3.4" data-risk="3.48">
                            <td data-label="Negara" class="fw-bold text-dark">🇺🇸 Amerika Serikat</td>
                            <td data-label="GDP">$25.40T</td>
                            <td data-label="Tingkat Inflasi" class="text-warning fw-bold">+3.4%</td>
                            <td data-label="Kurs Valuta Asing">Base Currency</td>
                            <td data-label="Skor Risiko"><x-badge type="warning" text="Medium (3.48)" /></td>
                        </tr>

                        <!-- Belanda -->
                        <tr class="macro-table-row" data-name="Belanda" data-region="europe" data-gdp="1.01" data-inflation="4.1" data-risk="1.85">
                            <td data-label="Negara" class="fw-bold text-dark">🇳🇱 Belanda</td>
                            <td data-label="GDP">$1.01T</td>
                            <td data-label="Tingkat Inflasi" class="text-danger fw-bold">+4.1%</td>
                            <td data-label="Kurs Valuta Asing">€0.92 / USD</td>
                            <td data-label="Skor Risiko"><x-badge type="success" text="Low (1.85)" /></td>
                        </tr>

                        <!-- Sudan -->
                        <tr class="macro-table-row" data-name="Sudan" data-region="africa" data-gdp="0.05" data-inflation="75.0" data-risk="4.80">
                            <td data-label="Negara" class="fw-bold text-dark">🇸🇩 Sudan</td>
                            <td data-label="GDP">$0.05T</td>
                            <td data-label="Tingkat Inflasi" class="text-danger fw-bold">+75.0%</td>
                            <td data-label="Kurs Valuta Asing">SDG 601.5 / USD</td>
                            <td data-label="Skor Risiko"><x-badge type="danger" text="Critical (4.80)" /></td>
                        </tr>
                    </tbody>
                </x-analytics-table>
            </div>

        </div>
    </div>

    <!-- Kolom Kanan: Insight Panel widgets -->
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-4">
            
            <!-- WIDGET 1: Top Country Summary -->
            <x-insight-widget title="Negara Fokus Ekspor" icon="bi-award-fill">
                <div class="text-center pb-3 border-bottom mb-3">
                    <span class="fs-1 d-block mb-1">🇺🇸</span>
                    <h5 class="fw-bold text-dark mb-1">Amerika Serikat</h5>
                    <span class="text-secondary small d-block">Pusat Manufaktur Terbesar</span>
                </div>
                <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Porsi Terhadap GDP Global:</span>
                        <span class="text-dark fw-bold">25.4%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Tingkat Risiko Rantai Pasok:</span>
                        <span class="text-warning fw-bold">Medium (3.48)</span>
                    </div>
                </div>
            </x-insight-widget>

            <!-- WIDGET 2: Highest Inflation -->
            <x-insight-widget title="Suku Bunga & Inflasi Ekstrem" icon="bi-percent">
                <div class="p-3 border border-danger-subtle rounded-3 bg-danger bg-opacity-10 text-danger-emphasis mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold small">🇸🇩 Sudan (Khartoum)</span>
                        <x-badge type="danger" text="Kritis" />
                    </div>
                    <h3 class="fw-bold text-danger mb-0">+75.0%</h3>
                    <span class="text-secondary small" style="font-size: 0.725rem;">Tingkat inflasi tertinggi di jalur logistik terpantau.</span>
                </div>
            </x-insight-widget>

            <!-- WIDGET 3: Highest Risk -->
            <x-insight-widget title="Indeks Kerawanan Tertinggi" icon="bi-shield-exclamation">
                <div class="d-flex flex-column gap-3" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <span>🇸🇩 Sudan (Jalur Merah)</span>
                        <span class="text-danger fw-bold">4.80 / 5.0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <span>🇨🇳 China (Jalur Jingga)</span>
                        <span class="text-warning fw-bold" style="color: #F97316 !important;">4.25 / 5.0</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>🇺🇸 Amerika Serikat (Kuning)</span>
                        <span class="text-warning fw-bold">3.48 / 5.0</span>
                    </div>
                </div>
            </x-insight-widget>

            <!-- WIDGET 4: Strongest Currency -->
            <x-insight-widget title="Kurs Valuta Menguat" icon="bi-currency-exchange">
                <div class="d-flex flex-column gap-2.5" style="font-size: 0.8rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">🇪🇺 Euro (EUR):</span>
                        <span class="text-success fw-bold">+0.14% (Menguat)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">🇯🇵 Yen Jepang (JPY):</span>
                        <span class="text-success fw-bold">+0.09% (Menguat)</span>
                    </div>
                </div>
            </x-insight-widget>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let gdpChart, inflationChart, currencyChart, riskChart;

    document.addEventListener('DOMContentLoaded', function() {
        // Run simulated loader
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
            
            // Instantiate Chart.js charts
            initializeAnalyticsCharts();
        }, 800);
    });

    function initializeAnalyticsCharts() {
        const ctxGdp = document.getElementById('gdpChartCanvas').getContext('2d');
        const ctxInflation = document.getElementById('inflationChartCanvas').getContext('2d');
        const ctxCurrency = document.getElementById('currencyChartCanvas').getContext('2d');
        const ctxRisk = document.getElementById('riskChartCanvas').getContext('2d');

        // Chart 1: GDP Trend (Line)
        gdpChart = new Chart(ctxGdp, {
            type: 'line',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'GDP Amerika Serikat ($T)',
                    data: [24.8, 25.1, 25.3, 25.4],
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Chart 2: Inflation Trend (Bar)
        inflationChart = new Chart(ctxInflation, {
            type: 'bar',
            data: {
                labels: ['ID', 'CN', 'US', 'NL'],
                datasets: [{
                    label: 'Inflasi (%)',
                    data: [2.8, 1.8, 3.4, 4.1],
                    backgroundColor: ['#22C55E', '#22C55E', '#F59E0B', '#F59E0B'],
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Chart 3: Currency Trend (Area)
        currencyChart = new Chart(ctxCurrency, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
                datasets: [{
                    label: 'USD/IDR',
                    data: [16210, 16225, 16240, 16250, 16245],
                    borderColor: '#06B6D4',
                    backgroundColor: 'rgba(6, 182, 212, 0.15)',
                    fill: true,
                    tension: 0.2,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Chart 4: Risk Trend (Line)
        riskChart = new Chart(ctxRisk, {
            type: 'line',
            data: {
                labels: ['Mei', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Risk Score',
                    data: [2.6, 2.75, 2.8],
                    borderColor: '#EF4444',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }

    // Toggle showing specific charts
    function toggleChartsDisplay() {
        const val = document.getElementById('filter-chart-category').value;
        const gdpWrap = document.getElementById('chart-wrap-gdp');
        const infWrap = document.getElementById('chart-wrap-inflation');
        const curWrap = document.getElementById('chart-wrap-currency');
        const riskWrap = document.getElementById('chart-wrap-risk');

        // Reset display
        gdpWrap.style.display = 'block';
        infWrap.style.display = 'block';
        curWrap.style.display = 'block';
        riskWrap.style.display = 'block';

        if (val === 'gdp') {
            infWrap.style.display = 'none';
            curWrap.style.display = 'none';
            riskWrap.style.display = 'none';
        } else if (val === 'inflation') {
            gdpWrap.style.display = 'none';
            curWrap.style.display = 'none';
            riskWrap.style.display = 'none';
        } else if (val === 'currency') {
            gdpWrap.style.display = 'none';
            infWrap.style.display = 'none';
            riskWrap.style.display = 'none';
        } else if (val === 'risk') {
            gdpWrap.style.display = 'none';
            infWrap.style.display = 'none';
            curWrap.style.display = 'none';
        }
    }

    // Refresh simulation trigger
    function triggerChartsSync() {
        const btn = document.getElementById('btn-refresh-charts');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Grafik';
            btn.disabled = false;
            
            // Randomize chart data points simulation
            if (gdpChart) {
                gdpChart.data.datasets[0].data = [24.7, 25.0, 25.2, 25.4].map(x => x + (Math.random() - 0.5) * 0.2);
                gdpChart.update();
            }
            if (inflationChart) {
                inflationChart.data.datasets[0].data = [2.8, 1.8, 3.4, 4.1].map(x => Math.max(0.5, x + (Math.random() - 0.5) * 0.5));
                inflationChart.update();
            }
            if (currencyChart) {
                currencyChart.data.datasets[0].data = [16210, 16225, 16240, 16250, 16245].map(x => x + Math.round((Math.random() - 0.5) * 50));
                currencyChart.update();
            }
            if (riskChart) {
                riskChart.data.datasets[0].data = [2.6, 2.75, 2.8].map(x => x + (Math.random() - 0.5) * 0.2);
                riskChart.update();
            }

            alert('Data visualisasi satelit makroekonomi berhasil disegarkan!');
        }, 1200);
    }

    // Filter and search logic
    function applyChartFilters() {
        const query = document.getElementById('search-chart-country').value.toLowerCase();
        const region = document.getElementById('filter-chart-region').value;
        const sortVal = document.getElementById('filter-chart-sort').value;

        const tableBody = document.getElementById('macro-table-body');
        const rows = Array.from(document.querySelectorAll('.macro-table-row'));
        
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name').toLowerCase();
            const rowRegion = row.getAttribute('data-region');

            const matchesSearch = name.includes(query);
            const matchesRegion = (region === 'all' || rowRegion === region);

            if (matchesSearch && matchesRegion) {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Sorting
        if (visibleCount > 0) {
            rows.sort((a, b) => {
                if (sortVal === 'name') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortVal === 'gdp-desc') {
                    return parseFloat(b.getAttribute('data-gdp')) - parseFloat(a.getAttribute('data-gdp'));
                } else if (sortVal === 'inflation-desc') {
                    return parseFloat(b.getAttribute('data-inflation')) - parseFloat(a.getAttribute('data-inflation'));
                } else if (sortVal === 'risk-desc') {
                    return parseFloat(b.getAttribute('data-risk')) - parseFloat(a.getAttribute('data-risk'));
                }
                return 0;
            });
            rows.forEach(row => tableBody.appendChild(row));
        }

        // Toggle Grid vs Empty States
        const grid = document.getElementById('main-content-grid');
        const emptyState = document.getElementById('empty-state-container');
        const errorState = document.getElementById('error-state-container');

        errorState.style.display = 'none';

        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyState.style.display = 'none';
        }
    }

    // Skeleton loader simulation
    function simulateSkeletonLoading() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'block';

        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
            applyChartFilters();
            
            // Re-instantiate charts on showing grid
            initializeAnalyticsCharts();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-chart-country').value = 'NegaraXyz';
        applyChartFilters();
    }

    // Error state simulation
    function simulateErrorState() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'flex';
    }

    function retryFromError() {
        simulateSkeletonLoading();
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('search-chart-country').value = '';
        document.getElementById('filter-chart-region').value = 'all';
        document.getElementById('filter-chart-year').value = '2026';
        document.getElementById('filter-chart-category').value = 'all';
        document.getElementById('filter-chart-sort').value = 'name';
        toggleChartsDisplay();
        applyChartFilters();
    }
</script>
@endsection
