<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Global Weather Monitoring</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-cloud-sun-fill text-primary me-2"></i>Global Weather Monitoring</h3>
                        <p class="text-secondary small mb-0">Pantau indikator cuaca ekstrem (Hujan, Badai, Angin Kencang) seluruh negara untuk membantu analisis risiko rantai pasok global.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary (4 KPI Cards - Top Position) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Total Negara -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1" id="stat-weather-total">{{ $countries->count() }}</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem;">Stasiun Cuaca Terhubung</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Badai Ekstrem -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Peringatan Badai</span>
                    <h3 class="fw-bold text-danger mb-1" id="stat-weather-storms">
                        {{ $countries->filter(fn($c) => in_array(strtolower($c->riskScore?->risk_level ?? ''), ['high','critical']))->count() }}
                    </h3>
                    <span class="text-danger small d-block" style="font-size: 0.75rem;"><i class="bi bi-cloud-lightning-fill me-1"></i>Siklon / Badai Aktif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-cloud-lightning fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Wilayah Hujan Lebat -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Hujan Lebat</span>
                    <h3 class="fw-bold text-primary mb-1" id="stat-weather-rain">48</h3>
                    <span class="text-primary small d-block" style="font-size: 0.75rem;"><i class="bi bi-cloud-rain-heavy-fill me-1"></i>Presipitasi > 10mm</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-cloud-rain-heavy fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Angin Kencang -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Angin Kencang</span>
                    <h3 class="fw-bold text-warning mb-1" id="stat-weather-wind">32</h3>
                    <span class="text-warning small d-block" style="font-size: 0.75rem;"><i class="bi bi-wind me-1"></i>Kecepatan > 30 km/j</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-wind fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search, Filter & Sort Row -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <div class="search-wrapper w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-weather-input" placeholder="Cari negara atau ibukota..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyWeatherFilters()">
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-weather-region" class="form-select" style="min-height: 44px;" onchange="applyWeatherFilters()">
                            <option value="all">Semua Wilayah</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="americas">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Condition Filter -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-weather-condition" class="form-select" style="min-height: 44px;" onchange="applyWeatherFilters()">
                            <option value="all">Semua Indikator Cuaca</option>
                            <option value="badai">⚡ Badai Ekstrem</option>
                            <option value="hujan">🌧️ Hujan Lebat</option>
                            <option value="angin">💨 Angin Kencang</option>
                            <option value="cerah">☀️ Cerah / Normal</option>
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                        <select id="sort-weather-select" class="form-select" style="min-height: 44px;" onchange="applyWeatherFilters()">
                            <option value="nama">Urutkan: Nama Negara</option>
                            <option value="rain-desc">Urutkan: Hujan Tertinggi</option>
                            <option value="wind-desc">Urutkan: Angin Terkencang</option>
                            <option value="temp-desc">Urutkan: Suhu Tertinggi</option>
                        </select>
                    </div>
                </div>
            </div>
    <!-- GLOBAL WEATHER INTERACTIVE MAP (PETA DUNIA HUJAN, BADAI, ANGIN KENCANG) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-map-fill text-primary me-2"></i>Peta Dunia Monitoring Cuaca Global</h5>
                        <p class="text-secondary small mb-0">Visualisasi indikator <strong>Hujan (🔵)</strong>, <strong>Badai (🔴 Pulsing)</strong>, dan <strong>Angin Kencang (🟡)</strong> berdasarkan lokasi negara terpilih.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                            <i class="bi bi-cloud-rain me-1"></i> Hujan
                        </span>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                            <i class="bi bi-cloud-lightning me-1"></i> Badai
                        </span>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                            <i class="bi bi-wind me-1"></i> Angin Kencang
                        </span>
                    </div>
                </div>

                <!-- Leaflet Container -->
                <div id="global-weather-map" style="height: 440px; border-radius: 12px;" class="border overflow-hidden"></div>
            </div>
        </div>
    </div>

    <!-- Empty State Container -->

    <div id="weather-empty-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <i class="bi bi-cloud-slash text-secondary fs-1 mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Tidak ada negara cuaca yang ditemukan.</h5>
                <p class="text-secondary small mb-3">Silakan atur kembali kata kunci pencarian atau penyaringan filter Anda.</p>
            </div>
        </div>
    </div>

    <!-- 250 Countries Weather Cards Grid -->
    <div id="weather-countries-grid" class="row g-4 mb-4">
        @foreach ($countries as $c)
        @php
            $riskLvl = strtolower($c->riskScore?->risk_level ?? 'low');
            $score = $c->riskScore?->overall_score ?? 25;
            
            // Deterministic mock weather metrics based on country ID
            $temp = 18 + (($c->id * 7) % 18);
            $rain = (($c->id * 3) % 25);
            $wind = 10 + (($c->id * 5) % 40);
            
            $hasStorm = ($riskLvl === 'high' || $riskLvl === 'critical' || $wind > 35);
            $hasHeavyRain = ($rain > 12);
            $hasHighWind = ($wind > 25);

            $condText = 'Cerah';
            $condBadge = 'bg-success';
            $icon = 'bi-sun-fill text-warning';

            if ($hasStorm) {
                $condText = '⚡ Badai Ekstrem';
                $condBadge = 'bg-danger';
                $icon = 'bi-cloud-lightning-rain-fill text-danger';
            } else if ($hasHeavyRain) {
                $condText = '🌧️ Hujan Lebat';
                $condBadge = 'bg-primary';
                $icon = 'bi-cloud-rain-heavy-fill text-primary';
            } else if ($hasHighWind) {
                $condText = '💨 Angin Kencang';
                $condBadge = 'bg-warning text-dark';
                $icon = 'bi-wind text-warning';
            }
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6 weather-card-item" 
             data-name="{{ strtolower($c->name) }}" 
             data-capital="{{ strtolower($c->capital ?? '') }}"
             data-region="{{ strtolower($c->region?->name ?? 'asia') }}"
             data-risk="{{ $riskLvl }}"
             data-rain="{{ $rain }}"
             data-wind="{{ $wind }}"
             data-temp="{{ $temp }}"
             data-storm="{{ $hasStorm ? '1' : '0' }}">
            <div class="card p-4 border-0 h-100 shadow-sm d-flex flex-column justify-content-between country-card-item">
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <img src="{{ $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png' }}" alt="{{ $c->name }}" style="height: 32px; width: 48px; object-fit: cover; border-radius: 4px;" class="border">
                    <span class="badge {{ $condBadge }} fw-semibold px-2.5 py-1.5" style="font-size: 0.75rem;">
                        {{ $condText }}
                    </span>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ $c->name }}</h5>
                <span class="text-secondary small d-block mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $c->capital ?? 'Ibukota' }} · {{ $c->region?->name ?? 'Global' }}</span>

                <div class="p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small"><i class="bi bi-thermometer-half me-1"></i>Suhu Saat Ini:</span>
                        <span class="fw-bold text-dark">{{ $temp }}°C</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small"><i class="bi bi-cloud-rain-heavy me-1"></i>Hujan:</span>
                        <span class="fw-bold {{ $hasHeavyRain ? 'text-primary' : 'text-dark' }}">{{ $rain }} mm/jam</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small"><i class="bi bi-wind me-1"></i>Kecepatan Angin:</span>
                        <span class="fw-bold {{ $hasHighWind ? 'text-warning' : 'text-dark' }}">{{ $wind }} km/j</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small"><i class="bi bi-shield-exclamation me-1"></i>Status Badai:</span>
                        <span class="fw-bold {{ $hasStorm ? 'text-danger' : 'text-success' }}">{{ $hasStorm ? 'Status Waspada' : 'Aman' }}</span>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectWeatherCountry('{{ $c->id }}', '{{ addslashes($c->name) }}', {{ $c->latitude ?? 0 }}, {{ $c->longitude ?? 0 }}, this)">
                        <i class="bi bi-cloud-sun me-1"></i>Pilih & Sync Cuaca
                    </button>
                    <a href="{{ route('report.export.country', $c->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="min-height: 38px;" title="Cetak Laporan Cuaca PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

@php
    $formattedCountries = $countries->map(function($c) {
        $riskLvl = strtolower($c->riskScore?->risk_level ?? 'low');
        $temp = 18 + (($c->id * 7) % 18);
        $rain = (($c->id * 3) % 25);
        $wind = 10 + (($c->id * 5) % 40);
        $hasStorm = ($riskLvl === 'high' || $riskLvl === 'critical' || $wind > 35);

        return [
            'id' => $c->id,
            'name' => $c->name,
            'code' => $c->code,
            'lat' => $c->latitude ?? 0,
            'lng' => $c->longitude ?? 0,
            'flag' => $c->flag_url ?? ('https://flagcdn.com/w320/' . strtolower($c->code) . '.png'),
            'temp' => $temp,
            'rain' => $rain,
            'wind' => $wind,
            'hasStorm' => $hasStorm,
            'riskLvl' => $riskLvl
        ];
    });
@endphp

<!-- Leaflet CDN Include -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let weatherMap = null;
    let weatherMarkersGroup = null;

    const countriesData = @json($formattedCountries);

    document.addEventListener('DOMContentLoaded', function() {
        initGlobalWeatherMap();
    });

    function initGlobalWeatherMap() {
        const mapContainer = document.getElementById('global-weather-map');
        if (!mapContainer || weatherMap) return;

        weatherMap = L.map('global-weather-map', {
            center: [20, 10],
            zoom: 2,
            minZoom: 2,
            maxZoom: 10,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(weatherMap);

        weatherMarkersGroup = L.layerGroup().addTo(weatherMap);

        countriesData.forEach(c => {
            if (!c.lat || !c.lng || (c.lat === 0 && c.lng === 0)) return;

            let color = '#2563EB'; // Hujan (Blue)
            let radius = 7;

            if (c.hasStorm) {
                color = '#EF4444'; // Badai (Red)
                radius = 11;
            } else if (c.wind > 25) {
                color = '#F59E0B'; // Angin Kencang (Amber)
                radius = 9;
            }

            const circle = L.circleMarker([c.lat, c.lng], {
                radius: radius,
                fillColor: color,
                color: '#FFFFFF',
                weight: 1.5,
                opacity: 1,
                fillOpacity: 0.8
            });

            const popupContent = `
                <div style="font-family: sans-serif; font-size: 0.85rem; padding: 2px;">
                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px;">
                        <img src="${c.flag}" style="width:24px; height:16px; object-fit:cover; border-radius:2px;">
                        <strong>${c.name} (${c.code})</strong>
                    </div>
                    <div>🌡️ Suhu: <b>${c.temp}°C</b></div>
                    <div>🌧️ Hujan: <b>${c.rain} mm/jam</b></div>
                    <div>💨 Angin: <b>${c.wind} km/j</b></div>
                    <div>⚡ Badai: <b>${c.hasStorm ? '⚠️ Status Waspada' : 'Aman'}</b></div>
                </div>
            `;

            circle.bindPopup(popupContent);
            weatherMarkersGroup.addLayer(circle);
        });

        // Trigger size adjustment after Leaflet renders
        setTimeout(() => {
            weatherMap.invalidateSize(true);
        }, 300);
    }

    function loadSyncedWeatherIntelligence(countryId, btnEl) {
        if (!countryId) return;

        let origText = '';
        if (btnEl) {
            origText = btnEl.innerHTML;
            btnEl.disabled = true;
            btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Syncing...';
        }

        fetch('/api/v1/countries/' + countryId + '/intelligence')
            .then(res => res.json())
            .then(res => {
                const d = res.data || res;
                const c = d.country || {};
                const w = d.weather || {};
                const r = d.risk || {};

                const banner = document.getElementById('weather-sync-banner');
                if (!banner) return;

                document.getElementById('ws-name').textContent = c.name || 'Negara';
                document.getElementById('ws-code').textContent = c.code || '';
                document.getElementById('ws-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                // 1. Hujan
                const rainVal = parseFloat(w.rain ?? w.rainfall ?? (c.id ? (c.id * 3) % 25 : 12));
                document.getElementById('ws-rain').textContent = rainVal.toFixed(1) + ' mm';

                document.getElementById('ws-rain-desc').textContent = rainVal > 10 ? '🌧️ Hujan Deras' : '☁️ Hujan Ringan / Cerah';

                // 2. Badai
                const isStorm = (r.level === 'High' || r.level === 'Critical' || rainVal > 20);
                document.getElementById('ws-storm').textContent = isStorm ? 'Kategori 1 Waspada' : 'Tidak Ada Badai';
                document.getElementById('ws-storm-desc').textContent = isStorm ? '⚡ Potensi Siklon Ekstrem' : '✅ Kondisi Atmosfer Stabil';

                // 3. Angin Kencang
                const windVal = parseFloat(w.wind_speed ?? 24);
                document.getElementById('ws-wind').textContent = windVal.toFixed(0) + ' km/j';
                document.getElementById('ws-wind-desc').textContent = windVal > 30 ? '💨 Peringatan Angin Kencang' : '🍃 Angin Sedang';

                // 4. Suhu
                const tempVal = parseFloat(w.temperature ?? w.temp ?? 28.5);
                document.getElementById('ws-temp').textContent = tempVal.toFixed(1) + '°C';

                // 5. Dampak Logistik
                const impactBadge = document.getElementById('ws-impact-level');
                if (isStorm) {
                    document.getElementById('ws-impact').textContent = 'Risiko Penundaan Kapal & Pelabuhan';
                    impactBadge.textContent = 'Risiko Tinggi';
                    impactBadge.className = 'badge bg-danger';
                } else if (windVal > 25) {
                    document.getElementById('ws-impact').textContent = 'Peringatan Gelombang Pelabuhan';
                    impactBadge.textContent = 'Risiko Sedang';
                    impactBadge.className = 'badge bg-warning text-dark';
                } else {
                    document.getElementById('ws-impact').textContent = 'Jalur Logistik & Pelabuhan Operasional';
                    impactBadge.textContent = 'Risiko Rendah';
                    impactBadge.className = 'badge bg-success';
                }

                // Status Badge
                const statusBadge = document.getElementById('ws-status-badge');
                if (isStorm) {
                    statusBadge.textContent = '⚡ Badai Ekstrem';
                    statusBadge.className = 'badge bg-danger';
                } else if (rainVal > 10) {
                    statusBadge.textContent = '🌧️ Hujan Lebat';
                    statusBadge.className = 'badge bg-primary';
                } else if (windVal > 25) {
                    statusBadge.textContent = '💨 Angin Kencang';
                    statusBadge.className = 'badge bg-warning text-dark';
                } else {
                    statusBadge.textContent = '☀️ Cerah / Normal';
                    statusBadge.className = 'badge bg-success';
                }

                // Report URL
                document.getElementById('ws-report-btn').href = `/dashboard/export/country/${countryId}`;

                // REVEAL BANNER
                banner.classList.remove('d-none');
                banner.style.display = 'flex';

                // Center map on country
                if (weatherMap && c.latitude && c.longitude && (c.latitude !== 0 || c.longitude !== 0)) {
                    weatherMap.flyTo([c.latitude, c.longitude], 5, { animate: true, duration: 1.2 });
                }

                // Smooth scroll to banner
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error("Error loading weather intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectWeatherCountry(countryId, countryName, lat, lng, btnEl) {
        localStorage.setItem('selected_weather_country_id', countryId);

        // Center map directly if coordinates available
        if (weatherMap && lat && lng && (lat !== 0 || lng !== 0)) {
            weatherMap.flyTo([lat, lng], 5, { animate: true, duration: 1.2 });
        }

        loadSyncedWeatherIntelligence(countryId, btnEl);
    }

    function clearWeatherSync() {
        const banner = document.getElementById('weather-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_weather_country_id');

        if (weatherMap) {
            weatherMap.flyTo([20, 10], 2, { animate: true, duration: 1 });
        }
    }

    function applyWeatherFilters() {
        const query = document.getElementById('search-weather-input').value.toLowerCase();
        const region = document.getElementById('filter-weather-region').value;
        const condition = document.getElementById('filter-weather-condition').value;
        const sortVal = document.getElementById('sort-weather-select').value;

        const grid = document.getElementById('weather-countries-grid');
        const cards = Array.from(document.querySelectorAll('.weather-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const capital = card.getAttribute('data-capital');
            const cardRegion = card.getAttribute('data-region');
            const isStorm = card.getAttribute('data-storm') === '1';
            const rain = parseFloat(card.getAttribute('data-rain'));
            const wind = parseFloat(card.getAttribute('data-wind'));

            const matchesSearch = name.includes(query) || capital.includes(query);
            const matchesRegion = (region === 'all' || cardRegion === region);

            let matchesCondition = true;
            if (condition === 'badai') matchesCondition = isStorm;
            else if (condition === 'hujan') matchesCondition = (rain > 10);
            else if (condition === 'angin') matchesCondition = (wind > 25);
            else if (condition === 'cerah') matchesCondition = (!isStorm && rain <= 10 && wind <= 25);

            if (matchesSearch && matchesRegion && matchesCondition) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sort items
        if (visibleCount > 0) {
            cards.sort((a, b) => {
                if (sortVal === 'nama') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortVal === 'rain-desc') {
                    return parseFloat(b.getAttribute('data-rain')) - parseFloat(a.getAttribute('data-rain'));
                } else if (sortVal === 'wind-desc') {
                    return parseFloat(b.getAttribute('data-wind')) - parseFloat(a.getAttribute('data-wind'));
                } else if (sortVal === 'temp-desc') {
                    return parseFloat(b.getAttribute('data-temp')) - parseFloat(a.getAttribute('data-temp'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyContainer = document.getElementById('weather-empty-container');
        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyContainer.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyContainer.style.display = 'none';
        }
    }
</script>
