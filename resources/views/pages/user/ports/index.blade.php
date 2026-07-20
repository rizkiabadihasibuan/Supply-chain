@extends('layouts.user.app')

@section('title', 'Global Ports Center – World Port Index | SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
/* ── Base ─────────────────────────────────────────────── */
.ports-hero { background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #0C4A6E 100%); border-radius: 20px; padding: 2rem 2.5rem; color: #fff; position: relative; overflow: hidden; }
.ports-hero::before { content: ''; position: absolute; top: -60px; right: -60px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(56,189,248,0.15) 0%, transparent 70%); border-radius: 50%; }
.ports-hero::after  { content: '⚓'; position: absolute; right: 2rem; bottom: 1rem; font-size: 7rem; opacity: 0.06; line-height: 1; }
.hero-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(56,189,248,0.15); border: 1px solid rgba(56,189,248,0.3); border-radius: 50px; padding: 4px 14px; font-size: .75rem; font-weight: 600; color: #38BDF8; margin-bottom: 1rem; }
.hero-title { font-size: 2rem; font-weight: 800; margin-bottom: .4rem; }
.hero-sub   { font-size: .9rem; color: rgba(255,255,255,.6); }

/* ── KPI Cards ────────────────────────────────────────── */
.kpi-port { background: #fff; border-radius: 16px; border: 1px solid #E2E8F0; padding: 1.4rem 1.6rem; position: relative; overflow: hidden; transition: box-shadow .2s, transform .2s; }
.kpi-port:hover { box-shadow: 0 8px 30px rgba(0,0,0,.08); transform: translateY(-2px); }
.kpi-port .kpi-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
.kpi-port .kpi-val  { font-size: 2rem; font-weight: 800; line-height: 1.1; }
.kpi-port .kpi-lbl  { font-size: .78rem; color: #64748B; font-weight: 500; margin-top: 2px; }

/* ── Search & Filter ──────────────────────────────────── */
.search-bar { background: #fff; border-radius: 16px; border: 1px solid #E2E8F0; padding: 1.2rem 1.4rem; }
.search-input-wrap { position: relative; }
.search-input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 1rem; pointer-events: none; }
.search-input-wrap input { padding-left: 42px; border-radius: 10px; border: 1px solid #E2E8F0; background: #F8FAFC; height: 44px; font-size: .9rem; }
.search-input-wrap input:focus { border-color: #2563EB; box-shadow: 0 0 0 3px rgba(37,99,235,.1); background: #fff; }

/* ── Map ──────────────────────────────────────────────── */
.map-card { background: #fff; border-radius: 20px; border: 1px solid #E2E8F0; overflow: hidden; }
.map-header { padding: 1.2rem 1.6rem; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between; }
#port-map-wrapper { position: relative; height: 520px; }
#port-map { position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; }
.map-legend { position: absolute; bottom: 12px; left: 12px; z-index: 1000; background: rgba(255,255,255,.95); border-radius: 12px; border: 1px solid #E2E8F0; padding: 10px 14px; font-size: .74rem; pointer-events: none; backdrop-filter: blur(4px); }
.legend-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 5px; }

/* ── Port Table Card ──────────────────────────────────── */
.table-card { background: #fff; border-radius: 20px; border: 1px solid #E2E8F0; }
.table-card-header { padding: 1.2rem 1.6rem; border-bottom: 1px solid #F1F5F9; }
.port-table th { font-size: .75rem; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; border: 0; background: #F8FAFC; padding: .85rem 1rem; }
.port-table td { font-size: .86rem; color: #334155; vertical-align: middle; border-bottom: 1px solid #F1F5F9; padding: .8rem 1rem; }
.port-table tr:hover td { background: #F8FAFC; }
.port-table tr:last-child td { border-bottom: 0; }
.size-badge { display: inline-flex; align-items: center; gap: 4px; border-radius: 20px; padding: 3px 10px; font-size: .72rem; font-weight: 600; }
.size-badge.large  { background: #DBEAFE; color: #1D4ED8; }
.size-badge.medium { background: #FEF3C7; color: #B45309; }
.size-badge.small  { background: #F1F5F9; color: #475569; }

/* ── Detail Panel ─────────────────────────────────────── */
.detail-panel { background: #fff; border-radius: 20px; border: 1px solid #E2E8F0; transition: all .3s ease; }
.detail-panel.collapsed { display: none; }
.detail-header { background: linear-gradient(135deg, #1E3A5F, #0C4A6E); color: #fff; border-radius: 20px 20px 0 0; padding: 1.4rem 1.6rem; }
.detail-stat-box { background: #F8FAFC; border-radius: 12px; padding: 12px 14px; border: 1px solid #E2E8F0; }

/* ── Continent Summary Bars ───────────────────────────── */
.continent-bar { height: 6px; border-radius: 3px; background: #E2E8F0; overflow: hidden; margin-top: 4px; }
.continent-fill { height: 100%; border-radius: 3px; transition: width .6s ease; }

/* ── Pagination ───────────────────────────────────────── */
.page-count { font-size: .82rem; color: #64748B; }
.pagination .page-link { border-radius: 8px !important; margin: 0 2px; border-color: #E2E8F0; color: #334155; font-size: .84rem; }
.pagination .page-item.active .page-link { background: #2563EB; border-color: #2563EB; color: #fff; }

/* ── Fade Anim ────────────────────────────────────────── */
.fade-in-up { animation: fadeInUp .45s ease both; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection

@section('content')
<div class="container-fluid p-0 fade-in-up">

    {{-- ── HERO ── --}}
    <div class="ports-hero mb-4">
        <div class="hero-badge"><i class="bi bi-anchor me-1"></i> World Port Index Dataset – NGA</div>
        <h1 class="hero-title"><i class="bi bi-anchor text-info me-2"></i>Global Ports Center</h1>
        <p class="hero-sub">Pantau {{ $totalPorts }} pelabuhan utama dunia dari dataset World Port Index (WPI).<br>Data mencakup nama pelabuhan, lokasi koordinat, dan negara.</p>
        <div class="d-flex gap-3 mt-3 flex-wrap">
            <div class="d-flex align-items-center gap-2 text-white-50 small">
                <i class="bi bi-database-fill text-info"></i> Sumber: NGA World Port Index (WPI)
            </div>
            <div class="d-flex align-items-center gap-2 text-white-50 small">
                <i class="bi bi-geo-alt-fill text-info"></i> Koordinat GPS aktual dari dataset publik
            </div>
        </div>
    </div>

    {{-- ── KPI CARDS ── --}}
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="kpi-port">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="kpi-val text-dark">{{ $totalPorts }}</div>
                        <div class="kpi-lbl">Total Pelabuhan Terdaftar</div>
                        <div class="mt-2 small text-secondary"><i class="bi bi-globe2 me-1 text-primary"></i>Dari {{ $byContinent->count() }} benua</div>
                    </div>
                    <div class="kpi-icon" style="background:#DBEAFE;color:#1D4ED8"><i class="bi bi-anchor"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="kpi-port">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="kpi-val text-primary">{{ $largePorts }}</div>
                        <div class="kpi-lbl">Pelabuhan Besar (Large)</div>
                        <div class="mt-2 small text-secondary"><i class="bi bi-building me-1 text-primary"></i>Hub utama rantai pasok global</div>
                    </div>
                    <div class="kpi-icon" style="background:#DBEAFE;color:#2563EB"><i class="bi bi-buildings"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="kpi-port">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="kpi-val text-warning">{{ $mediumPorts }}</div>
                        <div class="kpi-lbl">Pelabuhan Sedang (Medium)</div>
                        <div class="mt-2 small text-secondary"><i class="bi bi-arrow-left-right me-1 text-warning"></i>Hub regional & feeder</div>
                    </div>
                    <div class="kpi-icon" style="background:#FEF3C7;color:#D97706"><i class="bi bi-tsunami"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="kpi-port">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <div class="kpi-val text-secondary">{{ $smallPorts }}</div>
                        <div class="kpi-lbl">Pelabuhan Kecil (Small)</div>
                        <div class="mt-2 small text-secondary"><i class="bi bi-arrow-down-up me-1 text-secondary"></i>Pelabuhan lokal & khusus</div>
                    </div>
                    <div class="kpi-icon" style="background:#F1F5F9;color:#64748B"><i class="bi bi-water"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SEARCH & FILTER (Matches Weather & Countries reference design) ── --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <div class="search-input-wrap w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="port-search" class="form-control w-100" placeholder="Cari nama pelabuhan, kode, atau negara..." oninput="filterPorts()">
                        </div>
                    </div>

                    <!-- Filter Ukuran -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-size" class="form-select" style="min-height: 44px;" onchange="filterPorts()">
                            <option value="">Semua Ukuran</option>
                            <option value="Large">Besar (Large)</option>
                            <option value="Medium">Sedang (Medium)</option>
                            <option value="Small">Kecil (Small)</option>
                        </select>
                    </div>

                    <!-- Filter Benua -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-continent" class="form-select" style="min-height: 44px;" onchange="filterPorts()">
                            <option value="">Semua Benua</option>
                            @foreach($byContinent->keys() as $cont)
                                <option value="{{ $cont }}">{{ $cont }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tipe -->
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <select id="filter-type" class="form-select" style="min-height: 44px;" onchange="filterPorts()">
                            <option value="">Semua Tipe</option>
                            <option value="Seaport">Seaport</option>
                        </select>
                    </div>
                </div>

                <!-- Footer status info and reset -->
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <span class="text-secondary small fw-medium" id="filter-count">
                        <i class="bi bi-anchor text-primary me-1"></i> Menampilkan <strong>{{ $totalPorts }}</strong> dari {{ $totalPorts }} pelabuhan
                    </span>
                    <button class="btn btn-sm btn-light text-secondary border px-3" onclick="resetFilters()" title="Reset Filter" style="min-height:36px;">
                        <i class="bi bi-x-circle me-1"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── MAIN CONTENT: MAP + TABLE ── --}}
    <div class="row g-4 mb-4">

        {{-- MAP --}}
        <div class="col-12">
            <div class="map-card">
                <div class="map-header">
                    <div>
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-map-fill text-primary me-2"></i>Peta Jaringan Pelabuhan Global</h6>
                        <small class="text-secondary">Klik marker untuk detail pelabuhan. Data koordinat dari World Port Index (NGA).</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="portMap.setView([20,0],2)" title="Reset Zoom"><i class="bi bi-fullscreen-exit"></i></button>
                        <button class="btn btn-sm btn-primary" onclick="document.getElementById('port-table-section').scrollIntoView({behavior:'smooth'})">
                            <i class="bi bi-table me-1"></i>Lihat Tabel
                        </button>
                    </div>
                </div>
                <div id="port-map-wrapper">
                    <div id="port-map"></div>
                    <div class="map-legend">
                        <div class="fw-bold mb-1" style="font-size:.73rem;">Ukuran Pelabuhan</div>
                        <div><span class="legend-dot" style="background:#1D4ED8;"></span> Large (Hub Utama)</div>
                        <div><span class="legend-dot" style="background:#D97706;"></span> Medium (Regional)</div>
                        <div><span class="legend-dot" style="background:#94A3B8;"></span> Small (Lokal)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONTINENT SUMMARY + PORT DETAIL PANEL --}}
        <div class="col-xl-4">

            {{-- Continent Breakdown --}}
            <div class="table-card p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Distribusi per Benua</h6>
                @php $maxCount = $byContinent->max() ?: 1; @endphp
                @foreach($byContinent->sortDesc() as $continent => $count)
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold text-dark">{{ $continent }}</span>
                        <span class="small text-secondary fw-bold">{{ $count }} pelabuhan</span>
                    </div>
                    <div class="continent-bar">
                        <div class="continent-fill" style="width:{{ round($count/$maxCount*100) }}%;background:{{ ['Asia'=>'#3B82F6','Europe'=>'#8B5CF6','Americas'=>'#10B981','Africa'=>'#F59E0B','Oceania'=>'#06B6D4'][$continent] ?? '#94A3B8' }};"></div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Detail Panel --}}
            <div class="detail-panel collapsed" id="port-detail-panel">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size:.72rem;opacity:.7;" id="dp-code"></div>
                            <div class="fw-bold fs-6 mt-1" id="dp-name">—</div>
                        </div>
                        <button class="btn btn-sm text-white" onclick="closePortDetail()" style="opacity:.7;"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="detail-stat-box">
                                <div class="small text-secondary mb-1">Negara</div>
                                <div class="fw-bold small" id="dp-country">—</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-stat-box">
                                <div class="small text-secondary mb-1">Ukuran</div>
                                <div class="fw-bold small" id="dp-size">—</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-stat-box">
                                <div class="small text-secondary mb-1">Tipe Pelabuhan</div>
                                <div class="fw-bold small" id="dp-type">—</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-stat-box">
                                <div class="small text-secondary mb-1">Tipe Dermaga</div>
                                <div class="fw-bold small" id="dp-harbor">—</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-stat-box">
                                <div class="small text-secondary mb-1">Koordinat GPS</div>
                                <div class="fw-bold small font-monospace" id="dp-coords">—</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PORT TABLE --}}
        <div class="col-xl-8" id="port-table-section">
            <div class="table-card">
                <div class="table-card-header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-anchor text-primary me-2"></i>Daftar Pelabuhan</h6>
                    <span class="badge bg-primary rounded-pill" id="badge-count">{{ $totalPorts }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table port-table mb-0" id="port-table">
                        <thead>
                            <tr>
                                <th>Kode WPI</th>
                                <th>Nama Pelabuhan</th>
                                <th>Negara</th>
                                <th>Benua</th>
                                <th>Ukuran</th>
                                <th>Tipe Dermaga</th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        <tbody id="port-table-body">
                            @foreach($ports as $port)
                            <tr class="port-row"
                                data-name="{{ strtolower($port->name) }}"
                                data-code="{{ strtolower($port->code) }}"
                                data-country="{{ strtolower($port->country?->name ?? '') }}"
                                data-size="{{ $port->size }}"
                                data-type="{{ $port->type }}"
                                data-continent="{{ $port->country?->region?->name ?? '' }}"
                                data-lat="{{ $port->latitude }}"
                                data-lng="{{ $port->longitude }}"
                                data-id="{{ $port->id }}"
                            >
                                <td><code class="text-primary fw-bold" style="font-size:.78rem;">{{ $port->code }}</code></td>
                                <td>
                                    <div class="fw-semibold">{{ $port->name }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://flagcdn.com/20x15/{{ strtolower($port->country?->code ?? 'un') }}.png"
                                             onerror="this.style.display='none'"
                                             width="20" height="15" class="rounded-1" style="object-fit:cover;">
                                        <span>{{ $port->country?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary small">{{ $port->country?->region?->name ?? '—' }}</span>
                                </td>
                                <td>
                                    @php $sz = strtolower($port->size ?? 'small'); @endphp
                                    <span class="size-badge {{ $sz }}">
                                        <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>
                                        {{ $port->size ?? '—' }}
                                    </span>
                                </td>
                                <td class="text-secondary small">{{ $port->harbor_type ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary"
                                            onclick="showPortDetail({{ $port->id }})"
                                            style="border-radius:8px;padding:3px 10px;font-size:.75rem;"
                                            title="Detail pelabuhan">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination controls --}}
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                    <span class="page-count" id="page-info">Halaman 1</span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" id="btn-prev" onclick="changePage(-1)" disabled><i class="bi bi-chevron-left"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" id="btn-next" onclick="changePage(1)"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── PORT DETAIL MODAL ── --}}
<div class="modal fade" id="portModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px;border:0;">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#1E3A5F,#0C4A6E);color:#fff;border-radius:20px 20px 0 0;">
                <div>
                    <small class="opacity-50 d-block" id="modal-code"></small>
                    <h5 class="modal-title fw-bold mb-0" id="modal-name"></h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="detail-stat-box">
                            <div class="small text-secondary mb-1"><i class="bi bi-flag me-1"></i>Negara</div>
                            <div class="fw-bold" id="modal-country"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-stat-box">
                            <div class="small text-secondary mb-1"><i class="bi bi-building me-1"></i>Ukuran</div>
                            <div class="fw-bold" id="modal-size"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-stat-box">
                            <div class="small text-secondary mb-1"><i class="bi bi-anchor me-1"></i>Tipe Pelabuhan</div>
                            <div class="fw-bold" id="modal-type"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="detail-stat-box">
                            <div class="small text-secondary mb-1"><i class="bi bi-water me-1"></i>Tipe Dermaga</div>
                            <div class="fw-bold" id="modal-harbor"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-stat-box">
                            <div class="small text-secondary mb-1"><i class="bi bi-geo-alt me-1"></i>Koordinat GPS (World Port Index)</div>
                            <div class="fw-bold font-monospace" id="modal-coords"></div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <small class="text-secondary"><i class="bi bi-database me-1"></i>Sumber data: NGA World Port Index (WPI) – Public Domain</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
// ── PORT DATA FROM PHP ─────────────────────────────────
const PORT_DATA = @json($portJsonData);

// ── MAP INIT ───────────────────────────────────────────
let portMap  = null;
let clusters = null;
let allMarkers = [];

function colorForSize(size) {
    if (size === 'Large')  return '#1D4ED8';
    if (size === 'Medium') return '#D97706';
    return '#94A3B8';
}

function initPortMap() {
    requestAnimationFrame(() => requestAnimationFrame(() => {
        portMap = L.map('port-map', {
            zoomControl: true,
            worldCopyJump: true,
            preferCanvas: true,
        }).setView([20, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(portMap);

        clusters = L.markerClusterGroup({
            chunkedLoading: true,
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
        });
        portMap.addLayer(clusters);

        // Plot all ports
        PORT_DATA.forEach(p => {
            const color = colorForSize(p.size);
            const marker = L.circleMarker([p.lat, p.lng], {
                radius: p.size === 'Large' ? 9 : p.size === 'Medium' ? 7 : 5,
                fillColor: color,
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.85,
            });

            marker.bindPopup(`
                <div style="min-width:200px;">
                    <div style="font-weight:700;font-size:.9rem;margin-bottom:4px;">${p.name}</div>
                    <div style="color:#64748B;font-size:.8rem;margin-bottom:8px;">
                        <img src="https://flagcdn.com/16x12/${p.countryCode}.png" onerror="this.style.display='none'" width="16" style="border-radius:2px;margin-right:4px;">
                        ${p.country} · ${p.continent}
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <span style="background:${color}22;color:${color};border-radius:20px;padding:2px 8px;font-size:.72rem;font-weight:600;">${p.size}</span>
                        <span style="background:#F1F5F9;border-radius:20px;padding:2px 8px;font-size:.72rem;">${p.type}</span>
                    </div>
                    <div style="margin-top:8px;font-size:.72rem;color:#94A3B8;font-family:monospace;">
                        ${p.lat.toFixed(4)}°, ${p.lng.toFixed(4)}°
                    </div>
                    <div style="margin-top:8px;">
                        <strong style="font-size:.72rem;color:#334155;">Kode WPI: ${p.code}</strong>
                    </div>
                </div>
            `, { maxWidth: 260 });

            marker.on('click', () => showPortDetail(p.id));
            marker._portId = p.id;
            clusters.addLayer(marker);
            allMarkers.push({ marker, portId: p.id, size: p.size, continent: p.continent });
        });

        // Invalidate after init
        setTimeout(() => portMap.invalidateSize(true), 100);
        setTimeout(() => portMap.invalidateSize(true), 500);

        if (window.ResizeObserver) {
            new ResizeObserver(() => portMap && portMap.invalidateSize(true))
                .observe(document.getElementById('port-map-wrapper'));
        }
    }));
}

// ── PORT DETAIL ────────────────────────────────────────
function showPortDetail(id) {
    const p = PORT_DATA.find(x => x.id == id);
    if (!p) return;

    document.getElementById('modal-code').textContent    = 'Kode WPI: ' + p.code;
    document.getElementById('modal-name').textContent    = p.name;
    document.getElementById('modal-country').innerHTML   = `<img src="https://flagcdn.com/20x15/${p.countryCode}.png" onerror="this.style.display='none'" width="20" class="me-1 rounded-1"> ${p.country}`;
    document.getElementById('modal-size').textContent    = p.size;
    document.getElementById('modal-type').textContent    = p.type;
    document.getElementById('modal-harbor').textContent  = p.harbor;
    document.getElementById('modal-coords').textContent  = `${p.lat.toFixed(4)}°N, ${p.lng.toFixed(4)}°E`;

    new bootstrap.Modal(document.getElementById('portModal')).show();

    // Also fly to on map
    if (portMap) {
        portMap.flyTo([p.lat, p.lng], 6, { duration: 1.2 });
    }
}

function closePortDetail() {
    document.getElementById('port-detail-panel').classList.add('collapsed');
}

// ── FILTER & SEARCH ────────────────────────────────────
let currentPage = 1;
const PAGE_SIZE = 20;
let filteredRows = [];

function filterPorts() {
    const q    = document.getElementById('port-search').value.toLowerCase().trim();
    const size = document.getElementById('filter-size').value;
    const type = document.getElementById('filter-type').value;
    const cont = document.getElementById('filter-continent').value;

    const rows = Array.from(document.querySelectorAll('.port-row'));
    filteredRows = rows.filter(row => {
        const matchQ    = !q    || row.dataset.name.includes(q) || row.dataset.code.includes(q) || row.dataset.country.includes(q);
        const matchSize = !size || row.dataset.size === size;
        const matchType = !type || row.dataset.type === type;
        const matchCont = !cont || row.dataset.continent === cont;
        return matchQ && matchSize && matchType && matchCont;
    });

    currentPage = 1;
    renderPage();
    updateMapFilter(filteredRows);

    const total = rows.length;
    document.getElementById('filter-count').innerHTML =
        `<i class="bi bi-anchor text-primary me-1"></i> Menampilkan <strong>${filteredRows.length}</strong> dari ${total} pelabuhan`;
    document.getElementById('badge-count').textContent = filteredRows.length;
}

function renderPage() {
    const allRows = Array.from(document.querySelectorAll('.port-row'));
    allRows.forEach(r => r.style.display = 'none');

    const start = (currentPage - 1) * PAGE_SIZE;
    const pageRows = filteredRows.slice(start, start + PAGE_SIZE);
    pageRows.forEach(r => r.style.display = '');

    const totalPages = Math.ceil(filteredRows.length / PAGE_SIZE);
    document.getElementById('page-info').textContent =
        `Halaman ${currentPage} / ${totalPages || 1} (${filteredRows.length} hasil)`;
    document.getElementById('btn-prev').disabled = currentPage <= 1;
    document.getElementById('btn-next').disabled = currentPage >= totalPages;
}

function changePage(dir) {
    currentPage += dir;
    renderPage();
    document.getElementById('port-table-section').scrollIntoView({ behavior: 'smooth' });
}

function resetFilters() {
    document.getElementById('port-search').value = '';
    document.getElementById('filter-size').value = '';
    document.getElementById('filter-type').value = '';
    document.getElementById('filter-continent').value = '';
    filterPorts();
}

function updateMapFilter(visibleRows) {
    if (!clusters) return;
    const visibleIds = new Set(visibleRows.map(r => parseInt(r.dataset.id)));
    clusters.clearLayers();
    allMarkers.forEach(({ marker, portId }) => {
        if (visibleIds.size === 0 || visibleIds.has(portId)) {
            clusters.addLayer(marker);
        }
    });
}

// ── BOOT ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Init all rows as filtered
    filteredRows = Array.from(document.querySelectorAll('.port-row'));
    renderPage();
    initPortMap();
});
</script>
@endsection
