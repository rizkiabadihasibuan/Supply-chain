@extends('layouts.app')

@section('title', 'Dashboard Control Center')

@section('styles')
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

        <!-- Sync Button -->
        <button type="button" id="sync-country-btn" class="btn btn-sm btn-primary d-flex align-items-center py-1.5 px-3">
            <i class="bi bi-arrow-repeat me-1.5" id="sync-icon"></i> <span id="sync-btn-text">Sync Data</span>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Leaflet map centered globally
        const map = L.map('main-map').setView([20.0, 0.0], 2);
        
        // Load custom dark map tiles from CartoDB (perfect for dark dashboards)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CartoDB &copy; OpenStreetMap contributors'
        }).addTo(map);

        let mapMarkers = [];
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
                    loadDashboardData(countryCode);
                    fetchWatchlist();
                } else {
                    alert('Sync failed: ' + res.message);
                }
            })
            .catch(err => {
                syncIcon.classList.remove('spin');
                syncText.innerText = 'Sync Data';
                syncBtn.disabled = false;
                alert('Sync error: ' + err.message);
            });
        });

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
            fetch("{{ route('api.countries') }}")
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

            // 2. Fetch Ports for Map markers
            fetch(`/api/ports?country=${countryCode}`)
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        // Clear markers
                        mapMarkers.forEach(m => map.removeLayer(m));
                        mapMarkers = [];

                        document.getElementById('map-port-count').innerText = `${res.data.length} Ports`;

                        res.data.forEach(port => {
                            const markerColor = port.congestion_rate > 50.0 ? '#EF4444' : (port.congestion_rate > 25.0 ? '#F59E0B' : '#10B981');
                            
                            // Custom DivIcon
                            const icon = L.divIcon({
                                className: 'custom-div-icon',
                                html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${markerColor};"></div>`,
                                iconSize: [14, 14]
                            });

                            const marker = L.marker([port.latitude, port.longitude], { icon: icon })
                                .addTo(map)
                                .bindPopup(`
                                    <div class="text-dark">
                                        <strong>${port.name}</strong><br>
                                        <small class="text-muted">Kode: ${port.port_code}</small><br>
                                        Waktu Tunggu: <strong>${port.waiting_time_hours} jam</strong><br>
                                        Rasio Kemacetan: <strong>${port.congestion_rate}%</strong>
                                    </div>
                                `);

                            mapMarkers.push(marker);
                        });
                    }
                });

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

        // Initial Load (Germany)
        loadDashboardData('DE');
    });
</script>
@endsection
