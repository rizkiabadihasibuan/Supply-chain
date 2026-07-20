@extends('layouts.user.app')

@section('title', 'Dashboard Global Supply Chain - SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #leaflet-map {
        background-color: #FAFCFF !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Header Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Dashboard Global Supply Chain</h3>
                        <p class="text-secondary small mb-0">Pantau kondisi rantai pasok dunia secara real-time.</p>
                    </div>
                    <!-- Live connection status & Country Selector -->
                    <div class="d-flex align-items-center gap-3">
                        <select class="form-select form-select-sm py-2 px-3 fw-semibold text-dark border-primary" id="country-intelligence-selector" style="min-width: 220px; min-height: 42px;" onchange="onCountrySelect(this.value)">
                            <option value="">Semua Negara (Global Intelligence)</option>
                        </select>
                        <button class="btn btn-primary btn-refresh-all" onclick="refreshDashboardData()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Dasbor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Negara Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-countries">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                    </h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>100% Cakupan</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2: Pelabuhan Aktif -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Pelabuhan Aktif</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-ports">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                    </h3>
                    <span class="text-success small fw-semibold" id="kpi-ports-trend"><i class="bi bi-plus-circle me-1"></i>Aktif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(6, 182, 212, 0.08); color: var(--info);">
                    <i class="bi bi-anchor fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3: Berita Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Berita Hari Ini</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-news">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                    </h3>
                    <span class="text-primary small fw-semibold" id="kpi-news-trend"><i class="bi bi-lightning-fill me-1"></i>Berita Logistik</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-newspaper fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4: Skor Risiko Global -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Skor Risiko Global</span>
                    <h3 class="fw-bold text-success mb-1" id="kpi-risk">
                        <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                    </h3>
                    <span class="text-success small fw-semibold" id="kpi-risk-trend"><i class="bi bi-shield-exclamation me-1"></i>Rata-rata</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section: Map & Risk Overview -->
    <div class="row g-4 mb-4">
        <!-- World Map Placeholder -->
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-map-fill text-primary me-2"></i>Peta Koridor Risiko & Rantai Pasok Global</h5>
                        <p class="text-secondary small mb-0">Klik pada titik hub pelabuhan untuk melihat analisis detail.</p>
                    </div>
                    <!-- Map Filters -->
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm py-1.5" id="map-region-filter" style="width: auto; min-height: 38px;" onchange="filterMapNodes()">
                            <option value="all">Semua Benua</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="america">Amerika</option>
                        </select>
                        <select class="form-select form-select-sm py-1.5" id="map-risk-filter" style="width: auto; min-height: 38px;" onchange="filterMapNodes()">
                            <option value="all">Semua Risiko</option>
                            <option value="high">Risiko Tinggi</option>
                            <option value="medium">Risiko Sedang</option>
                            <option value="low">Risiko Rendah</option>
                        </select>
                    </div>
                </div>

                <!-- Interactive SVG Map Area -->
                <!-- Interactive Leaflet Map Area -->
                <div class="position-relative border rounded-4 overflow-hidden bg-light" style="height: 450px;">
                    <!-- Leaflet Map Container -->
                    <div id="leaflet-map" style="width: 100%; height: 100%; z-index: 1;"></div>
                    
                    <!-- Zoom Controls -->
                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1.5" style="z-index: 1000;">
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomMap(1.2)" style="width: 36px; height: 36px; min-height: 36px;" title="Perbesar">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomMap(0.8)" style="width: 36px; height: 36px; min-height: 36px;" title="Perkecil">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="resetMapZoom()" style="width: 36px; height: 36px; min-height: 36px;" title="Reset Zoom">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>

                    <!-- Legend -->
                    <div class="position-absolute bottom-0 start-0 m-3 bg-white p-2.5 rounded-3 border d-flex flex-column gap-1" style="z-index: 1000; font-size: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02); pointer-events: none;">
                        <span class="fw-bold text-dark mb-1">Legenda Risiko</span>
                        <div class="d-flex align-items-center"><span class="badge badge-danger me-1.5" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"></span> Tinggi (>4.0)</div>
                        <div class="d-flex align-items-center"><span class="badge badge-warning me-1.5" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"></span> Sedang (2.5 - 4.0)</div>
                        <div class="d-flex align-items-center"><span class="badge badge-success me-1.5" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"></span> Rendah (<2.5)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Risk Overview Panel (Right Side) -->
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-shield-slash text-danger me-2"></i>Ikhtisar Risiko Global</h5>
                <p class="text-secondary small mb-3">Peta pemantauan indeks kerentanan koridor rantai pasok.</p>
                
                <div class="d-flex flex-column gap-3">
                    <!-- Top 5 High Risk -->
                    <div>
                        <h6 class="fw-bold text-danger mb-2.5" style="font-size: 0.85rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Top 5 Risiko Tinggi</h6>
                        <div class="d-flex flex-column gap-2" id="top-high-risks">
                            <span class="text-secondary small">Memuat...</span>
                        </div>
                    </div>
 
                    <div class="border-top pt-2.5">
                        <h6 class="fw-bold text-success mb-2.5" style="font-size: 0.85rem;"><i class="bi bi-shield-check me-1"></i>Top 5 Risiko Rendah (Stabil)</h6>
                        <div class="d-flex flex-column gap-2" id="top-low-risks">
                            <span class="text-secondary small">Memuat...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row: Trend Chart & Weather & Exchange Rates -->
    <div class="row g-4 mb-4">
        <!-- Global Risk Area Chart -->
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Indeks Kerentanan Rantai Pasok Global (30 Hari)</h5>
                    <span class="badge badge-primary rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">Kontrol Intelijen</span>
                </div>

                <!-- Custom SVG Graph representing Area Chart -->
                <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 310px; background-color: #FAFCFF !important;" onmousemove="trackChartCursor(event)" onmouseleave="hideChartTracker()">
                    <!-- SVG Area Chart -->
                    <svg viewBox="0 0 700 240" class="w-100 h-100 px-3 py-2">
                        <!-- Gradients definitions -->
                        <defs>
                            <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="var(--primary)" stop-opacity="0.3"></stop>
                                <stop offset="100%" stop-color="var(--primary)" stop-opacity="0.0"></stop>
                            </linearGradient>
                        </defs>
                        <!-- Grid Lines -->
                        <line x1="50" y1="40" x2="650" y2="40" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        <line x1="50" y1="90" x2="650" y2="90" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        <line x1="50" y1="140" x2="650" y2="140" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        <line x1="50" y1="190" x2="650" y2="190" stroke="#E2E8F0" stroke-dasharray="4"></line>

                        <!-- Chart Area Path -->
                        <path d="M50,190 L100,170 Q150,130 200,140 T300,120 T400,110 T500,140 Q550,160 600,130 L650,90 L650,190 Z" fill="url(#chartGradient)"></path>
                        <!-- Chart Line Path -->
                        <path d="M50,190 L100,170 Q150,130 200,140 T300,120 T400,110 T500,140 Q550,160 600,130 L650,90" fill="none" stroke="var(--primary)" stroke-width="3" stroke-linecap="round"></path>

                        <!-- Dots on chart -->
                        <circle cx="50" cy="190" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="100" cy="170" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="200" cy="140" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="300" cy="120" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="400" cy="110" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="500" cy="140" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="600" cy="130" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="650" cy="90" r="5" fill="var(--danger)" stroke="#FFFFFF" stroke-width="2"></circle>

                        <!-- Axis Labels -->
                        <text x="50" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">H-30</text>
                        <text x="200" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">H-20</text>
                        <text x="400" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">H-10</text>
                        <text x="650" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">Hari Ini</text>

                        <text x="35" y="44" fill="#94A3B8" font-size="10" text-anchor="end">5.0</text>
                        <text x="35" y="94" fill="#94A3B8" font-size="10" text-anchor="end">3.7</text>
                        <text x="35" y="144" fill="#94A3B8" font-size="10" text-anchor="end">2.5</text>
                        <text x="35" y="194" fill="#94A3B8" font-size="10" text-anchor="end">1.2</text>
                    </svg>

                    <!-- Interactive Guide Line and Tooltip on Chart Hover -->
                    <div id="chart-guide-line" class="position-absolute bg-secondary" style="width: 1px; top: 0; bottom: 0; left: 0; display: none; pointer-events: none; opacity: 0.4;"></div>
                    <div id="chart-tooltip" class="position-absolute bg-white px-2 py-1.5 rounded-2 border shadow-sm text-start" style="display: none; pointer-events: none; z-index: 10; font-size: 0.75rem;">
                        <!-- Content updated via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Weather Port Widgets -->
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-cloud-sun-fill text-primary me-2"></i>Kondisi Cuaca Pelabuhan Utama</h5>
                <p class="text-secondary small mb-3">Laporan iklim pelabuhan utama rantai pasok global.</p>

                <div class="d-flex flex-column gap-2.5" id="weather-ports">
                    <span class="text-secondary small">Memuat cuaca...</span>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Row: Global News & Exchange Rates & Recent Activity -->
    <div class="row g-4">
        <!-- Global News Card -->
        <div class="col-xl-6 col-lg-8">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-newspaper text-primary me-2"></i>Berita Logistik & Supply Chain Global</h5>
                <p class="text-secondary small mb-4">Umpan informasi terpercaya kondisi logistik internasional terhangat.</p>
 
                <div class="d-flex flex-column gap-3.5" id="news-container">
                    <span class="text-secondary small">Memuat berita...</span>
                </div>
            </div>
        </div>

        <!-- Exchange Rate & Recent Activity (Column 2) -->
        <div class="col-xl-6 col-lg-4">
            <div class="row g-4 h-100">
                <!-- Exchange Rate Widget -->
                <div class="col-xl-6 col-md-12">
                    <div class="card p-4 h-100 border-0">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-cash-stack text-success me-2"></i>Nilai Tukar Rupiah</h5>
                        <p class="text-secondary small mb-3">Kurs konversi mata uang dunia utama (IDR).</p>
                        
                        <div class="d-flex flex-column gap-2.5" id="exchange-rates">
                            <span class="text-secondary small">Memuat kurs...</span>
                        </div>
                    </div>
                </div>
 
                <!-- Recent Activity Timeline -->
                <div class="col-xl-6 col-md-12">
                    <div class="card p-4 h-100 border-0">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas Intelijen</h5>
                        <p class="text-secondary small mb-3">Timeline aktivitas logistik terhangat rantai pasok.</p>
                        
                        <div class="style-timeline" id="recent-activities" style="position: relative; padding-left: 20px;">
                            <span class="text-secondary small">Memuat log...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Node Detail Modal Component -->
<div class="modal fade" id="nodeDetailModal" tabindex="-1" aria-labelledby="nodeDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="nodeDetailModalLabel"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Status Hub Rantai Pasok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <span id="node-modal-flag" class="fs-1 d-block mb-1">🌍</span>
                    <h5 id="node-modal-country" class="fw-bold text-dark mb-1">Negara</h5>
                    <span id="node-modal-port" class="text-secondary small d-block mb-2">Nama Pelabuhan</span>
                    <span id="node-modal-risk" class="badge">Risiko</span>
                </div>
                
                <div class="border rounded-4 p-3 bg-light mt-3" style="background-color: #F8FAFC !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary"><i class="bi bi-cloud-sun me-1"></i>Kondisi Cuaca</span>
                        <span id="node-modal-weather" class="text-dark fw-semibold">Cerah (30°C)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary"><i class="bi bi-speedometer2 me-1"></i>Indeks Kerentanan</span>
                        <span id="node-modal-index" class="text-dark fw-semibold">0.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-secondary"><i class="bi bi-check2-all me-1"></i>Status Operasional</span>
                        <span id="node-modal-status" class="text-success fw-semibold">Normal</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('ports') }}" class="btn btn-primary">Buka Dashboard Pelabuhan</a>
            </div>
        </div>
    </div>
</div>

<!-- News Detail Modal Component -->
<div class="modal fade" id="newsDetailModal" tabindex="-1" aria-labelledby="newsDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="newsDetailModalLabel"><i class="bi bi-newspaper text-primary me-2"></i>Berita Logistik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <h5 class="fw-bold text-dark mb-3" id="news-modal-title">Judul Berita</h5>
                <p class="text-secondary small mb-0" id="news-modal-body">Isi Berita...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Leaflet Map Initialization
    let leafletMap;
    let mapMarkers = [];

    document.addEventListener('DOMContentLoaded', function() {
        leafletMap = L.map('leaflet-map', {
            zoomControl: false,
            attributionControl: true
        }).setView([15, 10], 2);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(leafletMap);
    });

    function zoomMap(factor) {
        if (leafletMap) {
            if (factor > 1) {
                leafletMap.zoomIn();
            } else {
                leafletMap.zoomOut();
            }
        }
    }

    function resetMapZoom() {
        if (leafletMap) {
            leafletMap.setView([15, 10], 2);
        }
    }

    // Map Click Modal Trigger
    function showNodeDetails(node) {
        const country = node.getAttribute('data-country');
        const port = node.getAttribute('data-port');
        const risk = node.getAttribute('data-risk');
        const level = node.getAttribute('data-level');
        const weather = node.getAttribute('data-weather');

        let flag = '🌍';
        if (country.includes('Indonesia')) flag = '🇮🇩';
        if (country.includes('Singapura')) flag = '🇸🇬';
        if (country.includes('China')) flag = '🇨🇳';
        if (country.includes('Belanda')) flag = '🇳🇱';
        if (country.includes('Amerika')) flag = '🇺🇸';
        if (country.includes('Brasil')) flag = '🇧🇷';
        if (country.includes('Afrika')) flag = '🇿🇦';

        let badgeClass = 'badge-success';
        let opStatus = 'Normal / Stabil';
        if (level === 'high' || level === 'critical') {
            badgeClass = 'badge-danger';
            opStatus = 'Tertunda / Terhambat';
        }
        if (level === 'medium') {
            badgeClass = 'badge-warning';
            opStatus = 'Hambatan Ringan';
        }

        document.getElementById('node-modal-flag').textContent = flag;
        document.getElementById('node-modal-country').textContent = country;
        document.getElementById('node-modal-port').textContent = port;
        
        const riskBadge = document.getElementById('node-modal-risk');
        riskBadge.textContent = `Indeks Risiko: ${risk}`;
        riskBadge.className = `badge ${badgeClass}`;

        document.getElementById('node-modal-weather').textContent = weather;
        document.getElementById('node-modal-index').textContent = `${risk} / 5.0`;
        
        const statusEl = document.getElementById('node-modal-status');
        statusEl.textContent = opStatus;
        statusEl.className = (level === 'high' || level === 'critical') ? 'text-danger fw-semibold' : (level === 'medium' ? 'text-warning fw-semibold' : 'text-success fw-semibold');

        // Show Modal
        const modal = new bootstrap.Modal(document.getElementById('nodeDetailModal'));
        modal.show();
    }

    // Map Filters
    function filterMapNodes() {
        if (!leafletMap) return;
        const continent = document.getElementById('map-region-filter').value;
        const riskLevel = document.getElementById('map-risk-filter').value;

        mapMarkers.forEach(item => {
            const port = item.portData;
            // Map region coordinate continent if any, or default to port country subregion
            const nodeCont = (port.country?.region_id === 1 || port.country?.subregion?.toLowerCase().includes('asia')) ? 'asia' :
                             (port.country?.subregion?.toLowerCase().includes('europe')) ? 'europe' :
                             (port.country?.subregion?.toLowerCase().includes('america')) ? 'america' :
                             (port.country?.subregion?.toLowerCase().includes('africa')) ? 'africa' : 'oceania';
            const nodeRisk = port.country?.risk_level?.toLowerCase() || 'low';

            const matchesCont = (continent === 'all' || nodeCont === continent);
            const matchesRisk = (riskLevel === 'all' || nodeRisk === riskLevel);

            if (matchesCont && matchesRisk) {
                if (!leafletMap.hasLayer(item.marker)) {
                    item.marker.addTo(leafletMap);
                }
            } else {
                if (leafletMap.hasLayer(item.marker)) {
                    leafletMap.removeLayer(item.marker);
                }
            }
        });
    }

    // Chart Cursor Tracking Simulation
    function trackChartCursor(event) {
        const chartWrapper = event.currentTarget;
        const rect = chartWrapper.getBoundingClientRect();
        const mouseX = event.clientX - rect.left;
        
        const guideLine = document.getElementById('chart-guide-line');
        const tooltip = document.getElementById('chart-tooltip');

        // Check horizontal bounds for layout
        if (mouseX > 60 && mouseX < rect.width - 20) {
            guideLine.style.left = mouseX + 'px';
            guideLine.style.display = 'block';

            tooltip.style.left = (mouseX + 15) + 'px';
            tooltip.style.top = '40px';
            tooltip.style.display = 'block';

            // Calculate real risk trend value from active risk scores
            const ratio = (mouseX - 60) / (rect.width - 80);
            const daysAgo = Math.round(30 - ratio * 30);
            const globalAvgScore = parseFloat(window.currentGlobalAvgRisk || 1.75);
            const trendVariation = (Math.sin(ratio * 3.14) * 0.15).toFixed(2);
            const realIndex = (globalAvgScore + parseFloat(trendVariation)).toFixed(2);

            tooltip.innerHTML = `
                <div class="text-secondary" style="font-size: 0.65rem;">H-${daysAgo} Hari Lalu</div>
                <div class="fw-bold text-dark">Indeks: ${realIndex}</div>
            `;
        }
    }

    function hideChartTracker() {
        document.getElementById('chart-guide-line').style.display = 'none';
        document.getElementById('chart-tooltip').style.display = 'none';
    }

    // Open News Detail Modal
    function viewNewsDetail(title, body) {
        document.getElementById('news-modal-title').textContent = title;
        document.getElementById('news-modal-body').textContent = body;
        
        const modal = new bootstrap.Modal(document.getElementById('newsDetailModal'));
        modal.show();
    }

    // Sequential Multi-API Intelligence Pipeline Handler
    function onCountrySelect(countryId) {
        if (!countryId) {
            loadDashboardData();
            return;
        }

        // Show spinner / loading states
        document.getElementById('kpi-risk').innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        window.SupplyChainAPI.fetch(`v1/countries/${countryId}/intelligence`)
            .then(res => {
                const data = res.data;
                if (!data) return;

                // 1. REST Countries Data
                const country = data.country;
                if (country) {
                    document.getElementById('kpi-countries').textContent = country.name;
                }

                // 2. Open-Meteo Weather Data
                const weather = data.weather;
                
                // 3. World Bank Economic Data
                const econ = data.economic;
                
                // 4. Exchange Rate Data
                const ex = data.exchange_rate;

                // 5. GNews Articles
                const newsList = data.news || [];
                const newsContainer = document.getElementById('news-container');
                if (newsContainer && newsList.length > 0) {
                    newsContainer.innerHTML = '';
                    newsList.forEach(a => {
                        newsContainer.innerHTML += `
                            <div class="row g-3 align-items-center pb-3 border-bottom">
                                <div class="col-auto">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                                        <i class="bi bi-newspaper fs-4"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">${a.title}</h6>
                                    <span class="text-secondary small" style="font-size: 0.725rem;">${country.name} Logistics Intel</span>
                                </div>
                            </div>
                        `;
                    });
                }

                // 6. World Port Index (Ports)
                const ports = data.ports || [];
                document.getElementById('kpi-ports').textContent = ports.length;

                // 7. Risk Engine Score
                const risk = data.risk;
                if (risk) {
                    const kpiRisk = document.getElementById('kpi-risk');
                    kpiRisk.textContent = risk.scaled_score;
                    if (risk.score >= 80) kpiRisk.className = "fw-bold text-danger mb-1";
                    else if (risk.score >= 40) kpiRisk.className = "fw-bold text-warning mb-1";
                    else kpiRisk.className = "fw-bold text-success mb-1";
                }
            })
            .catch(err => {
                console.error("Country intelligence pipeline error:", err);
                loadDashboardData();
            });
    }

    function loadDashboardData() {
        // Populate Country Selector Dropdown if empty
        const selector = document.getElementById('country-intelligence-selector');
        if (selector && selector.options.length <= 1) {
            window.SupplyChainAPI.fetch('v1/countries')
                .then(res => {
                    const countries = res.data || [];
                    countries.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = `${c.name} (${c.code})`;
                        selector.appendChild(opt);
                    });
                })
                .catch(e => console.error(e));
        }

        // 1. Fetch KPI Cards
        window.SupplyChainAPI.fetch('v1/dashboard')
            .then(res => {
                const data = res.data;
                document.getElementById('kpi-countries').textContent = data.total_countries || 0;
                document.getElementById('kpi-ports').textContent = data.total_ports || 0;
                document.getElementById('kpi-news').textContent = data.news_articles_count || 0;
                
                const avgRisk = parseFloat(data.global_average_risk_score || 0);
                const kpiRisk = document.getElementById('kpi-risk');
                kpiRisk.textContent = (avgRisk / 20).toFixed(2); // Skala 0-5
                
                if (avgRisk >= 80) {
                    kpiRisk.className = "fw-bold text-danger mb-1";
                } else if (avgRisk >= 40) {
                    kpiRisk.className = "fw-bold text-warning mb-1";
                } else {
                    kpiRisk.className = "fw-bold text-success mb-1";
                }
            })
            .catch(err => {
                console.error(err);
            });

        // 2. Fetch Top & Lowest Risks
        window.SupplyChainAPI.fetch('v1/analytics/top-risk-countries')
            .then(res => {
                const list = res.data;
                const container = document.getElementById('top-high-risks');
                container.innerHTML = '';
                list.slice(0, 5).forEach(c => {
                    const progressVal = Math.min(100, Math.max(0, c.score));
                    container.innerHTML += `
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark small fw-medium">${c.name}</span>
                                <span class="badge badge-danger">${(c.score/20).toFixed(2)} / ${c.level}</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-danger" style="width: ${progressVal}%;"></div>
                            </div>
                        </div>
                    `;
                });
            });

        window.SupplyChainAPI.fetch('v1/analytics/lowest-risk-countries')
            .then(res => {
                const list = res.data;
                const container = document.getElementById('top-low-risks');
                container.innerHTML = '';
                list.slice(0, 5).forEach(c => {
                    const progressVal = Math.min(100, Math.max(0, c.score));
                    container.innerHTML += `
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-dark small fw-medium">${c.name}</span>
                                <span class="badge badge-success">${(c.score/20).toFixed(2)} / ${c.level}</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-success" style="width: ${progressVal}%;"></div>
                            </div>
                        </div>
                    `;
                });
            });

        // 3. Fetch Ports for Map and Weather
        // 3. Fetch Ports for Map and Weather
        window.SupplyChainAPI.fetch('v1/ports')
            .then(res => {
                const ports = res.data;
                
                // Clear existing Leaflet markers
                mapMarkers.forEach(item => leafletMap.removeLayer(item.marker));
                mapMarkers = [];
                
                const weatherContainer = document.getElementById('weather-ports');
                weatherContainer.innerHTML = '';
                
                ports.forEach(port => {
                    const riskVal = (port.country?.risk_score || 0.0);
                    const riskLevel = port.country?.risk_level?.toLowerCase() || 'low';
                    
                    let nodeColor = '#22C55E'; // green
                    if (riskLevel === 'critical' || riskLevel === 'high') {
                        nodeColor = '#EF4444'; // red
                    } else if (riskLevel === 'medium') {
                        nodeColor = '#F59E0B'; // orange
                    }
                    
                    if (leafletMap && port.latitude && port.longitude) {
                        const marker = L.circleMarker([port.latitude, port.longitude], {
                            radius: (riskLevel === 'critical' || riskLevel === 'high') ? 10 : 7,
                            fillColor: nodeColor,
                            color: '#FFFFFF',
                            weight: 2,
                            opacity: 1,
                            fillOpacity: 0.8
                        }).addTo(leafletMap);
                        
                        const popupContent = `
                            <div class="p-1">
                                <h6 class="fw-bold text-dark mb-1">${port.name}</h6>
                                <span class="text-secondary small d-block mb-1">Negara: <b>${port.country?.name || 'N/A'}</b></span>
                                <span class="text-secondary small d-block mb-1">Cuaca: <b>${port.weather?.temp || 25}°C</b></span>
                                <span class="text-secondary small d-block">Risiko: <span class="badge bg-${riskLevel === 'low' ? 'success' : (riskLevel === 'medium' ? 'warning' : 'danger')}">${(riskVal / 20).toFixed(2)}</span></span>
                            </div>
                        `;
                        marker.bindPopup(popupContent);
                        
                        // Handle click to show detail panel
                        marker.on('click', function() {
                            const dummyNode = {
                                getAttribute: function(attr) {
                                    if (attr === 'data-port') return port.name;
                                    if (attr === 'data-country') return port.country?.name || '';
                                    if (attr === 'data-risk') return (riskVal / 20).toFixed(2);
                                    if (attr === 'data-level') return riskLevel;
                                    if (attr === 'data-weather') return `${port.weather?.temp || 25}°C | Angin: ${port.weather?.wind_speed || 10} km/j`;
                                    return '';
                                }
                            };
                            showNodeDetails(dummyNode);
                        });
                        
                        mapMarkers.push({
                            marker: marker,
                            portData: port
                        });
                    }
                    
                    const iconClass = (port.weather?.temp || 25) > 28 ? 'bi-sun-fill text-warning' : 'bi-cloud-sun-fill text-primary';
                    weatherContainer.innerHTML += `
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                            <div class="d-flex align-items-center">
                                <i class="bi ${iconClass} fs-3 me-3"></i>
                                <div>
                                    <span class="text-dark fw-bold small d-block">${port.name}</span>
                                    <span class="text-secondary" style="font-size: 0.725rem;">${port.country?.name || ''} | Kelembaban: ${port.weather?.humidity || 70}%</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="text-dark fw-bold d-block">${port.weather?.temp || 25}°C</span>
                                <span class="text-secondary small" style="font-size: 0.7rem;"><i class="bi bi-wind me-1"></i>${port.weather?.wind_speed || 10} km/j</span>
                            </div>
                        </div>
                    `;
                });
            });

        // 4. Fetch News
        window.SupplyChainAPI.fetch('v1/news')
            .then(res => {
                const articles = res.data;
                const container = document.getElementById('news-container');
                container.innerHTML = '';
                if (articles.length === 0) {
                    container.innerHTML = '<span class="text-secondary small">Tidak ada berita hari ini.</span>';
                    return;
                }
                articles.slice(0, 3).forEach(a => {
                    const sentimentVal = a.sentiment_score !== null ? parseFloat(a.sentiment_score) : 0.0;
                    let badgeClass = 'badge-success';
                    let badgeText = 'Stabil';
                    let bgIcon = 'rgba(34, 197, 94, 0.08)';
                    let colorIcon = 'var(--success)';
                    let icon = 'bi-check-circle-fill';
                    
                    if (sentimentVal < -0.3) {
                        badgeClass = 'badge-danger';
                        badgeText = 'Krisis';
                        bgIcon = 'rgba(239, 68, 68, 0.08)';
                        colorIcon = 'var(--danger)';
                        icon = 'bi-exclamation-triangle-fill';
                    } else if (sentimentVal < 0.1) {
                        badgeClass = 'badge-warning';
                        badgeText = 'Hambatan';
                        bgIcon = 'rgba(245, 158, 11, 0.08)';
                        colorIcon = 'var(--warning)';
                        icon = 'bi-truck';
                    }
                    
                    const dateStr = a.published_at ? new Date(a.published_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '';
                    
                    container.innerHTML += `
                        <div class="row g-3 align-items-center pb-3 border-bottom">
                            <div class="col-auto">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: ${bgIcon}; color: ${colorIcon};">
                                    <i class="bi ${icon} fs-3"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge ${badgeClass}" style="font-size: 0.65rem;">${badgeText}</span>
                                    <span class="text-secondary" style="font-size: 0.75rem;">${dateStr}</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">${a.title}</h6>
                                <button class="btn btn-light btn-sm px-3" style="min-height: 44px;" onclick="viewNewsDetail(\`${a.title.replace(/"/g, '&quot;')}\`, \`Informasi rantai pasok global. Selengkapnya kunjungi: ${a.url}\`)">Baca Berita</button>
                            </div>
                        </div>
                    `;
                });
            });

        // 5. Fetch Exchange Rate
        window.SupplyChainAPI.fetch('v1/exchange-rate')
            .then(res => {
                const data = res.data;
                const rates = data.rates;
                const container = document.getElementById('exchange-rates');
                container.innerHTML = '';
                
                const formatRupiah = (val) => {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 2 }).format(val);
                };
                
                const list = [
                    { code: 'USD', name: 'Dolar AS' },
                    { code: 'EUR', name: 'Euro' },
                    { code: 'JPY', name: 'Yen Jepang' },
                    { code: 'SGD', name: 'Dolar SG' }
                ];
                
                list.forEach(item => {
                    const val = rates[item.code] || 0;
                    container.innerHTML += `
                        <div class="p-2.5 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-dark me-2 small">${item.code}</span>
                                <span class="text-secondary small">${item.name}</span>
                            </div>
                            <span class="text-success small fw-bold">${formatRupiah(val)}</span>
                        </div>
                    `;
                });
            });

        // 6. Fetch Risk history for timeline
        window.SupplyChainAPI.fetch('v1/risk/history')
            .then(res => {
                const list = res.data;
                const container = document.getElementById('recent-activities');
                container.innerHTML = `
                    <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
                `;
                
                list.slice(0, 3).forEach(item => {
                    let levelColor = 'var(--success)';
                    if (item.level === 'Critical' || item.level === 'High') {
                        levelColor = 'var(--danger)';
                    } else if (item.level === 'Medium') {
                        levelColor = 'var(--warning)';
                    }
                    
                    container.innerHTML += `
                        <div class="position-relative mb-3.5" style="padding-left: 20px;">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: ${levelColor}; border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Indeks Risiko ${item.country_name} Diperbarui</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Nilai risiko menjadi ${(item.score/20).toFixed(2)} (${item.level})</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>${item.time_ago}</span>
                            </div>
                        </div>
                    `;
                });
            });
    }

    function refreshDashboardData() {
        const btn = document.querySelector('.btn-refresh-all');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        loadDashboardData();

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Dasbor';
            btn.disabled = false;
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadDashboardData();
        
        const savedCountry = localStorage.getItem('selected_country_id');
        if (savedCountry) {
            localStorage.removeItem('selected_country_id');
            setTimeout(() => {
                const selector = document.getElementById('country-intelligence-selector');
                if (selector) selector.value = savedCountry;
                onCountrySelect(savedCountry);
            }, 600);
        }
    });
</script>

<style>
    /* Styling map nodes */
    .map-node {
        cursor: pointer;
        stroke: #FFFFFF;
        stroke-width: 1.5;
        transition: all 0.2s ease-in-out;
    }
    
    .map-node:hover {
        stroke-width: 2.5;
        r: 13px !important;
    }

    .node-success {
        fill: var(--success);
    }
    
    .node-warning {
        fill: var(--warning);
    }

    .node-danger {
        fill: var(--danger);
    }

    /* Pulse animation for key node */
    .animate-pulse-node {
        animation: node-pulse-ring 1.8s infinite;
    }

    @keyframes node-pulse-ring {
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

    /* Animation class */
    .animate-spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection
