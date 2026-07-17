@extends('layouts.app')

@section('title', 'Global Ports Center - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Global Ports Center" subtitle="Pantau aktivitas pelabuhan utama dunia untuk mendukung analisis risiko rantai pasok global." :breadcrumbs="['Global Ports Center' => '#']">
    <x-slot name="actions">
        <button class="btn btn-primary" id="btn-sync-ports" onclick="triggerPortsSync()" style="min-height: 44px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Pelabuhan
        </button>
    </x-slot>
</x-page-header>

<!-- Summary KPI Cards Row (4 Cards) -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Total Pelabuhan Dipantau" value="8" description="Pelabuhan Hub Utama Global" icon="bi-anchor" type="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Pelabuhan Aktif" value="7" description="Operasional Normal & Padat" icon="bi-check-circle" type="success" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Pelabuhan Risiko Tinggi" value="1" description="Butuh Jalur Alternatif" icon="bi-exclamation-octagon" type="danger" id="kpi-risk-count" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Keterlambatan Pengiriman" value="+4.8 Jam" description="Rata-rata Waktu Tunggu" icon="bi-clock-history" type="warning" />
    </div>
</div>

<!-- Search & Filters Toolbar Component -->
<x-search-toolbar placeholder="Cari pelabuhan berdasarkan nama atau negara..." searchId="search-port-input" oninput="applyPortFilters()">
    <x-slot name="filters">
        <!-- Region Filter -->
        <div class="col-xl-3 col-lg-3 col-md-4 col-6">
            <select id="filter-port-region" class="form-select" style="min-height: 44px;" onchange="applyPortFilters()">
                <option value="all">Semua Benua</option>
                <option value="asia">Asia</option>
                <option value="europe">Eropa</option>
                <option value="america">Amerika</option>
                <option value="africa">Afrika</option>
                <option value="oceania">Oceania</option>
            </select>
        </div>
        <!-- Status Filter -->
        <div class="col-xl-3 col-lg-3 col-md-4 col-6">
            <select id="filter-port-status" class="form-select" style="min-height: 44px;" onchange="applyPortFilters()">
                <option value="all">Semua Status</option>
                <option value="normal">Normal</option>
                <option value="busy">Busy (Padat)</option>
                <option value="critical">Critical (Kritis)</option>
            </select>
        </div>
        <!-- Sort Filter -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-12">
            <select id="sort-port-select" class="form-select" style="min-height: 44px;" onchange="applyPortFilters()">
                <option value="name">Nama Pelabuhan</option>
                <option value="capacity-desc">Kapasitas Terbesar</option>
                <option value="congestion-desc">Kepadatan Tertinggi</option>
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

<!-- Skeleton Loading wrapper -->
<div id="skeleton-container" style="display: none;">
    <x-loading-state type="table" count="1" height="380px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada data pelabuhan." description="Nama pelabuhan tidak ditemukan dalam radar pengawasan kami." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Koneksi Maritim Terputus." description="Gagal menghubungkan ke World Port Index stasiun data. Silakan coba lagi." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Kolom Kiri (Peta, Tabel, Congestion Barchart) -->
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- SECTION 1: World Port Map -->
            <div class="card p-4 border-0">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Peta Koridor Pelayaran Global</h5>
                        <p class="text-secondary small mb-0">Klik titik berkedip untuk memilih detail aktivitas pelabuhan.</p>
                    </div>
                    <!-- Zoom & Layers -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomPortMap(1.2)" style="width: 36px; height: 36px;">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomPortMap(0.8)" style="width: 36px; height: 36px;">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="resetPortMapZoom()" style="width: 36px; height: 36px;">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>

                <!-- SVG Peta -->
                <div class="position-relative border rounded-4 overflow-hidden d-flex align-items-center justify-content-center" style="height: 360px; background-color: #FAFCFF !important;">
                    <div id="port-map-container" class="w-100 h-100 d-flex align-items-center justify-content-center" style="transition: transform 0.3s ease; transform-origin: center;">
                        <svg viewBox="0 0 1000 500" class="w-100 h-100">
                            <!-- Background World Shapes -->
                            <g fill="#E2E8F0" stroke="#FFFFFF" stroke-width="1.5">
                                <path d="M100,80 L200,60 L280,100 L250,180 L200,200 L150,150 Z" />
                                <path d="M250,220 L320,250 L280,380 L240,420 L220,300 Z" />
                                <path d="M300,30 L380,20 L350,70 L280,60 Z" />
                                <path d="M450,60 L600,40 L850,50 L900,120 L800,280 L700,250 L600,280 L520,220 L420,120 Z" />
                                <path d="M460,180 L560,160 L630,220 L580,350 L520,380 L480,260 Z" />
                                <path d="M780,320 L880,300 L850,380 L760,360 Z" />
                            </g>

                            <!-- Port nodes map -->
                            <g id="port-markers">
                                <!-- Tanjung Priok -->
                                <g class="port-marker-node" data-name="Tanjung Priok" data-country="Indonesia" data-region="asia" data-status="normal" data-capacity="8.5" data-congestion="65" data-weather="Hujan Ringan (28°C)" data-risk="Rendah" data-delay="0" onclick="selectPortNode(this)">
                                    <circle cx="570" cy="220" r="14" fill="rgba(34, 197, 94, 0.15)" stroke="var(--success)" stroke-width="1" />
                                    <circle cx="570" cy="220" r="5" fill="var(--success)" />
                                </g>
                                <!-- Singapore -->
                                <g class="port-marker-node" data-name="Port of Singapore" data-country="Singapura" data-region="asia" data-status="busy" data-capacity="37.2" data-congestion="82" data-weather="Cerah Berawan (31°C)" data-risk="Rendah" data-delay="1.5" onclick="selectPortNode(this)">
                                    <circle cx="650" cy="230" r="14" fill="rgba(245, 158, 11, 0.15)" stroke="var(--warning)" stroke-width="1" />
                                    <circle cx="650" cy="230" r="5" fill="var(--warning)" />
                                </g>
                                <!-- Shanghai -->
                                <g class="port-marker-node" data-name="Port of Shanghai" data-country="China" data-region="asia" data-status="critical" data-capacity="47.3" data-congestion="95" data-weather="Badai Topan (24°C)" data-risk="Tinggi" data-delay="12.0" onclick="selectPortNode(this)">
                                    <circle cx="780" cy="120" r="18" fill="rgba(239, 68, 68, 0.2)" stroke="var(--danger)" stroke-width="1.5" class="ports-pulse" />
                                    <circle cx="780" cy="120" r="6" fill="var(--danger)" />
                                </g>
                                <!-- Rotterdam -->
                                <g class="port-marker-node" data-name="Port of Rotterdam" data-country="Belanda" data-region="europe" data-status="normal" data-capacity="14.5" data-congestion="50" data-weather="Berawan (17°C)" data-risk="Rendah" data-delay="0.5" onclick="selectPortNode(this)">
                                    <circle cx="520" cy="130" r="14" fill="rgba(34, 197, 94, 0.15)" stroke="var(--success)" stroke-width="1" />
                                    <circle cx="520" cy="130" r="5" fill="var(--success)" />
                                </g>
                                <!-- Los Angeles -->
                                <g class="port-marker-node" data-name="Port of Los Angeles" data-country="Amerika Serikat" data-region="america" data-status="busy" data-capacity="10.6" data-congestion="78" data-weather="Cerah (21°C)" data-risk="Sedang" data-delay="3.0" onclick="selectPortNode(this)">
                                    <circle cx="150" cy="110" r="14" fill="rgba(245, 158, 11, 0.15)" stroke="var(--warning)" stroke-width="1" />
                                    <circle cx="150" cy="110" r="5" fill="var(--warning)" />
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Ports Table -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-table text-primary me-2"></i>Daftar Aktivitas Operasional Pelabuhan</h5>
                
                <x-table id="ports-main-table">
                    <thead>
                        <tr>
                            <th>Nama Pelabuhan</th>
                            <th>Negara</th>
                            <th>Status</th>
                            <th>Kapasitas (TEUs)</th>
                            <th>Kepadatan</th>
                            <th>Cuaca</th>
                            <th>Tingkat Risiko</th>
                            <th>Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="ports-table-body">
                        <!-- Tanjung Priok -->
                        <tr class="port-table-row" data-name="Tanjung Priok" data-country="Indonesia" data-region="asia" data-status="normal" data-capacity="8.5" data-congestion="65" data-weather="Hujan (28°C)" data-risk="low" data-delay="0">
                            <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Tanjung Priok</td>
                            <td data-label="Negara">Indonesia</td>
                            <td data-label="Status"><x-badge type="success" text="Normal" /></td>
                            <td data-label="Kapasitas">8.5 Juta TEUs</td>
                            <td data-label="Kepadatan">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-semibold">65%</span>
                                    <div class="progress flex-grow-1" style="height: 5px; width: 60px; background-color: #E2E8F0;">
                                        <div class="progress-bar bg-success" style="width: 65%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cuaca">🌧️ 28°C</td>
                            <td data-label="Tingkat Risiko"><x-badge type="success" text="Rendah" /></td>
                            <td data-label="Update">Baru Saja</td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickPort('Tanjung Priok')">Pilih</button></td>
                        </tr>

                        <!-- Singapore -->
                        <tr class="port-table-row" data-name="Port of Singapore" data-country="Singapura" data-region="asia" data-status="busy" data-capacity="37.2" data-congestion="82" data-weather="Cerah (31°C)" data-risk="low" data-delay="1.5">
                            <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Port of Singapore</td>
                            <td data-label="Negara">Singapura</td>
                            <td data-label="Status"><x-badge type="warning" text="Busy" /></td>
                            <td data-label="Kapasitas">37.2 Juta TEUs</td>
                            <td data-label="Kepadatan">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-semibold">82%</span>
                                    <div class="progress flex-grow-1" style="height: 5px; width: 60px; background-color: #E2E8F0;">
                                        <div class="progress-bar bg-warning" style="width: 82%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cuaca">☀️ 31°C</td>
                            <td data-label="Tingkat Risiko"><x-badge type="success" text="Rendah" /></td>
                            <td data-label="Update">5 Menit Lalu</td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickPort('Port of Singapore')">Pilih</button></td>
                        </tr>

                        <!-- Shanghai -->
                        <tr class="port-table-row" data-name="Port of Shanghai" data-country="China" data-region="asia" data-status="critical" data-capacity="47.3" data-congestion="95" data-weather="Badai (24°C)" data-risk="high" data-delay="12.0">
                            <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Port of Shanghai</td>
                            <td data-label="Negara">China</td>
                            <td data-label="Status"><x-badge type="danger" text="Critical" /></td>
                            <td data-label="Kapasitas">47.3 Juta TEUs</td>
                            <td data-label="Kepadatan">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-semibold">95%</span>
                                    <div class="progress flex-grow-1" style="height: 5px; width: 60px; background-color: #E2E8F0;">
                                        <div class="progress-bar bg-danger" style="width: 95%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cuaca">⛈️ 24°C</td>
                            <td data-label="Tingkat Risiko"><x-badge type="danger" text="Tinggi" /></td>
                            <td data-label="Update">10 Menit Lalu</td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickPort('Port of Shanghai')">Pilih</button></td>
                        </tr>

                        <!-- Rotterdam -->
                        <tr class="port-table-row" data-name="Port of Rotterdam" data-country="Belanda" data-region="europe" data-status="normal" data-capacity="14.5" data-congestion="50" data-weather="Berawan (17°C)" data-risk="low" data-delay="0.5">
                            <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Port of Rotterdam</td>
                            <td data-label="Negara">Belanda</td>
                            <td data-label="Status"><x-badge type="success" text="Normal" /></td>
                            <td data-label="Kapasitas">14.5 Juta TEUs</td>
                            <td data-label="Kepadatan">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-semibold">50%</span>
                                    <div class="progress flex-grow-1" style="height: 5px; width: 60px; background-color: #E2E8F0;">
                                        <div class="progress-bar bg-success" style="width: 50%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cuaca">⛅ 17°C</td>
                            <td data-label="Tingkat Risiko"><x-badge type="success" text="Rendah" /></td>
                            <td data-label="Update">15 Menit Lalu</td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickPort('Port of Rotterdam')">Pilih</button></td>
                        </tr>

                        <!-- Los Angeles -->
                        <tr class="port-table-row" data-name="Port of Los Angeles" data-country="Amerika Serikat" data-region="america" data-status="busy" data-capacity="10.6" data-congestion="78" data-weather="Cerah (21°C)" data-risk="medium" data-delay="3.0">
                            <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Port of Los Angeles</td>
                            <td data-label="Negara">Amerika Serikat</td>
                            <td data-label="Status"><x-badge type="warning" text="Busy" /></td>
                            <td data-label="Kapasitas">10.6 Juta TEUs</td>
                            <td data-label="Kepadatan">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small fw-semibold">78%</span>
                                    <div class="progress flex-grow-1" style="height: 5px; width: 60px; background-color: #E2E8F0;">
                                        <div class="progress-bar bg-warning" style="width: 78%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cuaca">☀️ 21°C</td>
                            <td data-label="Tingkat Risiko"><x-badge type="warning" text="Sedang" /></td>
                            <td data-label="Update">30 Menit Lalu</td>
                            <td data-label="Aksi"><button class="btn btn-light btn-sm border" style="min-height: 38px;" onclick="pickPort('Port of Los Angeles')">Pilih</button></td>
                        </tr>
                    </tbody>
                </x-table>
            </div>

            <!-- SECTION 3: Top Busy Ports -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-award text-primary me-2"></i>Top 5 Pelabuhan Hub Terpadat Dunia</h5>
                
                <div class="row g-3">
                    <div class="col-md-6 col-xl-4">
                        <div class="p-3 border rounded-4 bg-light" style="background-color: #FAFCFF !important;">
                            <span class="badge bg-secondary mb-1">Peringkat 1</span>
                            <h6 class="fw-bold text-dark mb-1">Port of Shanghai (CN)</h6>
                            <span class="text-secondary small">Kapasitas: 47.3 Juta TEUs / Thn</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="p-3 border rounded-4 bg-light" style="background-color: #FAFCFF !important;">
                            <span class="badge bg-secondary mb-1">Peringkat 2</span>
                            <h6 class="fw-bold text-dark mb-1">Port of Singapore (SG)</h6>
                            <span class="text-secondary small">Kapasitas: 37.2 Juta TEUs / Thn</span>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4">
                        <div class="p-3 border rounded-4 bg-light" style="background-color: #FAFCFF !important;">
                            <span class="badge bg-secondary mb-1">Peringkat 3</span>
                            <h6 class="fw-bold text-dark mb-1">Port of Rotterdam (NL)</h6>
                            <span class="text-secondary small">Kapasitas: 14.5 Juta TEUs / Thn</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Port Congestion (SVG Bar Chart) -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-bar-chart-line text-primary me-2"></i>Komparasi Kepadatan Pelabuhan</h5>
                <p class="text-secondary small mb-4">Grafik tingkat kepadatan penumpukan kontainer gudang dermaga utama saat ini.</p>
                
                <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center" style="height: 200px; background-color: #FAFCFF !important;">
                    <svg viewBox="0 0 600 180" class="w-100 h-100 p-2">
                        <!-- Bar chart grid -->
                        <line x1="120" y1="20" x2="120" y2="140" stroke="#CBD5E1" stroke-width="1.5"></line>
                        <line x1="220" y1="140" x2="560" y2="140" stroke="#E2E8F0" stroke-width="1" stroke-dasharray="4"></line>
                        
                        <!-- Bar 1: Shanghai (95%) -->
                        <text x="110" y="45" fill="#475569" font-size="10" text-anchor="end" font-weight="bold">Shanghai</text>
                        <rect x="120" y="35" width="380" height="15" rx="3" fill="var(--danger)"></rect>
                        <text x="510" y="47" fill="#475569" font-size="10" font-weight="bold">95%</text>

                        <!-- Bar 2: Singapore (82%) -->
                        <text x="110" y="75" fill="#475569" font-size="10" text-anchor="end" font-weight="bold">Singapore</text>
                        <rect x="120" y="65" width="320" height="15" rx="3" fill="var(--warning)"></rect>
                        <text x="450" y="77" fill="#475569" font-size="10" font-weight="bold">82%</text>

                        <!-- Bar 3: Los Angeles (78%) -->
                        <text x="110" y="105" fill="#475569" font-size="10" text-anchor="end" font-weight="bold">Los Angeles</text>
                        <rect x="120" y="95" width="300" height="15" rx="3" fill="var(--warning)"></rect>
                        <text x="430" y="107" fill="#475569" font-size="10" font-weight="bold">78%</text>

                        <!-- Bar 4: Tanjung Priok (65%) -->
                        <text x="110" y="135" fill="#475569" font-size="10" text-anchor="end" font-weight="bold">Tanjung Priok</text>
                        <rect x="120" y="125" width="260" height="15" rx="3" fill="var(--success)"></rect>
                        <text x="390" y="137" fill="#475569" font-size="10" font-weight="bold">65%</text>
                    </svg>
                </div>
            </div>

        </div>
    </div>

    <!-- Kolom Kanan (Widgets) -->
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-4">
            
            <!-- WIDGET 1: Port Summary Detail -->
            <x-widget-card title="Stasiun Pelabuhan Terpilih" icon="bi-info-circle-fill">
                <div class="text-center pb-3 border-bottom mb-3">
                    <span id="port-detail-flag" class="fs-1 d-block mb-1">🇮🇩</span>
                    <h5 id="port-detail-name" class="fw-bold text-dark mb-1">Tanjung Priok</h5>
                    <span id="port-detail-country" class="text-secondary small d-block">Jakarta, Indonesia</span>
                </div>

                <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Kapasitas Tahunan:</span>
                        <span id="port-detail-capacity" class="text-dark fw-bold">8.5 Juta TEUs</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Kepadatan Kontainer:</span>
                        <span id="port-detail-congestion" class="text-dark fw-semibold">65%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Kondisi Cuaca Port:</span>
                        <span id="port-detail-weather" class="text-dark fw-semibold">Hujan (28°C)</span>
                    </div>
                </div>
            </x-widget-card>

            <!-- WIDGET 2: Shipping Delay / Status -->
            <x-widget-card title="Status Pengiriman Maritim" icon="bi-shield-exclamation">
                <p class="text-secondary small mb-3">Estimasi keterlambatan bersandar akibat antrean dermaga penumpukan kontainer.</p>
                
                <div class="p-3 border rounded-4 text-center mb-3" id="port-shipping-box" style="background-color: rgba(34, 197, 94, 0.06); border-color: rgba(34, 197, 94, 0.15) !important;">
                    <span class="text-secondary small d-block">Penundaan Sandar Kapal</span>
                    <h3 class="fw-bold text-success mb-1" id="port-shipping-delay">0 Jam</h3>
                    <span class="badge badge-success" id="port-shipping-badge">Lancar (Normal)</span>
                </div>
            </x-widget-card>

            <!-- WIDGET 3: Top Risk Ports List -->
            <x-widget-card title="Peta Kerawanan Pelabuhan" icon="bi-exclamation-octagon-fill">
                <div class="d-flex flex-column gap-3" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <div>
                            <span class="fw-bold text-dark d-block">Port of Shanghai (CN)</span>
                            <span class="text-secondary small">Kepadatan Kritis (95%)</span>
                        </div>
                        <x-badge type="danger" text="Tinggi" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-dark d-block">Port of Los Angeles (US)</span>
                            <span class="text-secondary small">Kepadatan Tinggi (78%)</span>
                        </div>
                        <x-badge type="warning" text="Sedang" />
                    </div>
                </div>
            </x-widget-card>

            <!-- WIDGET 4: Recent Activity timeline -->
            <x-widget-card title="Log Aktivitas Maritim" icon="bi-clock-history">
                <div style="position: relative; padding-left: 20px;">
                    <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
                    
                    <div class="position-relative mb-3.5">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--danger); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Vessel Delay Shanghai</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Waktu sandar tertunda 12 jam akibat badai topan maritim.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>30 menit yang lalu</span>
                        </div>
                    </div>

                    <div class="position-relative">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--success); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Priok Gate Digitalization</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Antrean kontainer Merak-Tanjung Priok kembali berjalan normal.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>3 jam yang lalu</span>
                        </div>
                    </div>
                </div>
            </x-widget-card>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simulated loading on page startup
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    });

    // Zoom and Pan Controls
    let portMapZoomScale = 1;
    function zoomPortMap(factor) {
        portMapZoomScale *= factor;
        if (portMapZoomScale < 0.6) portMapZoomScale = 0.6;
        if (portMapZoomScale > 3.0) portMapZoomScale = 3.0;
        document.getElementById('port-map-container').style.transform = `scale(${portMapZoomScale})`;
    }

    function resetPortMapZoom() {
        portMapZoomScale = 1;
        document.getElementById('port-map-container').style.transform = `scale(1)`;
    }

    // Node Selection updates Sidebar Widgets
    function selectPortNode(node) {
        const name = node.getAttribute('data-name');
        const country = node.getAttribute('data-country');
        const capacity = node.getAttribute('data-capacity');
        const congestion = node.getAttribute('data-congestion');
        const weather = node.getAttribute('data-weather');
        const risk = node.getAttribute('data-risk');
        const status = node.getAttribute('data-status');
        const delay = parseFloat(node.getAttribute('data-delay'));

        // Flag matcher
        let flag = '🌍';
        if (country.includes('Indonesia')) flag = '🇮🇩';
        if (country.includes('Singapura')) flag = '🇸🇬';
        if (country.includes('China')) flag = '🇨🇳';
        if (country.includes('Belanda')) flag = '🇳🇱';
        if (country.includes('Amerika')) flag = '🇺🇸';

        // Update widget detail
        document.getElementById('port-detail-flag').textContent = flag;
        document.getElementById('port-detail-name').textContent = name;
        document.getElementById('port-detail-country').textContent = `${country}`;
        document.getElementById('port-detail-capacity').textContent = `${capacity} Juta TEUs`;
        document.getElementById('port-detail-congestion').textContent = `${congestion}%`;
        document.getElementById('port-detail-weather').textContent = weather;

        // Update shipping delay widget
        document.getElementById('port-shipping-delay').textContent = delay > 0 ? `+${delay} Jam` : '0 Jam';
        
        const delayBox = document.getElementById('port-shipping-box');
        const delayBadge = document.getElementById('port-shipping-badge');

        if (status === 'critical') {
            delayBox.style.backgroundColor = 'rgba(239, 68, 68, 0.06)';
            delayBox.style.borderColor = 'rgba(239, 68, 68, 0.15)';
            document.getElementById('port-shipping-delay').className = 'fw-bold text-danger mb-1';
            delayBadge.className = 'badge badge-danger';
            delayBadge.textContent = 'Kritis (Macet)';
        } else if (status === 'busy') {
            delayBox.style.backgroundColor = 'rgba(245, 158, 11, 0.06)';
            delayBox.style.borderColor = 'rgba(245, 158, 11, 0.15)';
            document.getElementById('port-shipping-delay').className = 'fw-bold text-warning mb-1';
            delayBadge.className = 'badge badge-warning';
            delayBadge.textContent = 'Padat (Antrean)';
        } else {
            delayBox.style.backgroundColor = 'rgba(34, 197, 94, 0.06)';
            delayBox.style.borderColor = 'rgba(34, 197, 94, 0.15)';
            document.getElementById('port-shipping-delay').className = 'fw-bold text-success mb-1';
            delayBadge.className = 'badge badge-success';
            delayBadge.textContent = 'Lancar (Normal)';
        }
    }

    // Trigger row click picking
    function pickPort(portName) {
        const markers = Array.from(document.querySelectorAll('.port-marker-node'));
        const matched = markers.find(m => m.getAttribute('data-name') === portName);
        if (matched) {
            selectPortNode(matched);
            alert(`Pelabuhan ${portName} dipilih. Periksa panel rincian stasiun maritim di sebelah kanan.`);
        }
    }

    // Filtering logic
    function applyPortFilters() {
        const query = document.getElementById('search-port-input').value.toLowerCase();
        const region = document.getElementById('filter-port-region').value;
        const status = document.getElementById('filter-port-status').value;
        const sortVal = document.getElementById('sort-port-select').value;

        const tableBody = document.getElementById('ports-table-body');
        const rows = Array.from(document.querySelectorAll('.port-table-row'));
        const markers = Array.from(document.querySelectorAll('.port-marker-node'));

        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name').toLowerCase();
            const country = row.getAttribute('data-country').toLowerCase();
            const rowRegion = row.getAttribute('data-region');
            const rowStatus = row.getAttribute('data-status');

            const matchesSearch = name.includes(query) || country.includes(query);
            const matchesRegion = (region === 'all' || rowRegion === region);
            const matchesStatus = (status === 'all' || rowStatus === status);

            if (matchesSearch && matchesRegion && matchesStatus) {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Sync markers visibility on map
        markers.forEach(marker => {
            const name = marker.getAttribute('data-name').toLowerCase();
            const country = marker.getAttribute('data-country').toLowerCase();
            const markerRegion = marker.getAttribute('data-region');
            const markerStatus = marker.getAttribute('data-status');

            const matchesSearch = name.includes(query) || country.includes(query);
            const matchesRegion = (region === 'all' || markerRegion === region);
            const matchesStatus = (status === 'all' || markerStatus === status);

            if (matchesSearch && matchesRegion && matchesStatus) {
                marker.style.display = 'block';
            } else {
                marker.style.display = 'none';
            }
        });

        // Sorting
        if (visibleCount > 0) {
            rows.sort((a, b) => {
                if (sortVal === 'name') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortVal === 'capacity-desc') {
                    return parseFloat(b.getAttribute('data-capacity')) - parseFloat(a.getAttribute('data-capacity'));
                } else if (sortVal === 'congestion-desc') {
                    return parseFloat(b.getAttribute('data-congestion')) - parseFloat(a.getAttribute('data-congestion'));
                }
                return 0;
            });
            rows.forEach(row => tableBody.appendChild(row));
        }

        // Toggle Grid vs States
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
    function triggerPortsSync() {
        const btn = document.getElementById('btn-sync-ports');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Pelabuhan';
            btn.disabled = false;
            alert('Aktivitas pelabuhan global berhasil disinkronisasikan ke API Satelit Maritim!');
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
            applyPortFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-port-input').value = 'PelabuhanXyz';
        applyPortFilters();
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
        document.getElementById('search-port-input').value = '';
        document.getElementById('filter-port-region').value = 'all';
        document.getElementById('filter-port-status').value = 'all';
        document.getElementById('sort-port-select').value = 'name';
        applyPortFilters();
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
</style>
@endsection
