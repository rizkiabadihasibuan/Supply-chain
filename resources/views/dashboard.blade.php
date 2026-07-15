@extends('layouts.app')

@section('title', 'Dashboard Control Center')

@section('styles')
<!-- Leaflet Marker Cluster CSS CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    /* Glowing borders based on risk status */
    .border-glow-success {
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.15);
    }
    .border-glow-warning {
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        box-shadow: 0 0 15px rgba(245, 158, 11, 0.15);
    }
    .border-glow-danger {
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
    }
    .watchlist-btn {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-secondary);
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .watchlist-btn.watched {
        background: rgba(245, 158, 11, 0.1);
        border-color: var(--color-warning);
        color: var(--color-warning);
    }
    /* Spin animation for loading icons */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<!-- Control Center Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary border-opacity-25">
    <h1 class="h2 text-white">Dashboard Control Center</h1>
    <div class="btn-toolbar mb-2 mb-md-0 d-flex gap-2 align-items-center">
        <!-- Country Selector Dropdown -->
        <select id="dashboard-country-selector" class="form-select form-select-sm glass-card text-white py-1 px-3" style="width: 200px;">
            <option value="DE" selected>Germany (DE)</option>
            <option value="CN">China (CN)</option>
            <option value="ID">Indonesia (ID)</option>
            <option value="AU">Australia (AU)</option>
        </select>
        
        <!-- Watchlist Star Toggle -->
        <button type="button" id="toggle-watchlist-btn" class="btn btn-sm watchlist-btn px-2.5 py-1.5">
            <i class="bi bi-star-fill"></i>
        </button>

        <!-- Sync Single Country Button -->
        <button type="button" id="sync-country-btn" class="btn btn-sm btn-primary d-flex align-items-center py-1.5 px-3">
            <i class="bi bi-arrow-repeat me-1.5" id="sync-icon"></i> <span id="sync-btn-text">Sync Data</span>
        </button>

        <!-- Sync All Countries Button -->
        <button type="button" id="sync-all-countries-btn" class="btn btn-sm btn-outline-light d-flex align-items-center py-1.5 px-3 glass-card text-glow-cyan" style="border-color: rgba(56, 189, 248, 0.4);">
            <i class="bi bi-globe me-1.5" id="sync-all-icon"></i> <span id="sync-all-btn-text">Sync Country Data</span>
        </button>

        <!-- Sync Economic Data Button -->
        <button type="button" id="sync-economic-btn" class="btn btn-sm btn-outline-light d-flex align-items-center py-1.5 px-3 glass-card text-glow-purple" style="border-color: rgba(139, 92, 246, 0.4);">
            <i class="bi bi-currency-exchange me-1.5" id="sync-economic-icon"></i> <span id="sync-economic-btn-text">Sync Economic Data</span>
        </button>

        <!-- Sync Weather Button -->
        <button type="button" id="sync-weather-btn" class="btn btn-sm btn-outline-light d-flex align-items-center py-1.5 px-3 glass-card text-glow-warning" style="border-color: rgba(245, 158, 11, 0.4);">
            <i class="bi bi-cloud-sun me-1.5" id="sync-weather-icon"></i> <span id="sync-weather-btn-text">Sync Weather</span>
        </button>

        <!-- Sync Currency Button -->
        <button type="button" id="sync-currency-btn" class="btn btn-sm btn-outline-light d-flex align-items-center py-1.5 px-3 glass-card text-glow-success" style="border-color: rgba(16, 185, 129, 0.4);">
            <i class="bi bi-cash-coin me-1.5" id="sync-currency-icon"></i> <span id="sync-currency-btn-text">Sync Currency</span>
        </button>
    </div>
</div>

<!-- Welcome Alert and Watchlist summary -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between position-relative overflow-hidden h-100">
            <div class="position-relative" style="z-index: 2;">
                <h3 class="text-white">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-secondary mb-0">Platform aktif mengawasi rantai pasokan global Anda. Semua sensor API berjalan normal.</p>
            </div>
            <div class="d-none d-lg-block text-glow-cyan" style="font-size: 4rem; z-index: 1; opacity: 0.12; transform: rotate(-10deg);">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 h-100">
            <h6 class="text-white mb-2"><i class="bi bi-star text-glow-purple me-1.5"></i> Watchlist Saya</h6>
            <div id="watchlist-container" class="d-flex flex-wrap gap-2 mt-2">
                <span class="text-secondary small">Belum ada negara yang dipantau.</span>
            </div>
        </div>
    </div>
</div>

<!-- KPI Indicators -->
<div class="row g-4 mb-4">
    <!-- Active Country GDP -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Produk Domestik Bruto (GDP)</span>
                <h3 class="display-7 fw-bold text-white mt-1" id="kpi-gdp">$4.40 T</h3>
            </div>
            <div class="d-flex align-items-center text-success small mt-2" id="kpi-region-footer">
                <i class="bi bi-globe me-1"></i> Europe
            </div>
        </div>
    </div>
    <!-- Country Inflation -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Tingkat Inflasi</span>
                <h3 class="display-7 fw-bold text-white mt-1" id="kpi-inflation">2.10%</h3>
            </div>
            <div class="d-flex align-items-center text-glow-cyan small mt-2" id="kpi-population-footer">
                <i class="bi bi-people me-1"></i> 84,000,000 Jiwa
            </div>
        </div>
    </div>
    <!-- Extreme Weather (Open-Meteo) -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Cuaca Real-Time</span>
                <h3 class="display-7 fw-bold text-info mt-1" id="kpi-weather">18.5°C</h3>
            </div>
            <div class="d-flex align-items-center text-secondary small mt-2" id="kpi-weather-desc">
                Partly Cloudy
            </div>
        </div>
    </div>
    <!-- Global Risk Index -->
    <div class="col-md-3" id="risk-score-card">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Supply Chain Risk Index</span>
                <h3 class="display-7 fw-bold text-success mt-1" id="kpi-risk-level">Low</h3>
            </div>
            <div class="d-flex align-items-center text-secondary small mt-2" id="kpi-risk-score">
                Skor Risiko: 24.21%
            </div>
        </div>
    </div>
</div>

<!-- Map and News Feed Grid -->
<div class="row g-4 mb-4">
    <!-- Map panel -->
    <div class="col-lg-7">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0"><i class="bi bi-globe me-2 text-glow-cyan"></i> Peta Risiko Logistik Global</h5>
                <span class="badge bg-secondary glass-card text-white py-1.5 px-3" id="map-port-count">0 Ports</span>
            </div>
            <!-- Search & Filter Controls -->
            <div class="row g-2 mb-3">
                <div class="col-sm-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-secondary border-opacity-25 text-secondary"><i class="bi bi-search"></i></span>
                        <input type="text" id="map-search" class="form-control form-control-sm glass-card text-white" placeholder="Cari pelabuhan..." style="border-left: none;">
                    </div>
                </div>
                <div class="col-sm-6">
                    <select id="map-country-filter" class="form-select form-select-sm glass-card text-white">
                        <option value="">Semua Negara</option>
                        <option value="DE">Germany (DE)</option>
                        <option value="CN">China (CN)</option>
                        <option value="ID">Indonesia (ID)</option>
                        <option value="AU">Australia (AU)</option>
                    </select>
                </div>
            </div>
            <div id="main-map" style="height: 380px; border-radius: 12px; border: 1px solid var(--card-border);"></div>
        </div>
    </div>
    
    <!-- Geopolitical and Logistics News Feed -->
    <div class="col-lg-5">
        <div class="glass-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0"><i class="bi bi-newspaper me-2 text-glow-purple"></i> Intelijen Berita Geopolitik</h5>
                <span class="badge badge-low py-1.5 px-3" id="news-sentiment-badge">Positive</span>
            </div>
            
            <!-- Sentiment breakdown percentages -->
            <div class="row g-2 mb-3 text-center small text-secondary">
                <div class="col-4 border-end border-secondary border-opacity-10">
                    <span class="text-success fw-bold d-block" id="sentiment-pct-positive">60%</span> Positif
                </div>
                <div class="col-4 border-end border-secondary border-opacity-10">
                    <span class="text-secondary fw-bold d-block" id="sentiment-pct-neutral">25%</span> Netral
                </div>
                <div class="col-4">
                    <span class="text-danger fw-bold d-block" id="sentiment-pct-negative">15%</span> Negatif
                </div>
            </div>

            <div class="list-group list-group-flush" id="news-feed" style="max-height: 290px; overflow-y: auto;">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- Economic and Risk Trend Charts -->
<div class="row g-4">
    <!-- Risk factors polar/radar chart -->
    <div class="col-md-6">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-shield-check me-2 text-glow-cyan"></i> Radar Indikator Risiko Rantai Pasok</h5>
            <div style="height: 300px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="risk-radar-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- 7-Day Exchange rate volatility chart -->
    <div class="col-md-6">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-currency-exchange me-2 text-glow-purple"></i> Volatilitas Nilai Tukar (7 Hari)</h5>
            <div style="height: 300px; position: relative;">
                <canvas id="currency-line-chart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Weather & Forecast Section -->
<div class="row g-4 mt-2 mb-4">
    <div class="col-12">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-cloud-sun me-2 text-glow-warning"></i> Prakiraan Cuaca 7 Hari & Parameter Detail</h5>
            <div class="row g-4">
                <!-- Weather Details Column -->
                <div class="col-lg-4 border-end border-secondary border-opacity-10">
                    <h6 class="text-secondary small fw-medium mb-3">Detail Cuaca Saat Ini</h6>
                    <div class="d-flex flex-column gap-2 text-white">
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-10">
                            <span class="text-secondary"><i class="bi bi-moisture me-1"></i> Kelembaban</span>
                            <span class="fw-semibold" id="weather-detail-humidity">N/A</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-10">
                            <span class="text-secondary"><i class="bi bi-wind me-1"></i> Arah Angin</span>
                            <span class="fw-semibold" id="weather-detail-wind-direction">N/A</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-10">
                            <span class="text-secondary"><i class="bi bi-cloud-drizzle me-1"></i> Curah Hujan</span>
                            <span class="fw-semibold" id="weather-detail-rain">N/A</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-10">
                            <span class="text-secondary"><i class="bi bi-activity me-1"></i> Kode Cuaca (WMO)</span>
                            <span class="fw-semibold" id="weather-detail-code">N/A</span>
                        </div>
                        <div class="d-flex justify-content-between py-1">
                            <span class="text-secondary"><i class="bi bi-exclamation-triangle me-1"></i> Risiko Badai</span>
                            <span class="fw-semibold text-warning" id="weather-detail-storm-risk">N/A</span>
                        </div>
                    </div>
                </div>
                
                <!-- 7-Day Forecast Column -->
                <div class="col-lg-8">
                    <h6 class="text-secondary small fw-medium mb-3">Prakiraan 7 Hari Ke Depan</h6>
                    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-7 g-2 text-center" id="weather-forecast-container">
                        <div class="col w-100 text-center py-4">
                            <span class="text-secondary small">Data prakiraan tidak tersedia. Silakan tekan tombol "Sync Weather".</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Notification Banner -->
<div id="notification-banner" class="alert d-none position-fixed bottom-0 end-0 m-4 glass-card text-white" style="z-index: 1050; transition: all 0.3s ease;"></div>
@endsection

@section('scripts')
<!-- Leaflet Marker Cluster JS CDN -->
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Leaflet map centered globally
        const map = L.map('main-map').setView([20.0, 0.0], 2);
        
        // Load custom dark map tiles from CartoDB (perfect for dark dashboards)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CartoDB &copy; OpenStreetMap contributors'
        }).addTo(map);

        // Initialize Marker Cluster Group
        const markerClusterGroup = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });
        map.addLayer(markerClusterGroup);

        let allPorts = [];
        let riskRadarChartObj = null;
        let currencyLineChartObj = null;

        const countrySelector = document.getElementById('dashboard-country-selector');
        const syncBtn = document.getElementById('sync-country-btn');
        const watchlistBtn = document.getElementById('toggle-watchlist-btn');

        // Fetch User Watchlist
        function fetchWatchlist() {
            fetch("{{ route('api.watchlist') }}")
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        const container = document.getElementById('watchlist-container');
                        container.innerHTML = '';
                        
                        if (res.data.length === 0) {
                            container.innerHTML = '<span class="text-secondary small">Belum ada negara yang dipantau.</span>';
                            return;
                        }
                        
                        let isWatchedCurrent = false;
                        res.data.forEach(c => {
                            if (c.code === countrySelector.value) {
                                isWatchedCurrent = true;
                            }
                            
                            const badgeColor = c.latest_risk && c.latest_risk.risk_level === 'Low' ? 'badge-low' : (c.latest_risk && c.latest_risk.risk_level === 'Medium' ? 'badge-medium' : 'badge-high');
                            const indicator = c.latest_risk ? `${c.latest_risk.total_risk_score}%` : 'N/A';
                            
                            container.innerHTML += `
                                <a href="#" onclick="selectCountry('${c.code}')" class="badge ${badgeColor} py-1.5 px-3 text-decoration-none">
                                    ${c.name} (${indicator})
                                </a>
                            `;
                        });
                        
                        if (isWatchedCurrent) {
                            watchlistBtn.classList.add('watched');
                        } else {
                            watchlistBtn.classList.remove('watched');
                        }
                    }
                });
        }

        // Trigger Watchlist Toggle
        watchlistBtn.addEventListener('click', function() {
            const countryCode = countrySelector.value;
            fetch("{{ route('api.watchlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ country_code: countryCode })
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    fetchWatchlist();
                }
            });
        });

        // Trigger Country Sync
        syncBtn.addEventListener('click', function() {
            const countryCode = countrySelector.value;
            const syncIcon = document.getElementById('sync-icon');
            const syncText = document.getElementById('sync-btn-text');

            syncIcon.classList.add('spin');
            syncText.innerText = 'Syncing...';
            syncBtn.disabled = true;

            fetch(`/countries/${countryCode}/sync`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(res => {
                syncIcon.classList.remove('spin');
                syncText.innerText = 'Sync Data';
                syncBtn.disabled = false;
                
                if (res.success) {
                    showNotification("Sinkronisasi data " + countryCode + " selesai!");
                    loadDashboardData(countryCode);
                    fetchWatchlist();
                } else {
                    showNotification('Sync failed: ' + res.message, 'danger');
                }
            })
            .catch(err => {
                syncIcon.classList.remove('spin');
                syncText.innerText = 'Sync Data';
                syncBtn.disabled = false;
                showNotification('Sync error: ' + err.message, 'danger');
            });
        });

        // Trigger All Countries Sync
        const syncAllBtn = document.getElementById('sync-all-countries-btn');
        if (syncAllBtn) {
            syncAllBtn.addEventListener('click', function() {
                const syncAllIcon = document.getElementById('sync-all-icon');
                const syncAllText = document.getElementById('sync-all-btn-text');

                syncAllIcon.classList.add('spin');
                syncAllText.innerText = 'Syncing...';
                syncAllBtn.disabled = true;

                fetch('/countries/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(res => {
                    syncAllIcon.classList.remove('spin');
                    syncAllText.innerText = 'Sync Country Data';
                    syncAllBtn.disabled = false;
                    
                    if (res.success) {
                        showNotification(res.message);
                        const countryCode = countrySelector.value;
                        loadDashboardData(countryCode);
                        fetchWatchlist();
                    } else {
                        showNotification('Sync failed: ' + res.message, 'danger');
                    }
                })
                .catch(err => {
                    syncAllIcon.classList.remove('spin');
                    syncAllText.innerText = 'Sync Country Data';
                    syncAllBtn.disabled = false;
                    showNotification('Sync error: ' + err.message, 'danger');
                });
            });
        }

        // Trigger Economic Data Sync
        const syncEconomicBtn = document.getElementById('sync-economic-btn');
        if (syncEconomicBtn) {
            syncEconomicBtn.addEventListener('click', function() {
                const syncEconomicIcon = document.getElementById('sync-economic-icon');
                const syncEconomicText = document.getElementById('sync-economic-btn-text');

                syncEconomicIcon.classList.add('spin');
                syncEconomicText.innerText = 'Syncing...';
                syncEconomicBtn.disabled = true;

                fetch('/countries/sync-economic', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(res => {
                    syncEconomicIcon.classList.remove('spin');
                    syncEconomicText.innerText = 'Sync Economic Data';
                    syncEconomicBtn.disabled = false;
                    
                    if (res.success) {
                        showNotification(res.message);
                        const countryCode = countrySelector.value;
                        loadDashboardData(countryCode);
                        fetchWatchlist();
                    } else {
                        showNotification('Sync failed: ' + res.message, 'danger');
                    }
                })
                .catch(err => {
                    syncEconomicIcon.classList.remove('spin');
                    syncEconomicText.innerText = 'Sync Economic Data';
                    syncEconomicBtn.disabled = false;
                    showNotification('Sync error: ' + err.message, 'danger');
                });
            });
        }

        // Trigger Weather Data Sync
        const syncWeatherBtn = document.getElementById('sync-weather-btn');
        if (syncWeatherBtn) {
            syncWeatherBtn.addEventListener('click', function() {
                const syncWeatherIcon = document.getElementById('sync-weather-icon');
                const syncWeatherText = document.getElementById('sync-weather-btn-text');

                syncWeatherIcon.classList.add('spin');
                syncWeatherText.innerText = 'Syncing...';
                syncWeatherBtn.disabled = true;

                fetch('/countries/sync-weather', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(res => {
                    syncWeatherIcon.classList.remove('spin');
                    syncWeatherText.innerText = 'Sync Weather';
                    syncWeatherBtn.disabled = false;
                    
                    if (res.success) {
                        showNotification(res.message);
                        const countryCode = countrySelector.value;
                        loadDashboardData(countryCode);
                        fetchWatchlist();
                    } else {
                        showNotification('Sync failed: ' + res.message, 'danger');
                    }
                })
                .catch(err => {
                    syncWeatherIcon.classList.remove('spin');
                    syncWeatherText.innerText = 'Sync Weather';
                    syncWeatherBtn.disabled = false;
                    showNotification('Sync error: ' + err.message, 'danger');
                });
            });
        }

        // Trigger Currency Data Sync
        const syncCurrencyBtn = document.getElementById('sync-currency-btn');
        if (syncCurrencyBtn) {
            syncCurrencyBtn.addEventListener('click', function() {
                const syncCurrencyIcon = document.getElementById('sync-currency-icon');
                const syncCurrencyText = document.getElementById('sync-currency-btn-text');

                syncCurrencyIcon.classList.add('spin');
                syncCurrencyText.innerText = 'Syncing...';
                syncCurrencyBtn.disabled = true;

                fetch('/countries/sync-currency', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(res => {
                    syncCurrencyIcon.classList.remove('spin');
                    syncCurrencyText.innerText = 'Sync Currency';
                    syncCurrencyBtn.disabled = false;
                    
                    if (res.success) {
                        showNotification(res.message);
                        const countryCode = countrySelector.value;
                        loadDashboardData(countryCode);
                        fetchWatchlist();
                    } else {
                        showNotification('Sync failed: ' + res.message, 'danger');
                    }
                })
                .catch(err => {
                    syncCurrencyIcon.classList.remove('spin');
                    syncCurrencyText.innerText = 'Sync Currency';
                    syncCurrencyBtn.disabled = false;
                    showNotification('Sync error: ' + err.message, 'danger');
                });
            });
        }

        // Load all data for the selected country
        window.selectCountry = function(code) {
            countrySelector.value = code;
            loadDashboardData(code);
        }

        countrySelector.addEventListener('change', function() {
            loadDashboardData(this.value);
        });

        function loadDashboardData(countryCode) {
            // Update Watchlist State Button
            fetchWatchlist();

            // 1. Fetch Countries detail (GDP, Inflation, Weather, Region, etc.)
            fetch("{{ route('api.countries') }}?country=" + countryCode)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        const country = res.data.find(c => c.code === countryCode);
                        if (country) {
                            // Update KPIs
                            document.getElementById('kpi-gdp').innerText = country.gdp ? '$' + (country.gdp / 1e12).toFixed(2) + ' T' : 'N/A';
                            document.getElementById('kpi-region-footer').innerHTML = `<i class="bi bi-globe me-1"></i> ${country.region}`;
                            document.getElementById('kpi-inflation').innerText = country.inflation ? country.inflation + '%' : 'N/A';
                            document.getElementById('kpi-population-footer').innerHTML = `<i class="bi bi-people me-1"></i> ${Number(country.population).toLocaleString('id-ID')} Jiwa`;
                            document.getElementById('kpi-weather').innerText = country.weather_temp !== null ? country.weather_temp + '°C' : 'N/A';
                            document.getElementById('kpi-weather-desc').innerText = country.weather_condition || 'N/A';

                            // Update detailed weather parameters
                            document.getElementById('weather-detail-humidity').innerText = country.weather_humidity !== null ? country.weather_humidity + '%' : 'N/A';
                            document.getElementById('weather-detail-wind-direction').innerText = country.weather_wind_direction !== null ? country.weather_wind_direction + '°' : 'N/A';
                            document.getElementById('weather-detail-rain').innerText = country.weather_rain !== null ? country.weather_rain + ' mm' : 'N/A';
                            document.getElementById('weather-detail-code').innerText = country.weather_code !== null ? country.weather_code : 'N/A';
                            document.getElementById('weather-detail-storm-risk').innerText = country.weather_storm_risk !== null ? country.weather_storm_risk + '%' : 'N/A';

                            // Update forecast list
                            const forecastContainer = document.getElementById('weather-forecast-container');
                            forecastContainer.innerHTML = '';
                            if (country.weather_forecast && country.weather_forecast.length > 0) {
                                country.weather_forecast.forEach(day => {
                                    const dateObj = new Date(day.date);
                                    const dayName = dateObj.toLocaleDateString('id-ID', { weekday: 'short' });
                                    const dayDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                                    
                                    let iconClass = 'bi-cloud';
                                    if (day.condition === 'Clear Sky') iconClass = 'bi-sun';
                                    else if (day.condition === 'Partly Cloudy') iconClass = 'bi-cloud-sun';
                                    else if (day.condition === 'Foggy') iconClass = 'bi-cloud-fog';
                                    else if (day.condition === 'Drizzle') iconClass = 'bi-cloud-drizzle';
                                    else if (day.condition === 'Heavy Rain') iconClass = 'bi-cloud-rain-heavy';
                                    else if (day.condition === 'Snowy') iconClass = 'bi-cloud-snow';
                                    else if (day.condition === 'Thunderstorm') iconClass = 'bi-cloud-lightning-rain';

                                    forecastContainer.innerHTML += `
                                        <div class="col">
                                            <div class="glass-card p-2 h-100 d-flex flex-column justify-content-between align-items-center" style="background: rgba(255,255,255,0.02); min-height: 120px;">
                                                <div class="small fw-semibold text-secondary mb-1">${dayName}</div>
                                                <div class="small text-muted mb-2" style="font-size: 0.75rem;">${dayDate}</div>
                                                <div class="fs-4 text-warning mb-2"><i class="bi ${iconClass}"></i></div>
                                                <div class="small text-white fw-bold mb-1">${day.temp_max}°C</div>
                                                <div class="small text-secondary" style="font-size: 0.75rem;">${day.temp_min}°C</div>
                                            </div>
                                        </div>
                                    `;
                                });
                            } else {
                                forecastContainer.innerHTML = `
                                    <div class="col w-100 text-center py-3">
                                        <span class="text-secondary small">Tidak ada data prakiraan. Silakan tekan tombol "Sync Weather".</span>
                                    </div>
                                `;
                            }

                            // Center map on capital
                            if (country.latitude && country.longitude) {
                                map.setView([country.latitude, country.longitude], 4);
                            }

                            // Update risk metrics card styling
                            const riskCard = document.getElementById('risk-score-card');
                            riskCard.className = 'col-md-3'; // clear custom border glow
                            const riskLevelBadge = document.getElementById('kpi-risk-level');
                            const riskScoreFooter = document.getElementById('kpi-risk-score');

                            if (country.latest_risk) {
                                riskLevelBadge.innerText = country.latest_risk.risk_level;
                                riskScoreFooter.innerText = `Skor Risiko: ${country.latest_risk.total_risk_score}%`;
                                
                                if (country.latest_risk.risk_level === 'Low') {
                                    riskCard.classList.add('border-glow-success');
                                    riskLevelBadge.className = 'display-7 fw-bold text-success mt-1';
                                } else if (country.latest_risk.risk_level === 'Medium') {
                                    riskCard.classList.add('border-glow-warning');
                                    riskLevelBadge.className = 'display-7 fw-bold text-warning mt-1';
                                } else {
                                    riskCard.classList.add('border-glow-danger');
                                    riskLevelBadge.className = 'display-7 fw-bold text-danger mt-1';
                                }

                                // Update Radar risk factors chart
                                updateRadarChart(country.latest_risk);
                            } else {
                                riskLevelBadge.innerText = 'N/A';
                                riskScoreFooter.innerText = 'Belum Dihitung';
                            }
                        }
                    }
                });

            // 2. Filter map ports by selected country
            const mapCountryFilter = document.getElementById('map-country-filter');
            if (mapCountryFilter) {
                mapCountryFilter.value = countryCode;
                renderMapPorts();
            }

            // 3. Fetch News feed & Sentiments
            fetch(`/api/news?country=${countryCode}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        const data = res.data;
                        
                        // Update Breakdown percentages
                        document.getElementById('sentiment-pct-positive').innerText = `${data.sentiment_breakdown.positive}%`;
                        document.getElementById('sentiment-pct-neutral').innerText = `${data.sentiment_breakdown.neutral}%`;
                        document.getElementById('sentiment-pct-negative').innerText = `${data.sentiment_breakdown.negative}%`;

                        // Update news dominant sentiment badge
                        const sentimentBadge = document.getElementById('news-sentiment-badge');
                        const maxVal = Math.max(data.sentiment_breakdown.positive, data.sentiment_breakdown.neutral, data.sentiment_breakdown.negative);
                        
                        sentimentBadge.className = 'badge';
                        if (maxVal === data.sentiment_breakdown.positive) {
                            sentimentBadge.innerText = 'Positive';
                            sentimentBadge.classList.add('badge-low');
                        } else if (maxVal === data.sentiment_breakdown.negative) {
                            sentimentBadge.innerText = 'Negative';
                            sentimentBadge.classList.add('badge-high');
                        } else {
                            sentimentBadge.innerText = 'Neutral';
                            sentimentBadge.classList.add('bg-secondary');
                        }

                        // Populate feed list
                        const feed = document.getElementById('news-feed');
                        feed.innerHTML = '';
                        
                        data.articles.forEach(article => {
                            const badgeColor = article.sentiment === 'Positive' ? 'badge-low' : (article.sentiment === 'Negative' ? 'badge-high' : 'bg-secondary text-secondary');
                            
                            feed.innerHTML += `
                                <div class="list-group-item bg-transparent text-white border-secondary border-opacity-25 px-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge ${badgeColor}" style="font-size: 0.75rem;">${article.sentiment}</span>
                                        <small class="text-secondary">${new Date(article.published_at).toLocaleDateString('id-ID')}</small>
                                    </div>
                                    <h6 class="mb-1 text-glow-cyan">${article.title}</h6>
                                    <p class="text-secondary small mb-0">${article.description}</p>
                                    <small class="text-muted">Sumber: ${article.source}</small>
                                </div>
                            `;
                        });
                    }
                });

            // 4. Fetch Currency rate and history trend
            fetch(`/api/currency?country=${countryCode}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        updateLineChart(res.data);
                    }
                });
        }

        // Radar chart update (Supply chain risk factors breakdown)
        function updateRadarChart(riskData) {
            const chartData = [
                riskData.weather_risk_score,
                riskData.inflation_risk_score,
                riskData.currency_risk_score,
                riskData.political_risk_score
            ];

            if (riskRadarChartObj) {
                riskRadarChartObj.data.datasets[0].data = chartData;
                riskRadarChartObj.update();
                return;
            }

            const ctx = document.getElementById('risk-radar-chart').getContext('2d');
            riskRadarChartObj = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Risiko Cuaca', 'Risiko Inflasi', 'Risiko Nilai Tukar', 'Risiko Geopolitik'],
                    datasets: [{
                        label: 'Skor Indikator (%)',
                        data: chartData,
                        backgroundColor: 'rgba(56, 189, 248, 0.2)',
                        borderColor: '#38BDF8',
                        pointBackgroundColor: '#8B5CF6',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' },
                            pointLabels: { color: '#9CA3AF', font: { family: 'Outfit', size: 11 } },
                            ticks: { display: false, max: 100, min: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Line chart update (Exchange rate history trend)
        function updateLineChart(currencyData) {
            const labels = currencyData.history.map(h => {
                const dateObj = new Date(h.date);
                return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            });
            const dataPoints = currencyData.history.map(h => h.rate);

            if (currencyLineChartObj) {
                currencyLineChartObj.data.labels = labels;
                currencyLineChartObj.data.datasets[0].label = `Kurs 1 USD ke ${currencyData.currency_code}`;
                currencyLineChartObj.data.datasets[0].data = dataPoints;
                currencyLineChartObj.update();
                return;
            }

            const ctx = document.getElementById('currency-line-chart').getContext('2d');
            currencyLineChartObj = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Kurs 1 USD ke ${currencyData.currency_code}`,
                        data: dataPoints,
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#9CA3AF' } },
                        y: { grid: { color: 'rgba(255, 255, 255, 0.05)' }, ticks: { color: '#9CA3AF' } }
                    },
                    plugins: {
                        legend: { labels: { color: '#9CA3AF', font: { family: 'Outfit' } } }
                    }
                }
            });
        }

        // Map Search & Country Filter Event Listeners and Helpers
        document.getElementById('map-search').addEventListener('input', renderMapPorts);
        document.getElementById('map-country-filter').addEventListener('change', function() {
            renderMapPorts();
            if (this.value && this.value !== countrySelector.value) {
                countrySelector.value = this.value;
                loadDashboardData(this.value);
            }
        });

        function fetchAllPorts() {
            fetch('/api/ports')
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        allPorts = res.data;
                        renderMapPorts();
                    }
                });
        }

        function renderMapPorts() {
            markerClusterGroup.clearLayers();
            
            const searchText = document.getElementById('map-search').value.toLowerCase().trim();
            const countryFilter = document.getElementById('map-country-filter').value;
            
            const filteredPorts = allPorts.filter(port => {
                const matchesCountry = !countryFilter || (port.country && port.country.code === countryFilter);
                const matchesSearch = !searchText || 
                    port.name.toLowerCase().includes(searchText) || 
                    port.port_code.toLowerCase().includes(searchText);
                return matchesCountry && matchesSearch;
            });
            
            document.getElementById('map-port-count').innerText = `${filteredPorts.length} Ports`;
            
            filteredPorts.forEach(port => {
                const markerColor = port.congestion_rate > 50.0 ? '#EF4444' : (port.congestion_rate > 25.0 ? '#F59E0B' : '#10B981');
                
                const icon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${markerColor};"></div>`,
                    iconSize: [14, 14]
                });
                
                const marker = L.marker([port.latitude, port.longitude], { icon: icon })
                    .bindPopup(`
                        <div class="text-dark">
                            <strong>${port.name}</strong><br>
                            <small class="text-muted">Kode: ${port.port_code}</small><br>
                            Waktu Tunggu: <strong>${port.waiting_time_hours} jam</strong><br>
                            Rasio Kemacetan: <strong>${port.congestion_rate}%</strong><br>
                            Negara: <strong>${port.country ? `${port.country.name} (${port.country.code})` : 'N/A'}</strong>
                        </div>
                    `);
                    
                markerClusterGroup.addLayer(marker);
            });

            // Zoom map to fit clustered markers safely
            if (filteredPorts.length > 0) {
                try {
                    const groupBounds = markerClusterGroup.getBounds();
                    if (groupBounds && typeof groupBounds.getNorthEast === 'function') {
                        map.fitBounds(groupBounds, { maxZoom: 5, padding: [20, 20] });
                    }
                } catch (err) {
                    console.warn("Failed to fit map bounds:", err);
                }
            }
        }

        // Floating notification helper (non-blocking alternative to alert())
        function showNotification(message, type = 'success') {
            const banner = document.getElementById('notification-banner');
            banner.innerText = message;
            banner.className = `alert d-block position-fixed bottom-0 end-0 m-4 glass-card text-white ${type === 'success' ? 'border-glow-success' : 'border-glow-danger'}`;
            banner.style.background = type === 'success' ? 'rgba(16, 185, 129, 0.95)' : 'rgba(239, 68, 68, 0.95)';
            banner.style.boxShadow = type === 'success' ? '0 0 20px rgba(16, 185, 129, 0.4)' : '0 0 20px rgba(239, 68, 68, 0.4)';
            banner.style.zIndex = '1050';
            
            setTimeout(() => {
                banner.className = 'alert d-none';
            }, 4000);
        }

        // Auto-polling for real-time updates every 15 seconds
        setInterval(function() {
            const countryCode = countrySelector.value;
            loadDashboardData(countryCode);
        }, 15000); // 15 seconds

        // Initial Load (Germany)
        fetchAllPorts();
        loadDashboardData('DE');
    });
</script>
@endsection
