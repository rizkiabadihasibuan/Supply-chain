@extends('layouts.app')

@section('title', 'Global Risk Analysis Center - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Global Risk Analysis Center" subtitle="Analisis kondisi rantai pasok dunia berdasarkan berbagai indikator risiko global." :breadcrumbs="['Risk Analysis' => '#']">
    <x-slot name="actions">
        <button class="btn btn-primary" id="btn-sync-risk" onclick="triggerRiskSync()" style="min-height: 44px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Analisis
        </button>
    </x-slot>
</x-page-header>

<!-- Summary KPI Cards Row (6 Cards) -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Global Risk Score" value="2.80" description="Skor Rata-rata" icon="bi-globe" type="warning" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Negara Kritis/Tinggi" value="3" description="Butuh Atensi" icon="bi-shield-exclamation" type="danger" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Negara Sedang" value="4" description="Waspada Jalur" icon="bi-shield-slash" type="warning" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Negara Rendah" value="5" description="Kondisi Stabil" icon="bi-shield-check" type="success" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Negara Dipantau" value="12" description="Total Terintegrasi" icon="bi-check-all" type="primary" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Update Terakhir" value="Hari Ini" description="Terhubung Satelit" icon="bi-clock-history" type="info" />
    </div>
</div>

<!-- Search & Filters Toolbar Component -->
<x-search-toolbar placeholder="Cari negara..." searchId="search-risk-country" oninput="applyRiskFilters()">
    <x-slot name="filters">
        <!-- Region Filter -->
        <div class="col-xl-3 col-lg-3 col-md-4 col-6">
            <select id="filter-risk-region" class="form-select" style="min-height: 44px;" onchange="applyRiskFilters()">
                <option value="all">Semua Wilayah</option>
                <option value="asia">Asia</option>
                <option value="europe">Eropa</option>
                <option value="america">Amerika</option>
                <option value="africa">Afrika</option>
                <option value="oceania">Oceania</option>
            </select>
        </div>
        <!-- Risk level Filter -->
        <div class="col-xl-3 col-lg-3 col-md-4 col-6">
            <select id="filter-risk-level" class="form-select" style="min-height: 44px;" onchange="applyRiskFilters()">
                <option value="all">Semua Risiko</option>
                <option value="critical">Kritis (Merah)</option>
                <option value="high">Tinggi (Oranye)</option>
                <option value="medium">Sedang (Kuning)</option>
                <option value="low">Rendah (Hijau)</option>
            </select>
        </div>
        <!-- Industry Filter -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-12">
            <select id="filter-risk-industry" class="form-select" style="min-height: 44px;" onchange="applyRiskFilters()">
                <option value="all">Semua Industri</option>
                <option value="electronics">Elektronika</option>
                <option value="automotive">Otomotif</option>
                <option value="food">Pangan</option>
                <option value="energy">Energi</option>
                <option value="medical">Medis</option>
            </select>
        </div>
    </x-slot>

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
</x-search-toolbar>

<!-- Skeleton Loading Container -->
<div id="skeleton-container" style="display: none;">
    <x-loading-state type="table" count="1" height="400px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada data analisis risiko." description="Negara yang Anda cari tidak terdaftar dalam basis pemantauan stasiun mitigasi." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Sistem Analisis Risiko Sibuk." description="Gagal menghubungkan ke satelit data geopolitik World Bank dan stasiun cuaca. Silakan muat ulang." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Kolom Kiri (Peta Risiko, Charts, Tabel Kerawanan, Timeline, Mitigasi Card) -->
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- SECTION 1: Global Risk Map component -->
            <x-risk-map title="Indikator Kerentanan Koridor Distribusi Dunia">
                <x-slot name="controls">
                    <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomRiskMap(1.2)" style="width: 36px; height: 36px;">
                        <i class="bi bi-zoom-in"></i>
                    </button>
                    <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomRiskMap(0.8)" style="width: 36px; height: 36px;">
                        <i class="bi bi-zoom-out"></i>
                    </button>
                    <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="resetRiskMapZoom()" style="width: 36px; height: 36px;">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </x-slot>

                <x-slot name="mapContent">
                    <div id="risk-map-container" class="w-100 h-100 d-flex align-items-center justify-content-center" style="transition: transform 0.3s ease; transform-origin: center;">
                        <svg viewBox="0 0 1000 500" class="w-100 h-100">
                            <!-- Choropleth Colored World Path nodes representing countries -->
                            <!-- Sudan (Critical - Red #EF4444) -->
                            <path class="risk-country-node" d="M460,180 L560,160 L630,220 L580,350 L520,380 L480,260 Z" fill="#EF4444" stroke="#FFFFFF" stroke-width="1.5"
                                  data-name="Sudan" data-flag="🇸🇩" data-region="africa" data-score="4.80" data-weather="38°C / Debu" data-port="Macet" data-news="Perang Sipil" data-currency="-2.5%" data-status="critical" onclick="selectRiskCountry(this)" />
                            
                            <!-- Indonesia (Low - Green #22C55E) -->
                            <path class="risk-country-node" d="M570,220 L660,230 L630,300 L550,280 Z" fill="#22C55E" stroke="#FFFFFF" stroke-width="1.5"
                                  data-name="Indonesia" data-flag="🇮🇩" data-region="asia" data-score="1.25" data-weather="28°C / Hujan" data-port="Lancar" data-news="Kondisi Kondusif" data-currency="+0.15%" data-status="low" onclick="selectRiskCountry(this)" />

                            <!-- China (High - Orange #F97316) -->
                            <path class="risk-country-node" d="M620,80 L800,60 L850,150 L750,190 L630,150 Z" fill="#F97316" stroke="#FFFFFF" stroke-width="1.5"
                                  data-name="China" data-flag="🇨🇳" data-region="asia" data-score="4.25" data-weather="24°C / Badai" data-port="Busy" data-news="Ketegangan Dagang" data-currency="+0.08%" data-status="high" onclick="selectRiskCountry(this)" />

                            <!-- Amerika Serikat (Medium - Yellow #F59E0B) -->
                            <path class="risk-country-node" d="M100,80 L250,70 L280,160 L140,210 L100,160 Z" fill="#F59E0B" stroke="#FFFFFF" stroke-width="1.5"
                                  data-name="Amerika Serikat" data-flag="🇺🇸" data-region="america" data-score="3.48" data-weather="19°C / Cerah" data-port="Antrean" data-news="Tarif Logistik" data-currency="-0.12%" data-status="medium" onclick="selectRiskCountry(this)" />

                            <!-- Belanda (Low - Green #22C55E) -->
                            <path class="risk-country-node" d="M480,80 L540,70 L520,110 L470,100 Z" fill="#22C55E" stroke="#FFFFFF" stroke-width="1.5"
                                  data-name="Belanda" data-flag="🇳🇱" data-region="europe" data-score="1.85" data-weather="17°C / Berawan" data-port="Lancar" data-news="Bebas Hambatan" data-currency="0.00%" data-status="low" onclick="selectRiskCountry(this)" />

                            <!-- Yaman (Critical - Red #EF4444) -->
                            <circle class="risk-country-node" cx="620" cy="180" r="15" fill="#EF4444" stroke="#FFFFFF" stroke-width="1.5" class="ports-pulse"
                                    data-name="Yaman" data-flag="🇾🇪" data-region="asia" data-score="4.50" data-weather="35°C / Panas" data-port="Ditutup" data-news="Sanksi Militer" data-currency="0.00%" data-status="critical" onclick="selectRiskCountry(this)" />
                            
                            <!-- Australia (Low - Green #22C55E) -->
                            <path class="risk-country-node" d="M780,320 L880,300 L850,380 L760,360 Z" fill="#22C55E" stroke="#FFFFFF" stroke-width="1.5"
                                  data-name="Australia" data-flag="🇦🇺" data-region="oceania" data-score="1.45" data-weather="14°C / Cerah" data-port="Lancar" data-news="Lancar" data-currency="+0.05%" data-status="low" onclick="selectRiskCountry(this)" />
                        </svg>
                    </div>
                </x-slot>
            </x-risk-map>

            <!-- SECTION 2 & 3: Risk Score Distribution & Risk Trend Charts -->
            <div class="row g-4">
                <!-- Risk Score Distribution (SVG Barchart) -->
                <div class="col-md-6">
                    <x-chart-card title="Penyebaran Skor Risiko Negara" subtitle="Barchart komparasi indeks kerentanan antar wilayah terpantau.">
                        <svg viewBox="0 0 300 180" class="w-100 h-100 p-2">
                            <line x1="50" y1="20" x2="50" y2="130" stroke="#CBD5E1" stroke-width="1.5"></line>
                            <line x1="50" y1="130" x2="280" y2="130" stroke="#CBD5E1" stroke-width="1.5"></line>

                            <!-- Asia (2.6) -->
                            <text x="40" y="45" fill="#475569" font-size="9" text-anchor="end">Asia</text>
                            <rect x="50" y="35" width="130" height="15" rx="3" fill="var(--warning)"></rect>

                            <!-- Afrika (4.6) -->
                            <text x="40" y="75" fill="#475569" font-size="9" text-anchor="end">Afrika</text>
                            <rect x="50" y="65" width="210" height="15" rx="3" fill="var(--danger)"></rect>

                            <!-- Eropa (1.8) -->
                            <text x="40" y="105" fill="#475569" font-size="9" text-anchor="end">Eropa</text>
                            <rect x="50" y="95" width="80" height="15" rx="3" fill="var(--success)"></rect>
                        </svg>
                    </x-chart-card>
                </div>

                <!-- Risk Trend (SVG Line Chart) -->
                <div class="col-md-6">
                    <x-chart-card title="Tren Fluktuasi Risiko Mingguan" subtitle="Linechart volatilitas rata-rata indeks risiko global.">
                        <svg viewBox="0 0 300 180" class="w-100 h-100 p-2">
                            <defs>
                                <linearGradient id="riskTrendGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="var(--primary)" stop-opacity="0.2"></stop>
                                    <stop offset="100%" stop-color="var(--primary)" stop-opacity="0.0"></stop>
                                </linearGradient>
                            </defs>
                            <path d="M40,110 L90,130 Q140,150 190,115 T290,95 L290,150 L40,150 Z" fill="url(#riskTrendGrad)"></path>
                            <path d="M40,110 L90,130 Q140,150 190,115 T290,95" fill="none" stroke="var(--primary)" stroke-width="2"></path>
                            <circle cx="290" cy="95" r="4" fill="var(--primary)"></circle>
                            <text x="40" y="165" fill="#94A3B8" font-size="8" text-anchor="middle">Senin</text>
                            <text x="160" y="165" fill="#94A3B8" font-size="8" text-anchor="middle">Rabu</text>
                            <text x="290" y="165" fill="#94A3B8" font-size="8" text-anchor="middle">Hari Ini</text>
                        </svg>
                    </x-chart-card>
                </div>
            </div>

            <!-- SECTION 4: High Risk Countries Table -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-table text-primary me-2"></i>Matriks Komparasi Risiko Negara Rantai Pasok</h5>
                
                <x-risk-table id="risk-countries-table">
                    <thead>
                        <tr>
                            <th>Negara</th>
                            <th>Skor Risiko</th>
                            <th>Cuaca Port</th>
                            <th>Kepadatan Port</th>
                            <th>Status Berita</th>
                            <th>Volatilitas Valuta</th>
                            <th>Status Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="risk-table-body">
                        <!-- Sudan -->
                        <tr class="risk-table-row" data-name="Sudan" data-region="africa" data-status="critical">
                            <td data-label="Negara" class="fw-bold text-dark">🇸🇩 Sudan</td>
                            <td data-label="Skor Risiko" class="text-danger fw-bold">4.80 / 5.0</td>
                            <td data-label="Cuaca Port">💨 38°C</td>
                            <td data-label="Kepadatan Port"><x-badge type="danger" text="Macet" /></td>
                            <td data-label="Status Berita">Krisis Sipil</td>
                            <td data-label="Volatilitas Valuta" class="text-danger">-2.5% (Melemah)</td>
                            <td data-label="Status Akhir"><x-badge type="danger" text="Critical" /></td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickRiskCountry('Sudan')">Pilih</button></td>
                        </tr>

                        <!-- China -->
                        <tr class="risk-table-row" data-name="China" data-region="asia" data-status="high">
                            <td data-label="Negara" class="fw-bold text-dark">🇨🇳 China</td>
                            <td data-label="Skor Risiko" class="text-warning fw-bold" style="color: #F97316 !important;">4.25 / 5.0</td>
                            <td data-label="Cuaca Port">⛈️ 24°C</td>
                            <td data-label="Kepadatan Port"><x-badge type="warning" text="Busy" /></td>
                            <td data-label="Status Berita">Perang Dagang</td>
                            <td data-label="Volatilitas Valuta" class="text-success">+0.08% (Menguat)</td>
                            <td data-label="Status Akhir"><span class="badge" style="background-color: #F97316; color: white;">High</span></td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickRiskCountry('China')">Pilih</button></td>
                        </tr>

                        <!-- Amerika Serikat -->
                        <tr class="risk-table-row" data-name="Amerika Serikat" data-region="america" data-status="medium">
                            <td data-label="Negara" class="fw-bold text-dark">🇺🇸 Amerika Serikat</td>
                            <td data-label="Skor Risiko" class="text-warning fw-bold">3.48 / 5.0</td>
                            <td data-label="Cuaca Port">☀️ 19°C</td>
                            <td data-label="Kepadatan Port"><x-badge type="warning" text="Antrean" /></td>
                            <td data-label="Status Berita">Regulasi Tarif</td>
                            <td data-label="Volatilitas Valuta" class="text-danger">-0.12% (Melemah)</td>
                            <td data-label="Status Akhir"><x-badge type="warning" text="Medium" /></td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickRiskCountry('Amerika Serikat')">Pilih</button></td>
                        </tr>

                        <!-- Indonesia -->
                        <tr class="risk-table-row" data-name="Indonesia" data-region="asia" data-status="low">
                            <td data-label="Negara" class="fw-bold text-dark">🇮🇩 Indonesia</td>
                            <td data-label="Skor Risiko" class="text-success fw-bold">1.25 / 5.0</td>
                            <td data-label="Cuaca Port">🌧️ 28°C</td>
                            <td data-label="Kepadatan Port"><x-badge type="success" text="Lancar" /></td>
                            <td data-label="Status Berita">Kondusif</td>
                            <td data-label="Volatilitas Valuta" class="text-success">+0.15% (Menguat)</td>
                            <td data-label="Status Akhir"><x-badge type="success" text="Low" /></td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickRiskCountry('Indonesia')">Pilih</button></td>
                        </tr>
                    </tbody>
                </x-risk-table>
            </div>

            <!-- SECTION 5: Recent Risk Events (Timeline) -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Riwayat Peristiwa Risiko Logistik Terbaru</h5>
                
                <x-timeline id="risk-events-timeline">
                    <div class="position-relative mb-3.5">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--danger); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Pemberitahuan Badai Kritis Shanghai</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Kecepatan angin 45 knot di pelabuhan memicu kenaikan skor risiko maritim China menjadi 4.25.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>30 menit yang lalu</span>
                        </div>
                    </div>
                    <div class="position-relative">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--warning); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Kenaikan Dwelling Time Los Angeles</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Antrean kontainer truk memicu kenaikan risiko logistik maritim AS ke status Medium (3.48).</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>2 jam yang lalu</span>
                        </div>
                    </div>
                </x-timeline>
            </div>

            <!-- SECTION 6: Risk Recommendation Card Component -->
            <x-risk-card title="Rekomendasi Mitigasi & Diversifikasi Rute Logistik" type="warning">
                Sebagai langkah antisipasi fluktuasi skor risiko, operator disarankan untuk segera mendiversifikasi pelabuhan tujuan kargo dari Port of Shanghai menuju Port of Singapore guna menghindari demurrage kontainer. Serta melakukan lindung nilai (hedging) valuta asing untuk transaksi suku cadang dalam USD demi meredam kerugian pelemahan kurs lokal.
            </x-risk-card>

        </div>
    </div>

    <!-- Kolom Kanan (Widgets) -->
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-4">
            
            <!-- WIDGET 1: Risk Summary detail -->
            <x-risk-widget title="Profil Kerentanan Negara" icon="bi-info-circle-fill">
                <div class="text-center pb-3 border-bottom mb-3">
                    <span id="risk-detail-flag" class="fs-1 d-block mb-1">🇮🇩</span>
                    <h5 id="risk-detail-name" class="fw-bold text-dark mb-1">Indonesia</h5>
                    <span id="risk-detail-region" class="text-secondary small d-block">Asia Tenggara</span>
                </div>

                <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Skor Kerentanan:</span>
                        <span id="risk-detail-score" class="text-success fw-bold">1.25 / 5.0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Status Operasional:</span>
                        <span id="risk-detail-port" class="text-dark fw-semibold">Lancar (Normal)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Dampak Cuaca:</span>
                        <span id="risk-detail-weather" class="text-dark fw-semibold">Hujan (28°C)</span>
                    </div>
                </div>
            </x-risk-widget>

            <!-- WIDGET 2: Top Risk Factors -->
            <x-risk-widget title="Faktor Pemicu Risiko Global" icon="bi-bar-chart-fill">
                <div class="d-flex flex-column gap-3.5">
                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.8rem;">
                            <span class="text-secondary fw-medium">Risiko Cuaca (Pelabuhan)</span>
                            <span class="text-dark fw-bold">45% (Sedang)</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                            <div class="progress-bar bg-warning" style="width: 45%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.8rem;">
                            <span class="text-secondary fw-medium">Kepadatan Kontainer Port</span>
                            <span class="text-dark fw-bold">78% (Tinggi)</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                            <div class="progress-bar bg-warning" style="width: 78%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.8rem;">
                            <span class="text-secondary fw-medium">Ketegangan Geopolitik</span>
                            <span class="text-dark fw-bold">90% (Kritis)</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                            <div class="progress-bar bg-danger" style="width: 90%;"></div>
                        </div>
                    </div>
                </div>
            </x-risk-widget>

            <!-- WIDGET 3: Risk Level Legend -->
            <x-risk-widget title="Legenda Klasifikasi Indeks" icon="bi-bookmark-star-fill">
                <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                    <div class="d-flex justify-content-between"><span>Risiko Kritis</span><x-badge type="danger" text="Critical" /></div>
                    <div class="d-flex justify-content-between"><span>Risiko Tinggi</span><span class="badge" style="background-color: #F97316; color: white;">High</span></div>
                    <div class="d-flex justify-content-between"><span>Risiko Sedang</span><x-badge type="warning" text="Medium" /></div>
                    <div class="d-flex justify-content-between"><span>Risiko Rendah</span><x-badge type="success" text="Low" /></div>
                </div>
            </x-risk-widget>

            <!-- WIDGET 4: Top Affected Supply Chains -->
            <x-risk-widget title="Sektor Rantai Pasok Terimbas" icon="bi-box-seam-fill">
                <div class="d-flex flex-wrap gap-2">
                    <x-badge type="danger" text="Elektronika" />
                    <x-badge type="danger" text="Otomotif" />
                    <x-badge type="warning" text="Energi" />
                    <x-badge type="success" text="Pangan" />
                    <x-badge type="success" text="Medis" />
                </div>
            </x-widget-card>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Run simulated loader
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    });

    // Zoom and pan map
    let riskMapZoomScale = 1;
    function zoomRiskMap(factor) {
        riskMapZoomScale *= factor;
        if (riskMapZoomScale < 0.6) riskMapZoomScale = 0.6;
        if (riskMapZoomScale > 3.0) riskMapZoomScale = 3.0;
        document.getElementById('risk-map-container').style.transform = `scale(${riskMapZoomScale})`;
    }

    function resetRiskMapZoom() {
        riskMapZoomScale = 1;
        document.getElementById('risk-map-container').style.transform = `scale(1)`;
    }

    // Country select node update widget details
    function selectRiskCountry(node) {
        const name = node.getAttribute('data-name');
        const flag = node.getAttribute('data-flag');
        const region = node.getAttribute('data-region');
        const score = node.getAttribute('data-score');
        const weather = node.getAttribute('data-weather');
        const port = node.getAttribute('data-port');
        const status = node.getAttribute('data-status');

        // Update widget detail
        document.getElementById('risk-detail-flag').textContent = flag;
        document.getElementById('risk-detail-name').textContent = name;
        document.getElementById('risk-detail-region').textContent = region;
        
        const scoreField = document.getElementById('risk-detail-score');
        scoreField.textContent = `${score} / 5.0`;

        if (status === 'critical') {
            scoreField.className = 'text-danger fw-bold';
        } else if (status === 'high') {
            scoreField.className = 'fw-bold';
            scoreField.style.color = '#F97316';
        } else if (status === 'medium') {
            scoreField.className = 'text-warning fw-bold';
        } else {
            scoreField.className = 'text-success fw-bold';
        }

        document.getElementById('risk-detail-port').textContent = port;
        document.getElementById('risk-detail-weather').textContent = weather;
    }

    // Proactive select via table click
    function pickRiskCountry(countryName) {
        const nodes = Array.from(document.querySelectorAll('.risk-country-node'));
        const matched = nodes.find(n => n.getAttribute('data-name') === countryName);
        if (matched) {
            selectRiskCountry(matched);
            alert(`Negara ${countryName} dipilih. Periksa tingkat mitigasi risiko di sebelah kanan.`);
        }
    }

    // Search and filter logic
    function applyRiskFilters() {
        const query = document.getElementById('search-risk-country').value.toLowerCase();
        const region = document.getElementById('filter-risk-region').value;
        const level = document.getElementById('filter-risk-level').value;

        const tableBody = document.getElementById('risk-table-body');
        const rows = Array.from(document.querySelectorAll('.risk-table-row'));
        const nodes = Array.from(document.querySelectorAll('.risk-country-node'));

        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name').toLowerCase();
            const rowRegion = row.getAttribute('data-region');
            const rowStatus = row.getAttribute('data-status');

            const matchesSearch = name.includes(query);
            const matchesRegion = (region === 'all' || rowRegion === region);
            const matchesStatus = (level === 'all' || rowStatus === level);

            if (matchesSearch && matchesRegion && matchesStatus) {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Sync map nodes visibility
        nodes.forEach(node => {
            const name = node.getAttribute('data-name').toLowerCase();
            const nodeRegion = node.getAttribute('data-region');
            const nodeStatus = node.getAttribute('data-status');

            const matchesSearch = name.includes(query);
            const matchesRegion = (region === 'all' || nodeRegion === region);
            const matchesStatus = (level === 'all' || nodeStatus === level);

            if (matchesSearch && matchesRegion && matchesStatus) {
                node.style.opacity = '1';
                node.style.pointerEvents = 'auto';
            } else {
                node.style.opacity = '0.15';
                node.style.pointerEvents = 'none';
            }
        });

        // Toggle Grid or Empty State
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

    // Refresh simulation trigger
    function triggerRiskSync() {
        const btn = document.getElementById('btn-sync-risk');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Menghitung...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Analisis';
            btn.disabled = false;
            alert('Perhitungan ulang indeks kerawanan mitigasi berhasil disinkronisasikan!');
        }, 1200);
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
            applyRiskFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-risk-country').value = 'NegaraXyz';
        applyRiskFilters();
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
        document.getElementById('search-risk-country').value = '';
        document.getElementById('filter-risk-region').value = 'all';
        document.getElementById('filter-risk-level').value = 'all';
        applyRiskFilters();
    }
</script>

<style>
    /* Pulses for critical high risk ports */
    .ports-pulse {
        animation: ports-pulse-ring 1.8s infinite;
    }

    @keyframes ports-pulse-ring {
        0% {
            stroke-width: 1.5;
            stroke: rgba(239, 68, 68, 0.4);
        }
        50% {
            stroke-width: 3.5;
            stroke: rgba(239, 68, 68, 0.8);
        }
        100% {
            stroke-width: 1.5;
            stroke: rgba(239, 68, 68, 0.4);
        }
    }

    .risk-country-node {
        cursor: pointer;
        transition: fill-opacity 0.2s ease, stroke-width 0.2s ease;
    }

    .risk-country-node:hover {
        fill-opacity: 0.85;
        stroke: #123458;
        stroke-width: 2.5;
    }
</style>
@endsection
