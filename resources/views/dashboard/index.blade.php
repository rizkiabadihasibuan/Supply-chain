@extends('layouts.user.app')

@section('title', 'Dashboard Global Supply Chain - SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    #leaflet-map { background-color: #E8F4FD !important; }
    .kpi-value { font-size: 1.75rem; font-weight: 700; }
    .risk-bar { height: 6px; border-radius: 3px; background: #E2E8F0; overflow: hidden; }
    .risk-bar-fill { height: 100%; border-radius: 3px; transition: width 0.5s ease; }
    .animate-spin { animation: spin 1s linear infinite; display: inline-block; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .weather-card { background: #F8FAFC !important; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px; }
    .rate-card { background: #F8FAFC !important; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 14px; }
    .timeline-line { position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background: #E2E8F0; }
    .timeline-dot { position: absolute; left: -19px; top: 5px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid #fff; }
</style>
@endsection

@section('content')
<div class="container-fluid p-0 fade-in-up">

    {{-- ── HEADER ── --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Dashboard Global Supply Chain</h3>
                        <p class="text-secondary small mb-0">Pantau kondisi rantai pasok dunia secara real-time.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <select class="form-select form-select-sm py-2 px-3 fw-semibold text-dark border-primary"
                            id="country-intelligence-selector" style="min-width:220px;min-height:42px;"
                            onchange="onCountrySelect(this.value)">
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

    {{-- ── 4 KPI CARDS ── --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <div class="kpi-value text-dark mb-1" id="kpi-countries">
                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                    </div>
                    <span class="text-success small fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>100% Cakupan</span>
                </div>
                <div class="p-3 rounded-4" style="background:rgba(37,99,235,.08);color:var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Pelabuhan Aktif</span>
                    <div class="kpi-value text-dark mb-1" id="kpi-ports">
                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                    </div>
                    <span class="text-success small fw-semibold"><i class="bi bi-plus-circle me-1"></i>Aktif</span>
                </div>
                <div class="p-3 rounded-4" style="background:rgba(6,182,212,.08);color:var(--info);">
                    <i class="bi bi-anchor fs-3"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Berita Hari Ini</span>
                    <div class="kpi-value text-dark mb-1" id="kpi-news">
                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                    </div>
                    <span class="text-primary small fw-semibold"><i class="bi bi-lightning-fill me-1"></i>Berita Logistik</span>
                </div>
                <div class="p-3 rounded-4" style="background:rgba(245,158,11,.08);color:var(--warning);">
                    <i class="bi bi-newspaper fs-3"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Skor Risiko Global</span>
                    <div class="kpi-value text-success mb-1" id="kpi-risk">
                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                    </div>
                    <span class="text-success small fw-semibold"><i class="bi bi-shield-exclamation me-1"></i>Rata-rata</span>
                </div>
                <div class="p-3 rounded-4" style="background:rgba(34,197,94,.08);color:var(--success);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAP + RISK PANEL ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-map-fill text-primary me-2"></i>Peta Koridor Risiko & Rantai Pasok Global</h5>
                        <p class="text-secondary small mb-0">Klik titik pelabuhan untuk detail analisis risiko.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="map-region-filter" style="width:auto;min-height:38px;" onchange="filterMapMarkers()">
                            <option value="all">Semua Benua</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="america">Amerika</option>
                            <option value="africa">Afrika</option>
                        </select>
                        <select class="form-select form-select-sm" id="map-risk-filter" style="width:auto;min-height:38px;" onchange="filterMapMarkers()">
                            <option value="all">Semua Risiko</option>
                            <option value="high">Risiko Tinggi</option>
                            <option value="medium">Risiko Sedang</option>
                            <option value="low">Risiko Rendah</option>
                        </select>
                    </div>
                </div>
                <div class="position-relative rounded-4 overflow-hidden border" style="height:420px;">
                    <div id="leaflet-map" style="width:100%;height:100%;z-index:1;"></div>
                    {{-- Map action buttons --}}
                    <div class="position-absolute d-flex flex-column gap-1" style="top:10px;left:10px;z-index:1000;">
                        <button class="btn btn-light btn-sm border shadow-sm" onclick="zoomMap(1)" title="Perbesar" style="width:32px;height:32px;min-height:32px;padding:0;">
                            <i class="bi bi-plus-lg" style="font-size:.85rem;"></i>
                        </button>
                        <button class="btn btn-light btn-sm border shadow-sm" onclick="zoomMap(-1)" title="Perkecil" style="width:32px;height:32px;min-height:32px;padding:0;">
                            <i class="bi bi-dash-lg" style="font-size:.85rem;"></i>
                        </button>
                        <button class="btn btn-light btn-sm border shadow-sm" onclick="resetMapZoom()" title="Reset" style="width:32px;height:32px;min-height:32px;padding:0;">
                            <i class="bi bi-fullscreen-exit" style="font-size:.75rem;"></i>
                        </button>
                    </div>
                    {{-- Legend --}}
                    <div class="position-absolute bg-white rounded-3 border px-2 py-2 d-flex flex-column gap-1"
                         style="bottom:10px;left:10px;z-index:1000;font-size:.72rem;pointer-events:none;min-width:130px;">
                        <span class="fw-bold text-dark">Legenda Risiko</span>
                        <div class="d-flex align-items-center gap-1">
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#EF4444;"></span> Tinggi (&gt;60)
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#F59E0B;"></span> Sedang (30-60)
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:#22C55E;"></span> Rendah (&lt;30)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-shield-slash text-danger me-2"></i>Ikhtisar Risiko Global</h5>
                <p class="text-secondary small mb-3">Pemantauan indeks kerentanan koridor rantai pasok.</p>
                <div class="mb-3">
                    <h6 class="fw-bold text-danger mb-2" style="font-size:.83rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>Top 5 Risiko Tinggi
                    </h6>
                    <div id="top-high-risks" class="d-flex flex-column gap-2">
                        <div class="placeholder-glow"><span class="placeholder col-12 rounded"></span></div>
                        <div class="placeholder-glow"><span class="placeholder col-10 rounded"></span></div>
                    </div>
                </div>
                <div class="border-top pt-3">
                    <h6 class="fw-bold text-success mb-2" style="font-size:.83rem;">
                        <i class="bi bi-shield-check me-1"></i>Top 5 Risiko Rendah (Stabil)
                    </h6>
                    <div id="top-low-risks" class="d-flex flex-column gap-2">
                        <div class="placeholder-glow"><span class="placeholder col-12 rounded"></span></div>
                        <div class="placeholder-glow"><span class="placeholder col-10 rounded"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TREND CHART + WEATHER ── --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card p-4 border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Indeks Kerentanan (30 Hari)
                    </h5>
                    <span class="badge badge-primary px-2 py-1" style="font-size:.68rem;">Live Intelligence</span>
                </div>
                <div class="border rounded-4 overflow-hidden position-relative"
                     style="height:300px;background:#FAFCFF;"
                     onmousemove="trackChartCursor(event)" onmouseleave="hideChartTracker()">
                    <svg viewBox="0 0 700 240" class="w-100 h-100 px-3 py-2" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#2563EB" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#2563EB" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <line x1="50" y1="40"  x2="660" y2="40"  stroke="#E2E8F0" stroke-dasharray="4"/>
                        <line x1="50" y1="90"  x2="660" y2="90"  stroke="#E2E8F0" stroke-dasharray="4"/>
                        <line x1="50" y1="140" x2="660" y2="140" stroke="#E2E8F0" stroke-dasharray="4"/>
                        <line x1="50" y1="190" x2="660" y2="190" stroke="#E2E8F0" stroke-dasharray="4"/>
                        <path d="M50,180 C100,165 130,135 200,140 S290,118 360,115 S450,125 520,138 S590,125 660,88 L660,200 L50,200 Z"
                              fill="url(#chartGradient)"/>
                        <path d="M50,180 C100,165 130,135 200,140 S290,118 360,115 S450,125 520,138 S590,125 660,88"
                              fill="none" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="50"  cy="180" r="4" fill="#2563EB" stroke="#fff" stroke-width="2"/>
                        <circle cx="200" cy="140" r="4" fill="#2563EB" stroke="#fff" stroke-width="2"/>
                        <circle cx="360" cy="115" r="4" fill="#2563EB" stroke="#fff" stroke-width="2"/>
                        <circle cx="520" cy="138" r="4" fill="#2563EB" stroke="#fff" stroke-width="2"/>
                        <circle cx="660" cy="88"  r="5" fill="#EF4444" stroke="#fff" stroke-width="2"/>
                        <text x="50"  y="215" fill="#94A3B8" font-size="10" text-anchor="middle">H-30</text>
                        <text x="200" y="215" fill="#94A3B8" font-size="10" text-anchor="middle">H-20</text>
                        <text x="400" y="215" fill="#94A3B8" font-size="10" text-anchor="middle">H-10</text>
                        <text x="660" y="215" fill="#94A3B8" font-size="10" text-anchor="middle">Hari Ini</text>
                        <text x="40" y="44"  fill="#94A3B8" font-size="9" text-anchor="end">100</text>
                        <text x="40" y="94"  fill="#94A3B8" font-size="9" text-anchor="end">75</text>
                        <text x="40" y="144" fill="#94A3B8" font-size="9" text-anchor="end">50</text>
                        <text x="40" y="194" fill="#94A3B8" font-size="9" text-anchor="end">25</text>
                    </svg>
                    <div id="chart-guide-line" style="display:none;position:absolute;top:0;bottom:0;width:1px;background:#94A3B8;opacity:.5;pointer-events:none;"></div>
                    <div id="chart-tooltip" style="display:none;position:absolute;background:#fff;border:1px solid #E2E8F0;border-radius:8px;padding:6px 10px;font-size:.75rem;pointer-events:none;box-shadow:0 4px 12px rgba(0,0,0,.08);z-index:10;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2">
                    <i class="bi bi-cloud-sun-fill text-primary me-2"></i>Cuaca Pelabuhan Utama
                </h5>
                <p class="text-secondary small mb-3">Kondisi iklim hub logistik global saat ini.</p>
                <div id="weather-ports" class="d-flex flex-column gap-2">
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:56px;"></span></div>
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:56px;"></span></div>
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:56px;"></span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── NEWS + EXCHANGE + LOG ── --}}
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-1">
                    <i class="bi bi-newspaper text-primary me-2"></i>Berita Logistik & Supply Chain
                </h5>
                <p class="text-secondary small mb-3">Informasi terpercaya kondisi logistik internasional terhangat.</p>
                <div id="news-container" class="d-flex flex-column gap-3">
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:80px;"></span></div>
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:80px;"></span></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-1">
                    <i class="bi bi-cash-stack text-success me-2"></i>Nilai Tukar Rupiah
                </h5>
                <p class="text-secondary small mb-3">Kurs mata uang dunia utama terhadap IDR.</p>
                <div id="exchange-rates" class="d-flex flex-column gap-2">
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:42px;"></span></div>
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:42px;"></span></div>
                    <div class="placeholder-glow"><span class="placeholder col-12 rounded" style="height:42px;"></span></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-1">
                    <i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas
                </h5>
                <p class="text-secondary small mb-3">Timeline aktivitas risiko terkini.</p>
                <div id="recent-activities" class="position-relative" style="padding-left:20px;">
                    <div class="placeholder-glow mb-3"><span class="placeholder col-12 rounded" style="height:50px;"></span></div>
                    <div class="placeholder-glow mb-3"><span class="placeholder col-12 rounded" style="height:50px;"></span></div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end container --}}

{{-- ── MODALS ── --}}
<div class="modal fade" id="portDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Detail Hub Pelabuhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <span id="pm-flag" class="fs-1 d-block mb-1">🌍</span>
                    <h5 id="pm-country" class="fw-bold text-dark mb-0">–</h5>
                    <span id="pm-port" class="text-secondary small">–</span>
                </div>
                <div class="border rounded-3 p-3 bg-light mt-3">
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary"><i class="bi bi-cloud-sun me-1"></i>Kondisi Cuaca</span>
                        <span id="pm-weather" class="fw-semibold">–</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-secondary"><i class="bi bi-speedometer2 me-1"></i>Skor Risiko</span>
                        <span id="pm-risk" class="fw-semibold">–</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span class="text-secondary"><i class="bi bi-check2-all me-1"></i>Status</span>
                        <span id="pm-status" class="fw-semibold text-success">Normal</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('ports') }}" class="btn btn-primary">Lihat Semua Pelabuhan</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newsDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-newspaper text-primary me-2"></i>Detail Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <h5 class="fw-bold text-dark mb-2" id="news-modal-title">–</h5>
                <p class="text-secondary small" id="news-modal-body">–</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <a id="news-modal-link" href="#" target="_blank" class="btn btn-primary">Baca Selengkapnya</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
// ── MAP ──────────────────────────────────────────────
let leafletMap = null;
let mapMarkers = [];
let markerClusterGroup = null;

function initMap() {
    if (leafletMap) return;
    leafletMap = L.map('leaflet-map', { zoomControl: false, attributionControl: true }).setView([15, 10], 2);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OSM</a> &copy; <a href="https://carto.com">CARTO</a>',
        subdomains: 'abcd', maxZoom: 19
    }).addTo(leafletMap);
    
    markerClusterGroup = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50,
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true
    });
    leafletMap.addLayer(markerClusterGroup);
}

function zoomMap(dir) { if (leafletMap) { dir > 0 ? leafletMap.zoomIn() : leafletMap.zoomOut(); } }
function resetMapZoom() { if (leafletMap) leafletMap.setView([15, 10], 2); }

function filterMapMarkers() {
    const cont = document.getElementById('map-region-filter').value;
    const risk = document.getElementById('map-risk-filter').value;
    
    markerClusterGroup.clearLayers();
    
    mapMarkers.forEach(m => {
        const matchC = cont === 'all' || m.continent === cont;
        const matchR = risk === 'all' || m.riskLevel === risk;
        if (matchC && matchR) { 
            markerClusterGroup.addLayer(m.marker);
        }
    });
}

function showPortModal(data) {
    const flags = { Indonesia:'🇮🇩', Singapore:'🇸🇬', China:'🇨🇳', Netherlands:'🇳🇱', 'United States':'🇺🇸', Brazil:'🇧🇷', 'South Africa':'🇿🇦', Sudan:'🇸🇩' };
    document.getElementById('pm-flag').textContent    = flags[data.country] || '🌍';
    document.getElementById('pm-country').textContent = data.country || '–';
    document.getElementById('pm-port').textContent    = data.port || '–';
    document.getElementById('pm-weather').textContent = data.weather || '–';
    document.getElementById('pm-risk').textContent    = data.risk || '–';
    const lvl = (data.riskLevel || '').toLowerCase();
    const st  = document.getElementById('pm-status');
    if (lvl === 'high' || lvl === 'critical') { st.textContent = 'Terhambat'; st.className = 'fw-semibold text-danger'; }
    else if (lvl === 'medium')                { st.textContent = 'Hambatan Ringan'; st.className = 'fw-semibold text-warning'; }
    else                                      { st.textContent = 'Normal'; st.className = 'fw-semibold text-success'; }
    new bootstrap.Modal(document.getElementById('portDetailModal')).show();
}

// ── CHART TOOLTIP ────────────────────────────────────
function trackChartCursor(e) {
    const rect = e.currentTarget.getBoundingClientRect();
    const x = e.clientX - rect.left;
    if (x < 50 || x > rect.width - 20) return;
    const gl = document.getElementById('chart-guide-line');
    const tt = document.getElementById('chart-tooltip');
    gl.style.left = x + 'px'; gl.style.display = 'block';
    const ratio   = (x - 50) / (rect.width - 70);
    const daysAgo = Math.round(30 - ratio * 30);
    const avg     = parseFloat(window._avgRisk || 42);
    const val     = Math.max(0, Math.min(100, avg + Math.sin(ratio * Math.PI) * 8)).toFixed(1);
    tt.style.left    = Math.min(x + 12, rect.width - 120) + 'px';
    tt.style.top     = '30px';
    tt.style.display = 'block';
    tt.innerHTML     = `<div style="color:#64748B;font-size:.65rem;">H-${daysAgo} Hari Lalu</div><div class="fw-bold">Skor: ${val}</div>`;
}
function hideChartTracker() {
    document.getElementById('chart-guide-line').style.display = 'none';
    document.getElementById('chart-tooltip').style.display    = 'none';
}

// ── NEWS MODAL ────────────────────────────────────────
function viewNewsDetail(title, desc, url) {
    document.getElementById('news-modal-title').textContent = title;
    document.getElementById('news-modal-body').textContent  = desc || 'Klik tombol di bawah untuk baca selengkapnya.';
    document.getElementById('news-modal-link').href         = url || '#';
    new bootstrap.Modal(document.getElementById('newsDetailModal')).show();
}

// ── HELPERS ──────────────────────────────────────────
function riskColor(score) {
    if (score >= 60) return '#EF4444';
    if (score >= 30) return '#F59E0B';
    return '#22C55E';
}
function riskLabel(level) {
    const l = (level || '').toLowerCase();
    if (l === 'critical' || l === 'high')   return 'high';
    if (l === 'medium')                     return 'medium';
    return 'low';
}
function continentOf(port) {
    const sub = (port.country?.subregion || port.country?.region || '').toLowerCase();
    if (sub.includes('asia') || sub.includes('eastern') || sub.includes('southern') || sub.includes('south-eastern')) return 'asia';
    if (sub.includes('europe')) return 'europe';
    if (sub.includes('america')) return 'america';
    if (sub.includes('africa')) return 'africa';
    return 'other';
}
function fmtRupiah(rateFromIDR) {
    if (!rateFromIDR) return 'N/A';
    const idr = 1 / rateFromIDR;
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(idr);
}

// ── LOAD DATA FUNCTIONS ──────────────────────────────

function loadKPI() {
    window.SupplyChainAPI.fetch('v1/dashboard').then(res => {
        const d = res.data?.data || res.data || {};
        document.getElementById('kpi-countries').textContent = d.total_countries ?? 0;
        document.getElementById('kpi-ports').textContent     = d.total_ports ?? 0;
        document.getElementById('kpi-news').textContent      = d.news_articles_count ?? 0;
        const avg = parseFloat(d.global_average_risk_score || 0);
        window._avgRisk = avg;
        const el = document.getElementById('kpi-risk');
        el.textContent  = avg.toFixed(1);
        el.className    = 'kpi-value mb-1 ' + (avg >= 60 ? 'text-danger' : avg >= 30 ? 'text-warning' : 'text-success');
    }).catch(() => {
        ['kpi-countries','kpi-ports','kpi-news','kpi-risk'].forEach(id => document.getElementById(id).textContent = '–');
    });
}

function loadTopRisk() {
    window.SupplyChainAPI.fetch('v1/analytics/top-risk-countries').then(res => {
        const list = res.data?.data || res.data || [];
        const el   = document.getElementById('top-high-risks');
        el.innerHTML = '';
        if (!list.length) { el.innerHTML = '<span class="text-secondary small">Belum ada data.</span>'; return; }
        list.slice(0, 5).forEach(c => {
            const pct = Math.min(100, c.score);
            el.innerHTML += `<div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-dark small fw-medium">${c.name}</span>
                    <span class="badge" style="background:rgba(239,68,68,.15);color:#EF4444;font-size:.65rem;">${c.score.toFixed(1)} – ${c.level}</span>
                </div>
                <div class="risk-bar"><div class="risk-bar-fill" style="width:${pct}%;background:#EF4444;"></div></div>
            </div>`;
        });
    }).catch(() => {
        document.getElementById('top-high-risks').innerHTML = '<span class="text-secondary small">Gagal memuat.</span>';
    });
}

function loadLowestRisk() {
    window.SupplyChainAPI.fetch('v1/analytics/lowest-risk-countries').then(res => {
        const list = res.data?.data || res.data || [];
        const el   = document.getElementById('top-low-risks');
        el.innerHTML = '';
        if (!list.length) { el.innerHTML = '<span class="text-secondary small">Belum ada data.</span>'; return; }
        list.slice(0, 5).forEach(c => {
            const pct = Math.min(100, c.score);
            el.innerHTML += `<div>
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-dark small fw-medium">${c.name}</span>
                    <span class="badge" style="background:rgba(34,197,94,.15);color:#22C55E;font-size:.65rem;">${c.score.toFixed(1)} – ${c.level}</span>
                </div>
                <div class="risk-bar"><div class="risk-bar-fill" style="width:${pct}%;background:#22C55E;"></div></div>
            </div>`;
        });
    }).catch(() => {
        document.getElementById('top-low-risks').innerHTML = '<span class="text-secondary small">Gagal memuat.</span>';
    });
}

function loadPorts() {
    window.SupplyChainAPI.fetch('v1/ports').then(res => {
        const ports = res.data || [];
        // Clear old markers
        markerClusterGroup.clearLayers();
        mapMarkers = [];
        const weatherEl = document.getElementById('weather-ports');
        weatherEl.innerHTML = '';

        ports.forEach(port => {
            const score  = parseFloat(port.country?.risk_score || 0);
            const level  = riskLabel(port.country?.risk_level || '');
            const color  = riskColor(score);
            const cont   = continentOf(port);

            // Add Leaflet marker
            if (leafletMap && port.latitude && port.longitude) {
                const radius = score >= 60 ? 10 : score >= 30 ? 8 : 6;
                const marker = L.circleMarker([port.latitude, port.longitude], {
                    radius, fillColor: color, color: '#FFFFFF', weight: 2, opacity: 1, fillOpacity: 0.85
                });

                const temp    = port.weather?.temp ?? '–';
                const wind    = port.weather?.wind_speed ?? '–';
                const country = port.country?.name || 'N/A';
                marker.bindPopup(`
                    <div style="min-width:160px;">
                        <strong style="font-size:.88rem;">${port.name}</strong><br>
                        <span style="color:#64748B;font-size:.78rem;">📍 ${country}</span><br>
                        <span style="color:#64748B;font-size:.78rem;">🌡 ${temp}°C &nbsp; 💨 ${wind} km/j</span><br>
                        <span style="font-size:.75rem;">Risiko: <b style="color:${color}">${score.toFixed(1)}</b></span>
                    </div>
                `);
                marker.on('click', () => {
                    showPortModal({ country, port: port.name, weather: `${temp}°C, Angin ${wind} km/j`, risk: score.toFixed(1), riskLevel: level });
                });
                
                // Add to array for filtering, and add to cluster layer immediately
                mapMarkers.push({ marker, continent: cont, riskLevel: level });
                markerClusterGroup.addLayer(marker);
            }

            // Weather card (just show top 3 for brevity to avoid extremely long page)
            if (weatherEl.children.length < 3) {
                const temp     = port.weather?.temp ?? null;
                const wind     = port.weather?.wind_speed ?? null;
                const humidity = port.weather?.humidity ?? 70;
                const desc     = port.weather?.description || 'N/A';
                const icon     = (temp && temp > 30) ? 'bi-sun-fill text-warning' : (temp && temp < 15) ? 'bi-cloud-snow-fill text-info' : 'bi-cloud-sun-fill text-primary';
                weatherEl.innerHTML += `
                    <div class="weather-card d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi ${icon} fs-4"></i>
                            <div>
                                <span class="fw-bold text-dark d-block small">${port.name}</span>
                                <span class="text-secondary" style="font-size:.7rem;">${port.country?.name || ''} · ${desc}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold text-dark d-block">${temp !== null ? temp + '°C' : '–'}</span>
                            <span class="text-secondary" style="font-size:.7rem;"><i class="bi bi-droplet me-1"></i>${humidity}%</span>
                        </div>
                    </div>`;
            }
        });

        if (ports.length === 0) {
            weatherEl.innerHTML = '<span class="text-secondary small">Tidak ada data pelabuhan.</span>';
        }
    }).catch(() => {
        document.getElementById('weather-ports').innerHTML = '<span class="text-secondary small">Gagal memuat cuaca.</span>';
    });
}

function loadNews() {
    window.SupplyChainAPI.fetch('v1/news').then(res => {
        const articles = Array.isArray(res.data) ? res.data : (res.data?.data || []);
        const el = document.getElementById('news-container');
        el.innerHTML = '';
        if (!articles.length) { el.innerHTML = '<span class="text-secondary small">Tidak ada berita tersedia.</span>'; return; }
        articles.slice(0, 4).forEach(a => {
            // sentiment_status field (bukan sentiment_score)
            const status = (a.sentiment_status || 'neutral').toLowerCase();
            let badgeStyle = 'background:rgba(34,197,94,.15);color:#22C55E;'; let badgeText = 'Positif'; let iconBg = 'rgba(34,197,94,.08)'; let iconColor = '#22C55E'; let icon = 'bi-check-circle-fill';
            if (status === 'negative') {
                badgeStyle = 'background:rgba(239,68,68,.15);color:#EF4444;'; badgeText = 'Negatif';
                iconBg = 'rgba(239,68,68,.08)'; iconColor = '#EF4444'; icon = 'bi-exclamation-triangle-fill';
            } else if (status === 'neutral') {
                badgeStyle = 'background:rgba(245,158,11,.15);color:#F59E0B;'; badgeText = 'Netral';
                iconBg = 'rgba(245,158,11,.08)'; iconColor = '#F59E0B'; icon = 'bi-info-circle-fill';
            }
            const dateStr = a.published_at ? new Date(a.published_at).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) : '';
            const safeTitle = (a.title || '').replace(/`/g,"'");
            const safeUrl   = a.url || '#';
            el.innerHTML += `
                <div class="d-flex gap-3 pb-3 border-bottom align-items-start">
                    <div class="rounded-3 flex-shrink-0 d-flex align-items-center justify-content-center"
                         style="width:56px;height:56px;background:${iconBg};color:${iconColor};">
                        <i class="bi ${icon} fs-4"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge" style="${badgeStyle}font-size:.62rem;">${badgeText}</span>
                            <span class="text-secondary" style="font-size:.72rem;">${dateStr}</span>
                        </div>
                        <p class="fw-bold text-dark mb-1" style="font-size:.85rem;line-height:1.3;">${a.title || '–'}</p>
                        <button class="btn btn-light btn-sm px-3" style="min-height:36px;font-size:.78rem;"
                            onclick="viewNewsDetail(\`${safeTitle}\`,\`${(a.description||'').replace(/`/g,"'")}\`,\`${safeUrl}\`)">
                            Baca Berita
                        </button>
                    </div>
                </div>`;
        });
    }).catch(() => {
        document.getElementById('news-container').innerHTML = '<span class="text-secondary small">Gagal memuat berita.</span>';
    });
}

function loadExchangeRate() {
    window.SupplyChainAPI.fetch('v1/exchange-rate?base=IDR').then(res => {
        const rates = res.data?.rates || {};
        const el    = document.getElementById('exchange-rates');
        el.innerHTML = '';
        [
            { code: 'USD', name: 'Dolar AS',    flag: '🇺🇸' },
            { code: 'EUR', name: 'Euro',        flag: '🇪🇺' },
            { code: 'JPY', name: 'Yen Jepang',  flag: '🇯🇵' },
            { code: 'SGD', name: 'Dolar SG',    flag: '🇸🇬' },
            { code: 'CNY', name: 'Yuan China',  flag: '🇨🇳' },
        ].forEach(item => {
            const rate = rates[item.code];
            el.innerHTML += `
                <div class="rate-card d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span style="font-size:1.1rem;">${item.flag}</span>
                        <div>
                            <span class="fw-bold text-dark small">${item.code}</span>
                            <span class="text-secondary d-block" style="font-size:.7rem;">${item.name}</span>
                        </div>
                    </div>
                    <span class="fw-bold text-success small">${fmtRupiah(rate)}</span>
                </div>`;
        });
    }).catch(() => {
        document.getElementById('exchange-rates').innerHTML = '<span class="text-secondary small">Gagal memuat kurs.</span>';
    });
}

function loadRiskHistory() {
    window.SupplyChainAPI.fetch('v1/risk/history').then(res => {
        const list = Array.isArray(res.data) ? res.data : [];
        const el   = document.getElementById('recent-activities');
        el.innerHTML = '<div class="timeline-line"></div>';
        if (!list.length) { el.innerHTML += '<span class="text-secondary small">Belum ada aktivitas.</span>'; return; }
        list.slice(0, 4).forEach(item => {
            const score = parseFloat(item.score || 0);
            const color = riskColor(score);
            el.innerHTML += `
                <div class="position-relative mb-3" style="padding-left:18px;">
                    <div class="timeline-dot" style="background:${color};"></div>
                    <span class="fw-bold text-dark d-block small">${item.country_name}</span>
                    <span class="text-secondary d-block" style="font-size:.72rem;">
                        Skor: <b>${score.toFixed(1)}</b> · ${item.level}
                    </span>
                    <span class="text-secondary" style="font-size:.65rem;">
                        <i class="bi bi-clock me-1"></i>${item.time_ago}
                    </span>
                </div>`;
        });
    }).catch(() => {
        document.getElementById('recent-activities').innerHTML = '<span class="text-secondary small">Gagal memuat log.</span>';
    });
}

function loadCountrySelector() {
    const sel = document.getElementById('country-intelligence-selector');
    if (sel && sel.options.length <= 1) {
        window.SupplyChainAPI.fetch('v1/countries').then(res => {
            (res.data || []).forEach(c => {
                const opt = new Option(`${c.name} (${c.code})`, c.id);
                sel.appendChild(opt);
            });
        }).catch(() => {});
    }
}

function onCountrySelect(id) {
    if (!id) { loadDashboardData(); return; }
    // just reload with global data for now — intelligence endpoint can be added later
    loadDashboardData();
}

// ── MAIN LOAD ─────────────────────────────────────────
function loadDashboardData() {
    loadKPI();
    loadTopRisk();
    loadLowestRisk();
    loadPorts();
    loadNews();
    loadExchangeRate();
    loadRiskHistory();
}

function refreshDashboardData() {
    const btn = document.querySelector('.btn-refresh-all');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Memuat...'; }
    loadDashboardData();
    setTimeout(() => {
        if (btn) { btn.disabled = false; btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Dasbor'; }
    }, 2500);
}

// ── INIT ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    initMap();
    loadCountrySelector();
    loadDashboardData();
});
</script>
@endsection
