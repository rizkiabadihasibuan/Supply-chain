@extends('layouts.app')

@section('title', 'Cuaca Global - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0">

    <!-- Header Row -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1 bg-transparent p-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: var(--primary);"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Cuaca</li>
                            </ol>
                        </nav>
                        <h4 class="fw-bold text-dark mb-0">Pemantauan Cuaca Global</h4>
                    </div>
                    
                    <button class="btn btn-light border px-4" id="btn-refresh-weather">
                        <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-funnel-fill text-primary me-2"></i>Penyaring Data Iklim</h6>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small text-secondary fw-semibold">Cari Negara</label>
                        <input type="text" id="search-weather-country" class="form-control" placeholder="Ketik nama negara...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-secondary fw-semibold">Pilih Negara</label>
                        <select id="select-weather-country" class="form-select">
                            <option value="all">Semua Negara</option>
                            <option value="ID">Indonesia</option>
                            <option value="SG">Singapura</option>
                            <option value="JP">Jepang</option>
                            <option value="US">Amerika Serikat</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-secondary fw-semibold">Pilih Benua</label>
                        <select id="select-continent" class="form-select">
                            <option value="all">Semua Benua</option>
                            <option value="Asia">Asia</option>
                            <option value="Eropa">Eropa</option>
                            <option value="Amerika">Amerika</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-secondary fw-semibold">Kondisi Cuaca</label>
                        <select id="select-condition" class="form-select">
                            <option value="all">Semua Kondisi</option>
                            <option value="Cerah">Cerah</option>
                            <option value="Berawan">Berawan</option>
                            <option value="Hujan">Hujan</option>
                            <option value="Ekstrem">Kondisi Ekstrem</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-light border w-100 py-2.5" id="btn-reset-filters">
                            <i class="bi bi-trash3 me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weather Summary (4 KPI Cards) -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Negara Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-dark mb-0" id="summary-monitored">195</h3>
                    <span class="text-primary small fw-semibold"><i class="bi bi-globe2 me-1"></i>Cakupan Global</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(14, 165, 233, 0.1); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2: Suhu Rata-rata Global -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Suhu Rata-rata Global</span>
                    <h3 class="fw-bold text-dark mb-0">24.5°C</h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-thermometer-half me-1"></i>Kondisi Normal</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.1); color: var(--success);">
                    <i class="bi bi-thermometer-half fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3: Negara Dengan Cuaca Ekstrem -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Cuaca Ekstrem</span>
                    <h3 class="fw-bold text-danger mb-0">1 Negara</h3>
                    <span class="text-danger small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Amerika Serikat (Badai)</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.1); color: var(--danger);">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4: Update Terakhir -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Pembaruan Terakhir</span>
                    <h3 class="fw-bold text-dark mb-0">Baru Saja</h3>
                    <span class="text-secondary small"><i class="bi bi-clock me-1"></i>Real-time update</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: #F1F5F9; color: var(--text-secondary);">
                    <i class="bi bi-arrow-repeat fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Rows -->
    <div class="row g-4 mb-4">
        
        <!-- Left Column: Map & Graph -->
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                
                <!-- World Map Weather Overlay -->
                <div class="card p-4 border-0 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-map text-primary me-2"></i>Radar Cuaca Spasial Dunia</h6>
                        <span class="badge badge-info text-uppercase" style="font-size: 0.65rem;">Satelit Pemantau</span>
                    </div>
                    
                    <div class="border rounded-4 d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 400px; background-color: #FAFCFF; background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px); background-size: 24px 24px;">
                        <div class="text-center position-relative z-index-1">
                            <div class="p-3 rounded-circle d-inline-block border mb-3" style="background-color: rgba(14, 165, 233, 0.08); border-color: rgba(14, 165, 233, 0.2) !important;">
                                <i class="bi bi-radar fs-2 text-primary"></i>
                            </div>
                            <h6 class="text-dark fw-bold mb-1">Visualisasi Radar Cuaca Global</h6>
                            <p class="text-secondary small mb-0">Integrasi peta spasial cuaca Leaflet.js siap dipasang pada tahap pengerjaan berikutnya.</p>
                        </div>
                    </div>
                </div>

                <!-- Weather Trend Global Chart -->
                <div class="card p-4 border-0 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Grafik Perubahan Suhu Port Utama (7 Hari Terakhir)</h6>
                        <span class="badge badge-info text-uppercase" style="font-size: 0.65rem;">Chart.js Engine</span>
                    </div>

                    <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 250px; background-color: #FAFCFF;">
                        <svg viewBox="0 0 500 200" class="w-100 h-100 position-absolute top-0 start-0 opacity-25" style="pointer-events: none;">
                            <path d="M 0 120 Q 100 130, 200 90 T 400 110 T 500 50" fill="none" stroke="var(--primary)" stroke-width="3"></path>
                            <path d="M 0 120 Q 100 130, 200 90 T 400 110 T 500 50 L 500 200 L 0 200 Z" fill="rgba(14, 165, 233, 0.05)"></path>
                            <line x1="0" y1="50" x2="500" y2="50" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            <line x1="0" y1="100" x2="500" y2="100" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            <line x1="0" y1="150" x2="500" y2="150" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        </svg>
                        <div class="text-center position-relative z-index-1">
                            <i class="bi bi-activity fs-3 text-secondary mb-1.5 d-block"></i>
                            <h6 class="text-dark fw-bold mb-1">Tren Suhu Historis</h6>
                            <p class="text-secondary small mb-0">Grafik Chart.js untuk fluktuasi cuaca akan dirender secara dinamis.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column: Detail Cuaca & Riwayat -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- Detail Cuaca Negara -->
                <div class="card p-4 border-0 shadow-sm" id="weather-detail-panel">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-sun-fill text-primary me-2"></i>Detail Cuaca Negara</h6>
                    
                    <div class="text-center pb-3 border-bottom mb-3">
                        <span id="wd-flag" class="fs-1 d-block mb-1">🇮🇩</span>
                        <h5 id="wd-name" class="fw-bold text-dark mb-1">Indonesia</h5>
                        <div class="d-flex align-items-center justify-content-center gap-1.5 mt-2">
                            <i class="bi bi-cloud-rain-heavy-fill text-primary fs-3" id="wd-icon"></i>
                            <h2 class="fw-bold text-dark mb-0 ms-1" id="wd-temp">28°C</h2>
                        </div>
                        <span id="wd-condition" class="badge badge-info mt-2">Hujan Ringan</span>
                    </div>

                    <div class="d-flex flex-column gap-2.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Kelembapan Udara</span>
                            <span id="wd-humidity" class="text-dark fw-semibold small">85%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Kecepatan Angin</span>
                            <span id="wd-wind" class="text-dark fw-semibold small">12 km/jam</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Tekanan Udara</span>
                            <span id="wd-pressure" class="text-dark fw-semibold small">1011 hPa</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Curah Hujan</span>
                            <span id="wd-rainfall" class="text-dark fw-semibold small">4.2 mm</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="text-secondary small">Kondisi Langit</span>
                            <span id="wd-sky" class="text-dark fw-semibold small">Mendung Tebal</span>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Cuaca (Timeline) -->
                <div class="card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Log Cuaca Buruk (Jalur Rantai Pasok)</h6>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="p-2.5 border rounded-3 bg-light" style="background-color: #F8FAFC !important;">
                            <span class="badge bg-danger text-light mb-1" style="font-size: 0.65rem;">Badai Salju</span>
                            <h6 class="fw-bold text-dark small mb-1">Badai Musim Dingin melanda Pantai Timur AS</h6>
                            <p class="text-secondary small mb-1" style="font-size: 0.775rem;">Operasional Port of New York ditunda sementara.</p>
                            <span class="text-secondary" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>3 jam yang lalu</span>
                        </div>
                        <div class="p-2.5 border rounded-3 bg-light" style="background-color: #F8FAFC !important;">
                            <span class="badge bg-warning text-dark mb-1" style="font-size: 0.65rem;">Kabut Tebal</span>
                            <h6 class="fw-bold text-dark small mb-1">Jarak Pandang Pelabuhan Tokyo Menurun</h6>
                            <p class="text-secondary small mb-1" style="font-size: 0.775rem;">Kecepatan kapal masuk dibatasi 5 knot demi keselamatan.</p>
                            <span class="text-secondary" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>12 jam yang lalu</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Row 6: Daftar Negara (Table) -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Pemantauan Kondisi Cuaca Negara</h6>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="weather-table">
                        <thead>
                            <tr>
                                <th>Negara</th>
                                <th>Suhu</th>
                                <th>Kondisi Cuaca</th>
                                <th>Kelembapan</th>
                                <th>Kecepatan Angin</th>
                                <th>Status Operasional Port</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="weather-row" data-id="ID" data-name="Indonesia" data-flag="🇮🇩" data-temp="28°C" data-cond="Hujan Ringan" data-icon="bi-cloud-rain-heavy-fill" data-humidity="85%" data-wind="12 km/jam" data-pressure="1011 hPa" data-rainfall="4.2 mm" data-sky="Mendung Tebal" data-continent="Asia" data-status="Normal">
                                <td class="fw-bold text-dark"><span class="me-2">🇮🇩</span>Indonesia</td>
                                <td>28°C</td>
                                <td><span class="badge badge-info">Hujan Ringan</span></td>
                                <td>85%</td>
                                <td>12 km/jam</td>
                                <td><span class="badge badge-success">Normal</span></td>
                                <td><button class="btn btn-light btn-sm border px-3 btn-select-weather">Pilih</button></td>
                            </tr>
                            <tr class="weather-row" data-id="SG" data-name="Singapura" data-flag="🇸🇬" data-temp="31°C" data-cond="Cerah" data-icon="bi-sun-fill" data-humidity="60%" data-wind="8 km/jam" data-pressure="1013 hPa" data-rainfall="0.0 mm" data-sky="Cerah Berawan" data-continent="Asia" data-status="Normal">
                                <td class="fw-bold text-dark"><span class="me-2">🇸🇬</span>Singapura</td>
                                <td>31°C</td>
                                <td><span class="badge badge-success">Cerah</span></td>
                                <td>60%</td>
                                <td>8 km/jam</td>
                                <td><span class="badge badge-success">Normal</span></td>
                                <td><button class="btn btn-light btn-sm border px-3 btn-select-weather">Pilih</button></td>
                            </tr>
                            <tr class="weather-row" data-id="JP" data-name="Jepang" data-flag="🇯🇵" data-temp="25°C" data-cond="Berawan" data-icon="bi-cloud-sun-fill" data-humidity="72%" data-wind="15 km/jam" data-pressure="1009 hPa" data-rainfall="0.5 mm" data-sky="Awan Tersebar" data-continent="Asia" data-status="Normal">
                                <td class="fw-bold text-dark"><span class="me-2">🇯🇵</span>Jepang</td>
                                <td>25°C</td>
                                <td><span class="badge badge-info">Berawan</span></td>
                                <td>72%</td>
                                <td>15 km/jam</td>
                                <td><span class="badge badge-success">Normal</span></td>
                                <td><button class="btn btn-light btn-sm border px-3 btn-select-weather">Pilih</button></td>
                            </tr>
                            <tr class="weather-row" data-id="US" data-name="Amerika Serikat" data-flag="🇺🇸" data-temp="-2°C" data-cond="Badai Salju" data-icon="bi-snow" data-humidity="95%" data-wind="45 km/jam" data-pressure="998 hPa" data-rainfall="25.0 mm" data-sky="Badai Salju Kritis" data-continent="Amerika" data-status="Ekstrem">
                                <td class="fw-bold text-dark"><span class="me-2">🇺🇸</span>Amerika Serikat</td>
                                <td>-2°C</td>
                                <td><span class="badge badge-danger">Badai Salju</span></td>
                                <td>95%</td>
                                <td>45 km/jam</td>
                                <td><span class="badge badge-danger">Tertunda</span></td>
                                <td><button class="btn btn-light btn-sm border px-3 btn-select-weather">Pilih</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.weather-row');
        const searchInput = document.getElementById('search-weather-country');
        const selectCountry = document.getElementById('select-weather-country');
        const selectContinent = document.getElementById('select-continent');
        const selectCondition = document.getElementById('select-condition');
        const btnReset = document.getElementById('btn-reset-filters');
        const btnRefresh = document.getElementById('btn-refresh-weather');

        // Detail elements
        const wdFlag = document.getElementById('wd-flag');
        const wdName = document.getElementById('wd-name');
        const wdTemp = document.getElementById('wd-temp');
        const wdIcon = document.getElementById('wd-icon');
        const wdCondition = document.getElementById('wd-condition');
        const wdHumidity = document.getElementById('wd-humidity');
        const wdWind = document.getElementById('wd-wind');
        const wdPressure = document.getElementById('wd-pressure');
        const wdRainfall = document.getElementById('wd-rainfall');
        const wdSky = document.getElementById('wd-sky');

        // Function to update detail panel
        function selectWeather(row) {
            const data = row.dataset;
            wdFlag.textContent = data.flag;
            wdName.textContent = data.name;
            wdTemp.textContent = data.temp;
            wdCondition.textContent = data.cond;

            // Class icon set
            wdIcon.className = 'bi ' + data.icon + ' fs-3';
            if (data.status === 'Ekstrem') {
                wdIcon.style.color = 'var(--danger)';
                wdCondition.className = 'badge badge-danger';
            } else if (data.cond === 'Cerah') {
                wdIcon.style.color = 'var(--warning)';
                wdCondition.className = 'badge badge-success';
            } else {
                wdIcon.style.color = 'var(--primary)';
                wdCondition.className = 'badge badge-info';
            }

            wdHumidity.textContent = data.humidity;
            wdWind.textContent = data.wind;
            wdPressure.textContent = data.pressure;
            wdRainfall.textContent = data.rainfall;
            wdSky.textContent = data.sky;
        }

        // Click row events
        rows.forEach(row => {
            row.querySelector('.btn-select-weather').addEventListener('click', function(e) {
                e.stopPropagation();
                selectWeather(row);
                rows.forEach(r => r.classList.remove('table-primary'));
                row.classList.add('table-primary');
            });
            row.addEventListener('click', function() {
                selectWeather(row);
                rows.forEach(r => r.classList.remove('table-primary'));
                row.classList.add('table-primary');
            });
        });

        // Filter event listeners
        searchInput.addEventListener('input', filterWeather);
        selectCountry.addEventListener('change', filterWeather);
        selectContinent.addEventListener('change', filterWeather);
        selectCondition.addEventListener('change', filterWeather);

        function filterWeather() {
            const query = searchInput.value.toLowerCase();
            const countryId = selectCountry.value;
            const continent = selectContinent.value;
            const condition = selectCondition.value;

            rows.forEach(row => {
                const name = row.dataset.name.toLowerCase();
                const id = row.dataset.id;
                const cont = row.dataset.continent;
                const cond = row.dataset.cond;
                const status = row.dataset.status;

                let matchesSearch = name.includes(query);
                let matchesCountry = (countryId === 'all' || id === countryId);
                let matchesContinent = (continent === 'all' || cont === continent);
                
                let matchesCondition = true;
                if (condition === 'Ekstrem') {
                    matchesCondition = (status === 'Ekstrem');
                } else if (condition !== 'all') {
                    matchesCondition = cond.includes(condition);
                }

                if (matchesSearch && matchesCountry && matchesContinent && matchesCondition) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Reset button
        btnReset.addEventListener('click', function() {
            searchInput.value = '';
            selectCountry.value = 'all';
            selectContinent.value = 'all';
            selectCondition.value = 'all';
            filterWeather();
        });

        // Refresh button mock
        btnRefresh.addEventListener('click', function() {
            btnRefresh.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Memuat...';
            setTimeout(() => {
                btnRefresh.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Data';
                alert('Sinkronisasi data cuaca Open-Meteo API berhasil disimulasikan!');
            }, 1000);
        });
    });
</script>
<style>
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
