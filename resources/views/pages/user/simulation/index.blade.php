@extends('layouts.user.app')

@section('title', 'Simulasi Pengiriman Paket Maritim - SupplyChain')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<style>
    .sim-card-gradient {
        background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
        color: #FFFFFF;
    }
    
    .map-container {
        position: relative;
        width: 100%;
        height: 540px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(18, 52, 88, 0.12);
        border: 1px solid var(--border-color, #E2E8F0);
    }
    
    #simulation-map {
        width: 100%;
        height: 100%;
        background-color: #0F172A;
    }

    .map-overlay-controls {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 1000;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 10px 16px;
        color: #FFFFFF;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .ship-marker-icon {
        background: transparent;
        border: none;
    }

    .ship-symbol-wrapper {
        font-size: 28px;
        filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.9));
        transition: transform 0.2s ease-out;
        display: inline-block;
        line-height: 1;
    }

    .port-pulse-marker {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        position: relative;
        border: 3px solid #FFFFFF;
        box-shadow: 0 0 12px rgba(0,0,0,0.3);
    }

    .port-origin {
        background-color: #22C55E;
        box-shadow: 0 0 14px #22C55E;
    }

    .port-dest {
        background-color: #2563EB;
        box-shadow: 0 0 14px #2563EB;
    }

    .timeline-steps {
        position: relative;
        padding-left: 24px;
    }

    .timeline-steps::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background-color: #E2E8F0;
    }

    .timeline-step-item {
        position: relative;
        margin-bottom: 24px;
    }

    .timeline-step-dot {
        position: absolute;
        left: -24px;
        top: 4px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #CBD5E1;
        border: 3px solid #FFFFFF;
        box-shadow: 0 0 0 2px #E2E8F0;
        transition: all 0.3s;
    }

    .timeline-step-item.active .timeline-step-dot {
        background-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
        animation: pulse-ring 1.5s infinite;
    }

    .timeline-step-item.completed .timeline-step-dot {
        background-color: #22C55E;
        box-shadow: 0 0 0 2px #22C55E;
    }

    .console-log-box {
        background-color: #0F172A;
        color: #38BDF8;
        font-family: 'Consolas', 'Courier New', Courier, monospace;
        font-size: 0.82rem;
        border-radius: 12px;
        height: 175px;
        overflow-y: auto;
        padding: 12px;
        border: 1px solid #1E293B;
    }

    .speed-btn.active {
        background-color: #2563EB !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="bi bi-box-seam-fill text-primary me-2"></i>Simulasi Pengiriman Paket Maritim
            </h3>
            <p class="text-secondary mb-0">Simulasikan perjalanan kapal kargo pembawa paket Anda dari negara asal dan lihat perhentian tepat di pelabuhan tujuan.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill d-flex align-items-center">
                <span class="pulse-indicator me-2"></span>Live Port Simulator Active
            </span>
        </div>
    </div>

    <!-- Selection & Config Form Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-sliders me-2 text-primary"></i>Pengaturan Rute & Kargo Paket</h5>
            
            <form id="form-simulation-config" onsubmit="return false;">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="origin_country_id" class="form-label fw-semibold text-secondary">Negara Asal (Pengirim)</label>
                        <select class="form-select" id="origin_country_id" required>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" {{ strtolower($c->code) === 'cn' ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ strtoupper($c->code) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="destination_country_id" class="form-label fw-semibold text-secondary">Negara Tujuan (Penerima/Kita)</label>
                        <select class="form-select" id="destination_country_id" required>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" {{ strtolower($c->code) === 'id' ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ strtoupper($c->code) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="package_name" class="form-label fw-semibold text-secondary">Nama Paket / Deskripsi</label>
                        <input type="text" class="form-control" id="package_name" value="Paket Logistik Microchip #892" placeholder="Contoh: Sparepart Elektronik">
                    </div>

                    <div class="col-md-3">
                        <label for="cargo_type" class="form-label fw-semibold text-secondary">Jenis Kargo</label>
                        <select class="form-select" id="cargo_type">
                            <option value="Container Dry Goods" selected>Kontainer Standard (Dry Goods)</option>
                            <option value="Refrigerated Electronics">Kontainer Pendingin (Elektronik)</option>
                            <option value="Liquid Freight">Kargo Cair (Liquid Cargo)</option>
                            <option value="Heavy Machinery">Mesin & Alat Berat</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" id="btn-start-sim" class="btn btn-primary fw-semibold px-4 py-2 shadow-sm">
                                <i class="bi bi-play-circle-fill me-2 align-middle"></i> Mulai Simulasi Paket
                            </button>
                            <button type="button" id="btn-pause-sim" class="btn btn-warning fw-semibold px-3 py-2 d-none shadow-sm">
                                <i class="bi bi-pause-circle-fill me-1 align-middle"></i> Jeda
                            </button>
                            <button type="button" id="btn-reset-sim" class="btn btn-outline-secondary fw-semibold px-3 py-2 d-none shadow-sm">
                                <i class="bi bi-arrow-counterclockwise me-1 align-middle"></i> Reset
                            </button>
                        </div>

                        <!-- Speed Controls -->
                        <div class="d-flex align-items-center gap-1 bg-light p-1 rounded-3 border">
                            <span class="text-xs text-muted me-2 ms-2 fw-semibold">Kecepatan Simulasi:</span>
                            <button type="button" class="btn btn-sm btn-light speed-btn active" data-speed="1">1x</button>
                            <button type="button" class="btn btn-sm btn-light speed-btn" data-speed="2">2x</button>
                            <button type="button" class="btn btn-sm btn-light speed-btn" data-speed="5">5x</button>
                            <button type="button" class="btn btn-sm btn-light speed-btn" data-speed="10">10x</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Real-time Stat Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-xs text-uppercase fw-bold text-muted mb-1">Nomor Resi / Tracking ID</div>
                    <h6 class="fw-bold text-primary mb-2" id="stat-tracking-id">SP-LOG-WAITING</h6>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary" id="stat-status-badge">Menunggu Simulasi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-xs text-uppercase fw-bold text-muted mb-1">Lintasan Pelabuhan</div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-semibold text-dark text-truncate" id="stat-origin-port" style="max-width: 110px;">—</span>
                        <i class="bi bi-arrow-right text-primary"></i>
                        <span class="fw-semibold text-dark text-truncate" id="stat-dest-port" style="max-width: 110px;">—</span>
                    </div>
                    <div class="text-xs text-muted" id="stat-country-route">Pilih Negara Asal & Tujuan</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-xs text-uppercase fw-bold text-muted mb-1">Estimasi Jarak & Waktu</div>
                    <h6 class="fw-bold text-dark mb-1" id="stat-distance">— NM / — KM</h6>
                    <div class="text-xs text-muted" id="stat-duration">Est. Pelayaran: — jam</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-xs text-uppercase fw-bold text-muted mb-1">Risiko Navigasi Laut</div>
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="fw-bold text-dark mb-0" id="stat-risk-score">— / 10</h6>
                        <span class="badge bg-secondary" id="stat-risk-level">—</span>
                    </div>
                    <div class="text-xs text-muted mt-1">Status Keamanan Maritim</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Map & Progress Section -->
    <div class="row g-4">
        <!-- Interactive Map Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="map-container">
                        <div id="simulation-map"></div>
                        <div class="map-overlay-controls">
                            <div class="d-flex align-items-center gap-2 text-xs">
                                <i class="bi bi-compass-fill text-info fs-6"></i>
                                <span>Progress Perjalanan: <strong id="map-progress-pct" class="text-warning fs-6">0%</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Below Map -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between text-xs text-secondary mb-1">
                            <span><i class="bi bi-geo-alt-fill text-success me-1"></i>Pelabuhan Asal</span>
                            <span class="fw-semibold text-primary" id="progress-status-text">Menunggu Simulasi Pengiriman</span>
                            <span><i class="bi bi-flag-fill text-primary me-1"></i>Pelabuhan Tujuan</span>
                        </div>
                        <div class="progress" style="height: 12px; border-radius: 6px;">
                            <div id="ship-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline & Stages Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-list-check me-2 text-primary"></i>Tahapan Pengiriman
                    </h5>
                    <p class="text-xs text-muted mb-0">Status alur perjalanan paket per pelabuhan</p>
                </div>
                <div class="card-body p-4">
                    <div class="timeline-steps" id="timeline-container">
                        <!-- Step 1 -->
                        <div class="timeline-step-item active" id="step-1">
                            <div class="timeline-step-dot"></div>
                            <h6 class="fw-bold text-dark mb-1 fs-6" id="step1-title">1. Pemuatan di Pelabuhan Asal</h6>
                            <p class="text-xs text-muted mb-0" id="step1-desc">Paket dimasukkan ke dalam kontainer kargo dan diverifikasi oleh otoritas pelabuhan asal.</p>
                        </div>
                        <!-- Step 2 -->
                        <div class="timeline-step-item" id="step-2">
                            <div class="timeline-step-dot"></div>
                            <h6 class="fw-bold text-dark mb-1 fs-6" id="step2-title">2. Keberangkatan Kapal Kargo</h6>
                            <p class="text-xs text-muted mb-0" id="step2-desc">Kapal kargo mengangkat jangkar dan bertolak ke perairan terbuka.</p>
                        </div>
                        <!-- Step 3 -->
                        <div class="timeline-step-item" id="step-3">
                            <div class="timeline-step-dot"></div>
                            <h6 class="fw-bold text-dark mb-1 fs-6" id="step3-title">3. Pelayaran Laut Internasional</h6>
                            <p class="text-xs text-muted mb-0" id="step3-desc">Kapal melintasi titik navigasi maritim antar negara.</p>
                        </div>
                        <!-- Step 4 -->
                        <div class="timeline-step-item" id="step-4">
                            <div class="timeline-step-dot"></div>
                            <h6 class="fw-bold text-dark mb-1 fs-6" id="step4-title">4. Berlabuh di Pelabuhan Tujuan</h6>
                            <p class="text-xs text-muted mb-0" id="step4-desc">Kapal berlabuh tepat di dermaga pelabuhan tujuan untuk verifikasi Bea Cukai.</p>
                        </div>
                        <!-- Step 5 -->
                        <div class="timeline-step-item" id="step-5">
                            <div class="timeline-step-dot"></div>
                            <h6 class="fw-bold text-dark mb-1 fs-6" id="step5-title">5. Bongkar Muat & Paket Terkirim</h6>
                            <p class="text-xs text-muted mb-0" id="step5-desc">Paket berhasil dibongkar dan diserahkan kepada pihak penerima.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let map = null;
        let routePolyline = null;
        let originMarker = null;
        let destMarker = null;
        let shipMarker = null;

        let simulationData = null;
        let waypoints = [];
        let currentWaypointIndex = 0;
        let isSimulating = false;
        let isPaused = false;
        let simulationInterval = null;
        let simSpeed = 1; // 1x, 2x, 5x, 10x

        // Initialize Map
        function initMap() {
            map = L.map('simulation-map', {
                center: [15.0, 115.0],
                zoom: 4,
                zoomControl: true
            });

            // CartoDB Voyager Map Tiles
            L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 18
            }).addTo(map);
        }

        initMap();

        // Handle Speed Buttons
        document.querySelectorAll('.speed-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.speed-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                simSpeed = parseInt(this.getAttribute('data-speed')) || 1;
                
                logConsole(`[CONFIG] Kecepatan simulasi diubah menjadi ${simSpeed}x.`);
                if (isSimulating && !isPaused) {
                    restartAnimationLoop();
                }
            });
        });

        // Handle Form Submission / Start Simulation
        document.getElementById('btn-start-sim').addEventListener('click', function () {
            if (isPaused) {
                resumeSimulation();
                return;
            }
            startNewSimulation();
        });

        document.getElementById('btn-pause-sim').addEventListener('click', function () {
            pauseSimulation();
        });

        document.getElementById('btn-reset-sim').addEventListener('click', function () {
            resetSimulation();
        });

        document.getElementById('btn-clear-console')?.addEventListener('click', function () {
            const consoleBox = document.getElementById('console-log-body');
            if (consoleBox) consoleBox.innerHTML = '<div>[SYSTEM] Log dibersihkan.</div>';
        });

        // Start New Simulation Function
        async function startNewSimulation() {
            const originId = document.getElementById('origin_country_id').value;
            const destId = document.getElementById('destination_country_id').value;
            const packageName = document.getElementById('package_name').value || 'Paket Logistik Express';
            const cargoType = document.getElementById('cargo_type').value;

            if (!originId || !destId) {
                alert('Pilih Negara Asal dan Negara Tujuan.');
                return;
            }

            logConsole(`[API] Menghitung rute pengiriman paket maritim...`);

            try {
                const response = await window.SupplyChainAPI.fetch(`v1/shipping/route?origin_country_id=${originId}&destination_country_id=${destId}&package_name=${encodeURIComponent(packageName)}&cargo_type=${encodeURIComponent(cargoType)}`);
                
                if (response && response.success) {
                    simulationData = response.data;
                    setupSimulationUI(simulationData);
                    runShipAnimation();
                } else {
                    alert('Gagal mengambil data rute pengiriman.');
                }
            } catch (err) {
                console.error(err);
                logConsole(`[ERROR] Gagal memuat rute: ${err.message}`);
                alert('Terjadi kesalahan jaringan saat menghitung rute.');
            }
        }

        function setupSimulationUI(data) {
            // Update Stat Cards
            document.getElementById('stat-tracking-id').innerText = data.tracking_id;
            
            const statusBadge = document.getElementById('stat-status-badge');
            statusBadge.className = 'badge bg-warning text-dark';
            statusBadge.innerText = 'Dalam Pelayaran';

            document.getElementById('stat-origin-port').innerText = data.origin.port_name;
            document.getElementById('stat-dest-port').innerText = data.destination.port_name;
            document.getElementById('stat-country-route').innerText = `${data.origin.country_name} → ${data.destination.country_name}`;

            document.getElementById('stat-distance').innerText = `${data.route.distance_nm} NM (${data.route.distance_km} KM)`;
            document.getElementById('stat-duration').innerText = `Est. Pelayaran: ${data.route.estimated_hours} Jam (${data.route.estimated_days} Hari)`;

            document.getElementById('stat-risk-score').innerText = `${data.route.route_risk_score} / 10`;
            const riskBadge = document.getElementById('stat-risk-level');
            riskBadge.innerText = data.route.route_risk_level;
            if (data.route.route_risk_level === 'Tinggi') {
                riskBadge.className = 'badge bg-danger';
            } else if (data.route.route_risk_level === 'Sedang') {
                riskBadge.className = 'badge bg-warning text-dark';
            } else {
                riskBadge.className = 'badge bg-success';
            }

            // Update Timeline Texts
            if (data.stages && data.stages.length >= 5) {
                document.getElementById('step1-desc').innerText = data.stages[0].description;
                document.getElementById('step2-desc').innerText = data.stages[1].description;
                document.getElementById('step3-desc').innerText = data.stages[2].description;
                document.getElementById('step4-desc').innerText = data.stages[3].description;
                document.getElementById('step5-desc').innerText = data.stages[4].description;
            }

            // Clear existing map layers
            if (routePolyline) map.removeLayer(routePolyline);
            if (originMarker) map.removeLayer(originMarker);
            if (destMarker) map.removeLayer(destMarker);
            if (shipMarker) map.removeLayer(shipMarker);

            waypoints = data.route.waypoints;

            // Draw Route Polyline with bright cyan sea path
            routePolyline = L.polyline(waypoints, {
                color: '#0284C7',
                weight: 5,
                dashArray: '10, 10',
                opacity: 0.9,
                lineJoin: 'round'
            }).addTo(map);

            // Origin Marker
            const originIcon = L.divIcon({
                className: 'custom-port-icon',
                html: `<div class="port-pulse-marker port-origin" title="${data.origin.port_name}"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });
            originMarker = L.marker([data.origin.lat, data.origin.lng], { icon: originIcon })
                .bindPopup(`
                    <div class="p-1">
                        <span class="badge bg-success mb-1">Pelabuhan Asal</span>
                        <h6 class="fw-bold mb-0">${data.origin.port_name}</h6>
                        <small class="text-muted">${data.origin.country_name} (${data.origin.port_code})</small>
                    </div>
                `)
                .addTo(map);

            // Destination Marker
            const destIcon = L.divIcon({
                className: 'custom-port-icon',
                html: `<div class="port-pulse-marker port-dest" title="${data.destination.port_name}"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });
            destMarker = L.marker([data.destination.lat, data.destination.lng], { icon: destIcon })
                .bindPopup(`
                    <div class="p-1">
                        <span class="badge bg-primary mb-1">Pelabuhan Tujuan</span>
                        <h6 class="fw-bold mb-0">${data.destination.port_name}</h6>
                        <small class="text-muted">${data.destination.country_name} (${data.destination.port_code})</small>
                    </div>
                `)
                .addTo(map);

            // Ship Marker
            const shipIcon = L.divIcon({
                className: 'ship-marker-icon',
                html: `<div class="ship-symbol-wrapper" id="ship-icon-el">🚢</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });
            shipMarker = L.marker(waypoints[0], { icon: shipIcon }).addTo(map);
            shipMarker.bindPopup(`
                <div class="p-1">
                    <span class="badge bg-info text-dark mb-1">Kapal Kargo Express</span><br>
                    <b>Paket:</b> ${data.package_name}<br>
                    <b>Nomor Resi:</b> ${data.tracking_id}<br>
                    <b>Kargo:</b> ${data.cargo_type}
                </div>
            `);

            // Fit Map Bounds
            map.fitBounds(routePolyline.getBounds(), { padding: [60, 60] });

            // UI Controls Toggle
            document.getElementById('btn-start-sim').classList.add('d-none');
            document.getElementById('btn-pause-sim').classList.remove('d-none');
            document.getElementById('btn-reset-sim').classList.remove('d-none');

            logConsole(`[ROUTE] Resi ${data.tracking_id} dibuat. Lintasan: ${data.origin.port_name} ➔ ${data.destination.port_name}.`);
            logConsole(`[DEPARTURE] Paket dimuat di ${data.origin.port_name}. Kapal siap bertolak.`);
        }

        function runShipAnimation() {
            isSimulating = true;
            isPaused = false;
            currentWaypointIndex = 0;

            restartAnimationLoop();
        }

        function restartAnimationLoop() {
            if (simulationInterval) clearInterval(simulationInterval);

            // Base interval: 450ms per waypoint divided by simSpeed
            const intervalTime = Math.max(50, Math.round(450 / simSpeed));

            simulationInterval = setInterval(() => {
                if (isPaused || !waypoints.length) return;

                currentWaypointIndex++;
                if (currentWaypointIndex >= waypoints.length - 1) {
                    // REACHED EXACT DESTINATION PORT!
                    completeSimulation();
                    return;
                }

                const prevPos = waypoints[currentWaypointIndex - 1];
                const currentPos = waypoints[currentWaypointIndex];
                
                shipMarker.setLatLng(currentPos);

                // Rotate Ship Marker towards bearing angle
                const bearing = calculateBearing(prevPos[0], prevPos[1], currentPos[0], currentPos[1]);
                const shipEl = document.getElementById('ship-icon-el');
                if (shipEl) {
                    shipEl.style.transform = `rotate(${bearing}deg)`;
                }

                // Calculate Progress %
                const pct = Math.round((currentWaypointIndex / (waypoints.length - 1)) * 100);
                document.getElementById('map-progress-pct').innerText = `${pct}%`;
                document.getElementById('ship-progress-bar').style.width = `${pct}%`;

                updateTimelineStatus(pct);

                // Log Updates
                if (pct === 25) {
                    logConsole(`[STATUS 25%] Kapal bertolak dari perairan ${simulationData.origin.country_name}.`);
                } else if (pct === 50) {
                    logConsole(`[STATUS 50%] Kapal berlayar melintasi rute maritim laut lepas (Mid-journey).`);
                } else if (pct === 75) {
                    logConsole(`[STATUS 75%] Kapal mendekati perairan pelabuhan tujuan ${simulationData.destination.port_name}.`);
                }

            }, intervalTime);
        }

        function pauseSimulation() {
            isPaused = true;
            document.getElementById('btn-pause-sim').classList.add('d-none');
            document.getElementById('btn-start-sim').classList.remove('d-none');
            document.getElementById('btn-start-sim').innerHTML = '<i class="bi bi-play-circle-fill me-2 align-middle"></i> Lanjutkan';
            
            const statusBadge = document.getElementById('stat-status-badge');
            statusBadge.className = 'badge bg-secondary';
            statusBadge.innerText = 'Dijeda';
            logConsole(`[CONTROL] Simulasi pengiriman dijeda oleh pengguna.`);
        }

        function resumeSimulation() {
            isPaused = false;
            document.getElementById('btn-start-sim').classList.add('d-none');
            document.getElementById('btn-pause-sim').classList.remove('d-none');

            const statusBadge = document.getElementById('stat-status-badge');
            statusBadge.className = 'badge bg-warning text-dark';
            statusBadge.innerText = 'Dalam Pelayaran';
            
            logConsole(`[CONTROL] Simulasi pengiriman dilanjutkan.`);
        }

        function completeSimulation() {
            clearInterval(simulationInterval);
            isSimulating = false;

            if (!simulationData) return;

            // FORCE SHIP TO STOP EXACTLY AT DESTINATION PORT COORDINATES!
            const destLat = simulationData.destination.lat;
            const destLng = simulationData.destination.lng;
            shipMarker.setLatLng([destLat, destLng]);

            document.getElementById('map-progress-pct').innerText = `100%`;
            document.getElementById('ship-progress-bar').style.width = `100%`;
            document.getElementById('ship-progress-bar').className = 'progress-bar bg-success';

            const statusBadge = document.getElementById('stat-status-badge');
            statusBadge.className = 'badge bg-success';
            statusBadge.innerText = 'Tiba Tepat di Pelabuhan Tujuan';

            document.getElementById('progress-status-text').innerText = `Tiba Tepat di ${simulationData.destination.port_name}!`;

            updateTimelineStatus(100);

            document.getElementById('btn-pause-sim').classList.add('d-none');
            document.getElementById('btn-start-sim').classList.remove('d-none');
            document.getElementById('btn-start-sim').innerHTML = '<i class="bi bi-arrow-repeat me-2 align-middle"></i> Simulasi Ulang';

            // Pan map to destination port and open celebratory popup
            map.panTo([destLat, destLng], { animate: true, duration: 1.0 });

            const arrivalPopupContent = `
                <div class="text-center p-2" style="min-width: 200px;">
                    <div class="fs-4 text-success mb-1">🎉 <b>Paket Tiba!</b></div>
                    <div class="fw-bold text-dark fs-6 mb-1">${simulationData.destination.port_name}</div>
                    <div class="text-xs text-muted mb-2">${simulationData.destination.country_name} (${simulationData.destination.port_code})</div>
                    <div class="border-top pt-2 text-start text-xs">
                        <b>Resi:</b> ${simulationData.tracking_id}<br>
                        <b>Paket:</b> ${simulationData.package_name}<br>
                        <b>Status:</b> Terkirim & Selamat Sampai Tujuan
                    </div>
                </div>
            `;

            shipMarker.bindPopup(arrivalPopupContent).openPopup();

            logConsole(`[ARRIVED] ⚓ Kapal BERHENTI TEPAT di dermaga ${simulationData.destination.port_name} (${destLat}, ${destLng}).`);
            logConsole(`[SUCCESS] 🎉 Paket "${simulationData.package_name}" berhasil diserahkan kepada pihak penerima di ${simulationData.destination.country_name}.`);
        }

        function resetSimulation() {
            if (simulationInterval) clearInterval(simulationInterval);
            isSimulating = false;
            isPaused = false;
            currentWaypointIndex = 0;

            if (routePolyline) map.removeLayer(routePolyline);
            if (originMarker) map.removeLayer(originMarker);
            if (destMarker) map.removeLayer(destMarker);
            if (shipMarker) map.removeLayer(shipMarker);

            document.getElementById('map-progress-pct').innerText = `0%`;
            document.getElementById('ship-progress-bar').style.width = `0%`;
            document.getElementById('ship-progress-bar').className = 'progress-bar progress-bar-striped progress-bar-animated bg-primary';

            document.getElementById('stat-tracking-id').innerText = 'SP-LOG-WAITING';
            const statusBadge = document.getElementById('stat-status-badge');
            statusBadge.className = 'badge bg-secondary';
            statusBadge.innerText = 'Menunggu Simulasi';

            document.getElementById('stat-origin-port').innerText = '—';
            document.getElementById('stat-dest-port').innerText = '—';
            document.getElementById('stat-country-route').innerText = 'Pilih Negara Asal & Tujuan';

            document.getElementById('stat-distance').innerText = '— NM / — KM';
            document.getElementById('stat-duration').innerText = 'Est. Pelayaran: — jam';

            document.getElementById('btn-pause-sim').classList.add('d-none');
            document.getElementById('btn-reset-sim').classList.add('d-none');
            document.getElementById('btn-start-sim').classList.remove('d-none');
            document.getElementById('btn-start-sim').innerHTML = '<i class="bi bi-play-circle-fill me-2 align-middle"></i> Mulai Simulasi Paket';

            resetTimelineUI();
            logConsole(`[RESET] Simulasi direset ke posisi awal.`);
        }

        function updateTimelineStatus(pct) {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const step3 = document.getElementById('step-3');
            const step4 = document.getElementById('step-4');
            const step5 = document.getElementById('step-5');

            step1.className = 'timeline-step-item completed';

            if (pct >= 20) {
                step2.className = 'timeline-step-item completed';
            } else {
                step2.className = 'timeline-step-item active';
            }

            if (pct >= 50) {
                step3.className = 'timeline-step-item completed';
            } else if (pct >= 20) {
                step3.className = 'timeline-step-item active';
            }

            if (pct >= 85) {
                step4.className = 'timeline-step-item completed';
            } else if (pct >= 50) {
                step4.className = 'timeline-step-item active';
            }

            if (pct >= 100) {
                step5.className = 'timeline-step-item completed';
            } else if (pct >= 85) {
                step5.className = 'timeline-step-item active';
            }
        }

        function resetTimelineUI() {
            for (let i = 1; i <= 5; i++) {
                const step = document.getElementById(`step-${i}`);
                if (i === 1) {
                    step.className = 'timeline-step-item active';
                } else {
                    step.className = 'timeline-step-item';
                }
            }
        }

        function calculateBearing(lat1, lng1, lat2, lng2) {
            const rad = Math.PI / 180;
            const y = Math.sin((lng2 - lng1) * rad) * Math.cos(lat2 * rad);
            const x = Math.cos(lat1 * rad) * Math.sin(lat2 * rad) -
                Math.sin(lat1 * rad) * Math.cos(lat2 * rad) * Math.cos((lng2 - lng1) * rad);
            const brng = Math.atan2(y, x) * (180 / Math.PI);
            return (brng + 360) % 360;
        }

        function logConsole(msg) {
            const consoleBox = document.getElementById('console-log-body');
            if (!consoleBox) return;
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID');
            const line = document.createElement('div');
            line.innerHTML = `<span class="text-muted">[${timeStr}]</span> ${msg}`;
            consoleBox.appendChild(line);
            consoleBox.scrollTop = consoleBox.scrollHeight;
        }
    });
</script>
@endsection
