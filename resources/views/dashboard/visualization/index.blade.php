@extends('layouts.app')

@section('title', 'Data Visualization Dashboard - SupplyChain Platform')

@section('styles')
<style>
    {!! file_get_contents(resource_path('views/dashboard/visualization/css/visualization.css')) !!}
</style>
@endsection

@section('content')
<!-- Page Header Area -->
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h4 class="fw-bold text-dark mb-1">Data Visualization Dashboard</h4>
        <p class="text-secondary small mb-0">Visualisasikan kondisi ekonomi, nilai tukar, inflasi, dan tingkat risiko negara secara interaktif.</p>
    </div>
    <div class="col-md-5 d-flex justify-content-md-end align-items-center gap-3 mt-3 mt-md-0 flex-wrap">
        <!-- Date Indicator -->
        <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill small d-flex align-items-center gap-1.5 shadow-sm">
            <i class="bi bi-calendar3 text-primary"></i> 18 Juli 2026
        </span>
        <!-- Refresh Button -->
        <button class="btn btn-primary btn-sm px-3 d-flex align-items-center gap-1.5" id="btn-refresh-charts" onclick="triggerChartsSync()" style="min-height: 38px;" aria-label="Segarkan data grafik">
            <i class="bi bi-arrow-clockwise"></i> Segarkan Data
        </button>
        <!-- Export Button -->
        <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm px-3 dropdown-toggle d-flex align-items-center gap-1.5" type="button" id="exportHeaderDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="min-height: 38px;">
                <i class="bi bi-download"></i> Ekspor Data
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3" aria-labelledby="exportHeaderDropdown">
                <li><button class="dropdown-item small" type="button" onclick="exportData('pdf')"><i class="bi bi-filetype-pdf text-danger me-2"></i>PDF</button></li>
                <li><button class="dropdown-item small" type="button" onclick="exportData('csv')"><i class="bi bi-filetype-csv text-success me-2"></i>CSV</button></li>
                <li><button class="dropdown-item small" type="button" onclick="exportData('xlsx')"><i class="bi bi-filetype-xls text-primary me-2"></i>Excel</button></li>
            </ul>
        </div>
    </div>
</div>

<!-- Toolbar Area (Filter & Search) -->
<x-toolbar>
    <!-- Search Country -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-12">
        <x-search-input placeholder="Cari negara..." id="search-chart-country" oninput="applyChartFilters()" />
    </div>
    
    <!-- Dropdown Region -->
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

    <!-- Dropdown Year -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-chart-year" onchange="applyChartFilters()">
            <option value="2026" selected>Tahun 2026</option>
            <option value="2025">Tahun 2025</option>
            <option value="2024">Tahun 2024</option>
        </x-filter-dropdown>
    </div>

    <!-- Dropdown Chart -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-chart-category" onchange="toggleChartsDisplay()">
            <option value="all">Semua Grafik</option>
            <option value="gdp">GDP Trend</option>
            <option value="inflation">Inflation Trend</option>
            <option value="currency">Currency Trend</option>
            <option value="risk">Risk Trend</option>
        </x-filter-dropdown>
    </div>

    <!-- Sorting Tool -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-6">
        <x-filter-dropdown id="filter-chart-sort" onchange="applyChartFilters()">
            <option value="name">Urutkan: Nama Negara</option>
            <option value="gdp-desc">Urutkan: GDP Tertinggi</option>
            <option value="inflation-desc">Urutkan: Inflasi Terbesar</option>
            <option value="risk-desc">Urutkan: Risiko Terbesar</option>
        </x-filter-dropdown>
    </div>

    <!-- Right Actions: Extra Simulation Buttons -->
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
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="resetFilters()">
            <i class="bi bi-x me-2"></i>Reset Filter
        </button>
    </x-slot>
</x-toolbar>

<!-- Skeleton Loading wrapper (using Bootstrap Placeholders) -->
<div id="skeleton-container" style="display: none;" class="my-4">
    <div class="row g-4">
        <div class="col-12 col-md-8">
            <!-- Table Skeleton -->
            <div class="card p-4 border-0 mb-4">
                <h5 class="placeholder-glow mb-3"><span class="placeholder col-6"></span></h5>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th><span class="placeholder col-8"></span></th>
                                <th><span class="placeholder col-8"></span></th>
                                <th><span class="placeholder col-8"></span></th>
                                <th><span class="placeholder col-8"></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 4; $i++)
                            <tr>
                                <td><span class="placeholder col-6"></span></td>
                                <td><span class="placeholder col-4"></span></td>
                                <td><span class="placeholder col-5"></span></td>
                                <td><span class="placeholder col-7"></span></td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <!-- Sidebar Skeleton -->
            <div class="card p-4 border-0 mb-4">
                <div class="placeholder-glow">
                    <span class="placeholder col-8 mb-3"></span>
                    <span class="placeholder col-12 mb-2"></span>
                    <span class="placeholder col-10"></span>
                </div>
            </div>
        </div>
    </div>
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
    <!-- Kolom Kiri: KPI, Charts, & Analytics Table -->
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- KPI SECTION: 6 Cards -->
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <x-kpi-card title="GDP Global" value="$3.5T" description="Meningkat" icon="bi-cash-coin" type="primary" trend="+1.8%" :trendUp="true" statusBadge="Normal" statusBadgeType="success" />
                </div>
                <div class="col-6 col-md-4">
                    <x-kpi-card title="Rerata Inflasi" value="+3.2%" description="Menurun" icon="bi-percent" type="warning" trend="-0.4%" :trendUp="true" statusBadge="Waspada" statusBadgeType="warning" />
                </div>
                <div class="col-6 col-md-4">
                    <x-kpi-card title="Kurs USD/IDR" value="Rp16.250" description="Volatilitas Rendah" icon="bi-currency-exchange" type="success" trend="+0.2%" :trendUp="true" statusBadge="Stabil" statusBadgeType="success" />
                </div>
                <div class="col-6 col-md-4">
                    <x-kpi-card title="Risk Score" value="2.80 / 5.0" description="Level Sedang" icon="bi-exclamation-triangle" type="warning" trend="+0.1" :trendUp="false" statusBadge="Sedang" statusBadgeType="warning" />
                </div>
                <div class="col-6 col-md-4">
                    <x-kpi-card title="Populasi Terpantau" value="1.45B" description="Pertumbuhan Stabil" icon="bi-people" type="primary" trend="+0.5%" :trendUp="true" statusBadge="Normal" statusBadgeType="success" />
                </div>
                <div class="col-6 col-md-4">
                    <x-kpi-card title="Last Update" value="Live" description="Terhubung World Bank" icon="bi-clock" type="success" trend="Satelit" :trendUp="true" statusBadge="Online" statusBadgeType="success" />
                </div>
            </div>

            <!-- CHART SECTION: 4 Charts -->
            <div class="row g-4" id="charts-grid-container">
                <!-- GDP Trend (Line Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-gdp">
                    <x-chart-card title="GDP Trend" subtitle="Tren PDB Regional Terpantau" :showRefresh="true" refreshId="refresh-chart-gdp" :noBorder="true">
                        <canvas id="gdpChartCanvas"></canvas>
                    </x-chart-card>
                </div>

                <!-- Inflation Trend (Bar Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-inflation">
                    <x-chart-card title="Inflation Trend" subtitle="Tingkat Inflasi Bulanan Fokus" :showRefresh="true" refreshId="refresh-chart-inflation" :noBorder="true">
                        <canvas id="inflationChartCanvas"></canvas>
                    </x-chart-card>
                </div>

                <!-- Currency Trend (Area Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-currency">
                    <x-chart-card title="Currency Trend" subtitle="Volatilitas Kurs Rupiah terhadap USD" :showRefresh="true" refreshId="refresh-chart-currency" :noBorder="true">
                        <canvas id="currencyChartCanvas"></canvas>
                    </x-chart-card>
                </div>

                <!-- Risk Trend (Line Chart) -->
                <div class="col-md-6 chart-item-wrapper" id="chart-wrap-risk">
                    <x-chart-card title="Risk Trend" subtitle="Indeks Kerawanan Logistik Global" :showRefresh="true" refreshId="refresh-chart-risk" :noBorder="true">
                        <canvas id="riskChartCanvas"></canvas>
                    </x-chart-card>
                </div>
            </div>

            <!-- ANALYTICS TABLE -->
            <div class="card p-4 border-0 shadow-sm rounded-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Matriks Perbandingan Makroekonomi</h5>
                </div>
                
                <x-analytics-table id="visualization-macro-table">
                    <thead>
                        <tr>
                            <th>Negara</th>
                            <th>GDP (Triliun USD)</th>
                            <th>Inflasi</th>
                            <th>Exchange Rate</th>
                            <th>Risk Score</th>
                            <th>Last Update</th>
                        </tr>
                    </thead>
                    <tbody id="macro-table-body">
                        <!-- Indonesia -->
                        <tr class="macro-table-row" data-name="Indonesia" data-region="asia" data-gdp="1.37" data-inflation="2.8" data-risk="1.25">
                            <td data-label="Negara" class="fw-bold text-dark">🇮🇩 Indonesia</td>
                            <td data-label="GDP">$1.37T</td>
                            <td data-label="Inflasi" class="text-success fw-bold">+2.8%</td>
                            <td data-label="Exchange Rate">Rp16.250 / USD</td>
                            <td data-label="Risk Score"><x-badge type="success" text="Low (1.25)" /></td>
                            <td data-label="Last Update">Baru Saja</td>
                        </tr>

                        <!-- China -->
                        <tr class="macro-table-row" data-name="China" data-region="asia" data-gdp="17.9" data-inflation="1.8" data-risk="4.25">
                            <td data-label="Negara" class="fw-bold text-dark">🇨🇳 China</td>
                            <td data-label="GDP">$17.90T</td>
                            <td data-label="Inflasi" class="text-success fw-bold">+1.8%</td>
                            <td data-label="Exchange Rate">¥7.24 / USD</td>
                            <td data-label="Risk Score"><span class="badge" style="background-color: #EF4444; color: white;">High (4.25)</span></td>
                            <td data-label="Last Update">5 Menit Lalu</td>
                        </tr>

                        <!-- Amerika Serikat -->
                        <tr class="macro-table-row" data-name="Amerika Serikat" data-region="america" data-gdp="25.4" data-inflation="3.4" data-risk="3.48">
                            <td data-label="Negara" class="fw-bold text-dark">🇺🇸 Amerika Serikat</td>
                            <td data-label="GDP">$25.40T</td>
                            <td data-label="Inflasi" class="text-warning fw-bold">+3.4%</td>
                            <td data-label="Exchange Rate">Base Currency (USD)</td>
                            <td data-label="Risk Score"><x-badge type="warning" text="Medium (3.48)" /></td>
                            <td data-label="Last Update">1 Jam Lalu</td>
                        </tr>

                        <!-- Belanda -->
                        <tr class="macro-table-row" data-name="Belanda" data-region="europe" data-gdp="1.01" data-inflation="4.1" data-risk="1.85">
                            <td data-label="Negara" class="fw-bold text-dark">🇳🇱 Belanda</td>
                            <td data-label="GDP">$1.01T</td>
                            <td data-label="Inflasi" class="text-danger fw-bold">+4.1%</td>
                            <td data-label="Exchange Rate">€0.92 / USD</td>
                            <td data-label="Risk Score"><x-badge type="success" text="Low (1.85)" /></td>
                            <td data-label="Last Update">2 Jam Lalu</td>
                        </tr>

                        <!-- Sudan -->
                        <tr class="macro-table-row" data-name="Sudan" data-region="africa" data-gdp="0.05" data-inflation="75.0" data-risk="4.80">
                            <td data-label="Negara" class="fw-bold text-dark">🇸🇩 Sudan</td>
                            <td data-label="GDP">$0.05T</td>
                            <td data-label="Inflasi" class="text-danger fw-bold">+75.0%</td>
                            <td data-label="Exchange Rate">SDG 601.5 / USD</td>
                            <td data-label="Risk Score"><span class="badge bg-danger text-white">High (4.80)</span></td>
                            <td data-label="Last Update">Kemarin</td>
                        </tr>
                    </tbody>
                </x-analytics-table>
            </div>

        </div>
    </div>

    <!-- Kolom Kanan: Insight Panel (Widgets List) -->
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-3">
            
            <!-- Insight 1: Top Performing Country -->
            <x-insight-card title="Top Performing Country" icon="bi-award-fill" badgeText="A+ Rank" badgeType="success">
                <div class="text-center pb-3 border-bottom mb-3">
                    <span class="fs-1 d-block mb-1">🇸🇬</span>
                    <h6 class="fw-bold text-dark mb-1">Singapura</h6>
                    <span class="text-secondary small d-block">Logistik & Distribusi Superb</span>
                </div>
                <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Efisiensi Port:</span>
                        <span class="text-success fw-bold">98.5% (Tinggi)</span>
                    </div>
                </div>
            </x-insight-card>

            <!-- Insight 2: Highest GDP -->
            <x-insight-card title="Highest GDP" icon="bi-gem" badgeText="Leader" badgeType="primary">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark fw-bold">🇺🇸 Amerika Serikat</span>
                    <span class="badge bg-primary text-white">$25.40T</span>
                </div>
            </x-insight-card>

            <!-- Insight 3: Highest Inflation -->
            <x-insight-card title="Highest Inflation" icon="bi-percent" badgeText="Critical" badgeType="danger">
                <div class="d-flex justify-content-between align-items-center text-danger">
                    <span class="fw-bold">🇸🇩 Sudan</span>
                    <span class="fw-bold fs-5">+75.0%</span>
                </div>
            </x-insight-card>

            <!-- Insight 4: Highest Risk -->
            <x-insight-card title="Highest Risk" icon="bi-shield-exclamation" badgeText="Danger Zone" badgeType="danger">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark">🇸🇩 Jalur Laut Merah Sudan</span>
                    <span class="badge bg-danger text-white">4.80 / 5.0</span>
                </div>
            </x-insight-card>

            <!-- Insight 5: Strongest Currency -->
            <x-insight-card title="Strongest Currency" icon="bi-cash" badgeText="Forex" badgeType="success">
                <div class="d-flex justify-content-between align-items-center text-success">
                    <span class="fw-bold">🇪🇺 Euro (EUR)</span>
                    <span>+0.14% (Menguat)</span>
                </div>
            </x-insight-card>

            <!-- Insight 6: Best Economic Growth -->
            <x-insight-card title="Best Economic Growth" icon="bi-arrow-up-right" badgeText="Expansion" badgeType="success">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-dark">🇮🇩 Indonesia</span>
                    <span class="text-success fw-bold">+5.05% YoY</span>
                </div>
            </x-insight-card>

            <!-- Insight 7: Worst Economic Growth -->
            <x-insight-card title="Worst Economic Growth" icon="bi-arrow-down-left" badgeText="Contraction" badgeType="danger">
                <div class="d-flex justify-content-between align-items-center text-danger">
                    <span>🇸🇩 Sudan</span>
                    <span class="fw-bold">-18.3% YoY</span>
                </div>
            </x-insight-card>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    {!! file_get_contents(resource_path('views/dashboard/visualization/js/visualization.js')) !!}
</script>
@endsection
