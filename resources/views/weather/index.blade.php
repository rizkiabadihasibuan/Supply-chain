@extends('layouts.user.app')

@section('title', 'Global Weather Center - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Breadcrumb & Header Action -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Global Weather Center</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Global Weather Center</h3>
                        <p class="text-secondary small mb-0">Pantau kondisi cuaca berbagai negara untuk membantu analisis risiko rantai pasok global.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" id="btn-refresh-weather" onclick="triggerRefreshAnimation()" style="min-height: 44px;">
                            <i class="bi bi-arrow-clockwise me-2"></i>Sinkronisasi Cuaca
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Weather KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- 1. Negara Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1">195</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;">Cakupan Stasiun Cuaca</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 2. Cuaca Ekstrem -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Cuaca Ekstrem</span>
                    <h3 class="fw-bold text-danger mb-1" id="kpi-extremes">3</h3>
                    <span class="text-danger small d-block" style="font-size: 0.725rem;"><i class="bi bi-cloud-lightning-rain-fill me-1"></i>Pelabuhan Terdampak</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-cloud-lightning fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 3. Suhu Rata-rata Global -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Suhu Rata-rata Global</span>
                    <h3 class="fw-bold text-dark mb-1">24.5°C</h3>
                    <span class="text-success small d-block" style="font-size: 0.725rem;"><i class="bi bi-check-circle-fill me-1"></i>Kondisi Normal</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-thermometer-half fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 4. Peringatan Cuaca -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Peringatan Cuaca</span>
                    <h3 class="fw-bold text-warning mb-1" id="kpi-warnings">12</h3>
                    <span class="text-warning small d-block" style="font-size: 0.725rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Satelit Cuaca Aktif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters Container -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <div class="search-wrapper w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-weather-input" placeholder="Cari negara atau kota..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyFilters()">
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-region" class="form-select" style="min-height: 44px;" onchange="applyFilters()">
                            <option value="all">Semua Benua</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="america">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Condition Filter -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-condition" class="form-select" style="min-height: 44px;" onchange="applyFilters()">
                            <option value="all">Semua Kondisi Cuaca</option>
                            <option value="cerah">Cerah</option>
                            <option value="berawan">Berawan</option>
                            <option value="hujan">Hujan</option>
                            <option value="badai">Badai Ekstrem</option>
                        </select>
                    </div>

                    <!-- Temp Filter -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                        <select id="filter-temp" class="form-select" style="min-height: 44px;" onchange="applyFilters()">
                            <option value="all">Semua Tingkat Suhu</option>
                            <option value="hot">Suhu Tinggi (>30°C)</option>
                            <option value="mild">Suhu Sedang (15°C - 30°C)</option>
                            <option value="cold">Suhu Dingin (<15°C)</option>
                        </select>
                    </div>
                </div>

                <!-- Simulation bar -->
                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                    <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateSkeletonLoading()">
                        <i class="bi bi-hourglass-split me-2"></i>Simulasikan Loading
                    </button>
                    <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateEmptyState()">
                        <i class="bi bi-x-circle me-2"></i>Simulasikan Data Kosong
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Skeleton Loading Container -->
    <div id="skeleton-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                <div class="card p-4 border-0 skeleton-card" style="height: 380px;">
                    <div class="skeleton-shimmer" style="width: 100%; height: 100%; border-radius: 8px;"></div>
                </div>
                <div class="card p-4 border-0 skeleton-card" style="height: 200px;">
                    <div class="skeleton-shimmer mb-3" style="width: 40%; height: 24px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 100%; height: 120px; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 border-0 skeleton-card" style="height: 600px;">
                <div class="skeleton-shimmer mb-4" style="width: 50%; height: 24px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer mb-3" style="width: 100%; height: 100px; border-radius: 12px;"></div>
                <div class="skeleton-shimmer mb-3" style="width: 100%; height: 120px; border-radius: 12px;"></div>
                <div class="skeleton-shimmer" style="width: 100%; height: 180px; border-radius: 12px;"></div>
            </div>
        </div>
    </div>

    <!-- Empty State Container -->
    <div id="empty-state-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                <svg viewBox="0 0 200 120" style="width: 160px; height: 100px;" class="mb-3.5">
                    <rect x="30" y="30" width="140" height="70" rx="12" fill="none" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
                    <circle cx="100" cy="65" r="20" fill="rgba(6, 182, 212, 0.05)" stroke="rgba(6, 182, 212, 0.2)" stroke-width="1.5" />
                    <path d="M100,25 C115,25 125,35 125,45 C125,50 115,65 100,85 C85,65 75,50 75,45 C75,35 85,25 100,25 Z" fill="none" stroke="#CBD5E1" stroke-width="1.5" stroke-dasharray="3 3" />
                    <path d="M92,60 L108,68 M108,60 L92,68" stroke="var(--danger)" stroke-width="2" stroke-linecap="round" />
                </svg>
                <h5 class="fw-bold text-dark mb-1">Belum ada data cuaca.</h5>
                <p class="text-secondary small mb-3.5" style="max-width: 320px;">Sesuaikan kata kunci pencarian Anda untuk memantau data iklim port.</p>
                <button class="btn btn-primary px-4" style="min-height: 44px;" onclick="resetFilters()">Reset Filter</button>
            </div>
        </div>
    </div>

    <!-- Main Content Layout Grid -->
    <div id="main-content-grid" class="row g-4">
        <!-- Kolom Kiri (Peta, Overview, Forecast, Alerts) -->
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                
                <!-- SECTION 1: Global Weather Map -->
                <div class="card p-4 border-0">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-map text-primary me-2"></i>Radar & Kondisi Cuaca Pelabuhan Global</h5>
                            <p class="text-secondary small mb-0">Tekan titik pelabuhan untuk melihat laporan cuaca terperinci.</p>
                        </div>
                        <!-- Layer Filters -->
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-secondary border px-3 py-2 d-flex align-items-center"><i class="bi bi-layers me-1.5 text-primary"></i>Layer: Pelabuhan</span>
                        </div>
                    </div>

                    <!-- Interactive Weather Map Area -->
                    <div class="position-relative border rounded-4 overflow-hidden d-flex align-items-center justify-content-center" style="height: 420px; background-color: #FAFCFF !important; background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px); background-size: 24px 24px;">
                        
                        <!-- Zoom controls -->
                        <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1.5" style="z-index: 10;">
                            <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomWeatherMap(1.2)" style="width: 36px; height: 36px; min-height: 36px;">
                                <i class="bi bi-zoom-in"></i>
                            </button>
                            <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomWeatherMap(0.8)" style="width: 36px; height: 36px; min-height: 36px;">
                                <i class="bi bi-zoom-out"></i>
                            </button>
                            <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="resetWeatherMapZoom()" style="width: 36px; height: 36px; min-height: 36px;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>

                        <!-- Legend -->
                        <div class="position-absolute bottom-0 start-0 m-3 bg-white p-2.5 rounded-3 border d-flex flex-column gap-1" style="z-index: 10; font-size: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                            <span class="fw-bold text-dark mb-1">Iklim Radar</span>
                            <div class="d-flex align-items-center"><i class="bi bi-sun-fill text-warning me-1.5"></i> Cerah</div>
                            <div class="d-flex align-items-center"><i class="bi bi-cloud-sun-fill text-primary me-1.5"></i> Berawan</div>
                            <div class="d-flex align-items-center"><i class="bi bi-cloud-rain-heavy-fill text-primary me-1.5"></i> Hujan</div>
                            <div class="d-flex align-items-center"><i class="bi bi-cloud-lightning-rain-fill text-danger me-1.5"></i> Badai Ekstrem</div>
                        </div>

                        <!-- SVG Map -->
                        <div id="weather-map-container" class="w-100 h-100 d-flex align-items-center justify-content-center" style="transition: transform 0.3s ease; transform-origin: center;">
                            <svg viewBox="0 0 1000 500" class="w-100 h-100" style="max-height: 400px;">
                                <g fill="#E2E8F0" stroke="#FFFFFF" stroke-width="1.5">
                                    <path d="M100,80 L200,60 L280,100 L250,180 L200,200 L150,150 Z" />
                                    <path d="M250,220 L320,250 L280,380 L240,420 L220,300 Z" />
                                    <path d="M300,30 L380,20 L350,70 L280,60 Z" />
                                    <path d="M450,60 L600,40 L850,50 L900,120 L800,280 L700,250 L600,280 L520,220 L420,120 Z" />
                                    <path d="M460,180 L560,160 L630,220 L580,350 L520,380 L480,260 Z" />
                                    <path d="M780,320 L880,300 L850,380 L760,360 Z" />
                                </g>

                                <!-- Weather Nodes -->
                                <g id="weather-markers">
                                    <!-- Tanjung Priok -->
                                    <g class="weather-marker" data-city="Jakarta" data-country="Indonesia" data-port="Tanjung Priok" data-temp="28" data-cond="Hujan" data-icon="bi-cloud-rain-heavy-fill" data-hum="85" data-wind="12" data-press="1011" data-rain="4.2" data-uv="3" data-impact="low" data-region="asia" onclick="selectWeatherNode(this)">
                                        <circle cx="570" cy="220" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="570" cy="220" r="5" fill="var(--primary)" />
                                    </g>
                                    <!-- Singapore -->
                                    <g class="weather-marker" data-city="Singapura" data-country="Singapura" data-port="Port of Singapore" data-temp="31" data-cond="Cerah" data-icon="bi-sun-fill" data-hum="60" data-wind="8" data-press="1013" data-rain="0.0" data-uv="9" data-impact="low" data-region="asia" onclick="selectWeatherNode(this)">
                                        <circle cx="650" cy="230" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="650" cy="230" r="5" fill="var(--primary)" />
                                    </g>
                                    <!-- Shanghai -->
                                    <g class="weather-marker" data-city="Shanghai" data-country="China" data-port="Port of Shanghai" data-temp="24" data-cond="Badai" data-icon="bi-cloud-lightning-rain-fill" data-hum="95" data-wind="45" data-press="998" data-rain="25.0" data-uv="1" data-impact="high" data-region="asia" onclick="selectWeatherNode(this)">
                                        <circle cx="780" cy="120" r="18" fill="rgba(239, 68, 68, 0.2)" stroke="var(--danger)" stroke-width="1.5" class="weather-pulse" />
                                        <circle cx="780" cy="120" r="6" fill="var(--danger)" />
                                    </g>
                                    <!-- Rotterdam -->
                                    <g class="weather-marker" data-city="Rotterdam" data-country="Belanda" data-port="Port of Rotterdam" data-temp="17" data-cond="Berawan" data-icon="bi-cloud-sun-fill" data-hum="70" data-wind="22" data-press="1012" data-rain="0.5" data-uv="4" data-impact="medium" data-region="europe" onclick="selectWeatherNode(this)">
                                        <circle cx="520" cy="130" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="520" cy="130" r="5" fill="var(--primary)" />
                                    </g>
                                    <!-- Los Angeles -->
                                    <g class="weather-marker" data-city="Los Angeles" data-country="Amerika Serikat" data-port="Port of Los Angeles" data-temp="21" data-cond="Mendung" data-icon="bi-cloud-fill" data-hum="65" data-wind="10" data-press="1015" data-rain="0.0" data-uv="6" data-impact="low" data-region="america" onclick="selectWeatherNode(this)">
                                        <circle cx="150" cy="110" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="150" cy="110" r="5" fill="var(--primary)" />
                                    </g>
                                    <!-- New York -->
                                    <g class="weather-marker" data-city="New York" data-country="Amerika Serikat" data-port="Port of New York" data-temp="25" data-cond="Cerah" data-icon="bi-sun-fill" data-hum="55" data-wind="15" data-press="1014" data-rain="0.0" data-uv="7" data-impact="low" data-region="america" onclick="selectWeatherNode(this)">
                                        <circle cx="180" cy="130" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="180" cy="130" r="5" fill="var(--primary)" />
                                    </g>
                                    <!-- Santos -->
                                    <g class="weather-marker" data-city="Santos" data-country="Brasil" data-port="Port of Santos" data-temp="26" data-cond="Hujan" data-icon="bi-cloud-rain-heavy-fill" data-hum="80" data-wind="18" data-press="1010" data-rain="5.5" data-uv="5" data-impact="low" data-region="america" onclick="selectWeatherNode(this)">
                                        <circle cx="270" cy="260" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="270" cy="260" r="5" fill="var(--primary)" />
                                    </g>
                                    <!-- Durban -->
                                    <g class="weather-marker" data-city="Durban" data-country="Afrika Selatan" data-port="Port of Durban" data-temp="19" data-cond="Berawan" data-icon="bi-cloud-sun-fill" data-hum="72" data-wind="14" data-press="1016" data-rain="0.2" data-uv="4" data-impact="low" data-region="africa" onclick="selectWeatherNode(this)">
                                        <circle cx="500" cy="240" r="14" fill="rgba(37, 99, 235, 0.15)" stroke="var(--primary)" stroke-width="1" />
                                        <circle cx="500" cy="240" r="5" fill="var(--primary)" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Weather Overview -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Rincian Parameter Meteorologi</h5>
                    <p class="text-secondary small mb-4">Informasi mendetail cuaca teritorial untuk pelabuhan yang dipilih.</p>
                    
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                                <i class="bi bi-thermometer-half text-danger fs-3 mb-1.5 d-block"></i>
                                <span class="text-secondary small d-block mb-1">Suhu</span>
                                <h6 class="fw-bold text-dark mb-0" id="ov-temp">28°C</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                                <i class="bi bi-moisture text-info fs-3 mb-1.5 d-block"></i>
                                <span class="text-secondary small d-block mb-1">Kelembapan</span>
                                <h6 class="fw-bold text-dark mb-0" id="ov-humidity">85%</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                                <i class="bi bi-wind text-primary fs-3 mb-1.5 d-block"></i>
                                <span class="text-secondary small d-block mb-1">Angin</span>
                                <h6 class="fw-bold text-dark mb-0" id="ov-wind">12 km/j</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                                <i class="bi bi-speedometer text-success fs-3 mb-1.5 d-block"></i>
                                <span class="text-secondary small d-block mb-1">Tekanan</span>
                                <h6 class="fw-bold text-dark mb-0" id="ov-pressure">1011 hPa</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                                <i class="bi bi-cloud-drizzle text-primary fs-3 mb-1.5 d-block"></i>
                                <span class="text-secondary small d-block mb-1">Curah Hujan</span>
                                <h6 class="fw-bold text-dark mb-0" id="ov-rainfall">4.2 mm</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                                <i class="bi bi-sun text-warning fs-3 mb-1.5 d-block"></i>
                                <span class="text-secondary small d-block mb-1">Indeks UV</span>
                                <h6 class="fw-bold text-dark mb-0" id="ov-uv">3 / Sangat Rendah</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Forecast (7 Days Horizontal Scrollable) -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-calendar3 text-primary me-2"></i>Prakiraan Cuaca 7 Hari Mendatang</h5>
                    <p class="text-secondary small mb-3">Geser ke kanan untuk melihat proyeksi cuaca 1 minggu ke depan.</p>
                    
                    <!-- Horizontal Scroll Container -->
                    <div class="d-flex flex-nowrap gap-3 pb-2 forecast-scroll-container overflow-x-auto">
                        <!-- Day 1 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Sabtu</span>
                            <i class="bi bi-cloud-rain-fill text-primary fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">28°C / 24°C</h6>
                            <span class="badge badge-info" style="font-size: 0.6rem;">Hujan</span>
                        </div>
                        <!-- Day 2 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Minggu</span>
                            <i class="bi bi-cloud-sun-fill text-primary fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">29°C / 25°C</h6>
                            <span class="badge badge-info" style="font-size: 0.6rem;">Berawan</span>
                        </div>
                        <!-- Day 3 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Senin</span>
                            <i class="bi bi-sun-fill text-warning fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">31°C / 26°C</h6>
                            <span class="badge badge-success" style="font-size: 0.6rem;">Cerah</span>
                        </div>
                        <!-- Day 4 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Selasa</span>
                            <i class="bi bi-sun-fill text-warning fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">32°C / 26°C</h6>
                            <span class="badge badge-success" style="font-size: 0.6rem;">Cerah</span>
                        </div>
                        <!-- Day 5 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Rabu</span>
                            <i class="bi bi-cloud-rain-heavy-fill text-primary fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">27°C / 23°C</h6>
                            <span class="badge badge-info" style="font-size: 0.6rem;">Hujan Lebat</span>
                        </div>
                        <!-- Day 6 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Kamis</span>
                            <i class="bi bi-cloud-lightning-rain-fill text-danger fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">26°C / 22°C</h6>
                            <span class="badge badge-danger" style="font-size: 0.6rem;">Badai</span>
                        </div>
                        <!-- Day 7 -->
                        <div class="card p-3 border text-center flex-shrink-0" style="width: 110px; background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block mb-1">Jumat</span>
                            <i class="bi bi-cloud-sun-fill text-primary fs-3 mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">29°C / 24°C</h6>
                            <span class="badge badge-info" style="font-size: 0.6rem;">Berawan</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Extreme Weather Alert -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-charge-fill text-danger me-2"></i>Peringatan Cuaca Buruk Koridor Logistik</h5>
                    
                    <div class="d-flex flex-column gap-3">
                        <!-- Alert 1 -->
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between alert-item" data-name="Amerika Serikat" data-city="New York" data-cond="badai">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-snow2 text-primary fs-3 me-3"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Amerika Serikat | Badai Salju Kritis (Port of New York)</h6>
                                    <p class="text-secondary small mb-0">Penundaan bongkar kargo kontainer selama 24 jam ke depan.</p>
                                </div>
                            </div>
                            <span class="badge bg-danger text-white px-2.5 py-1.5 rounded-pill">Sangat Tinggi</span>
                        </div>

                        <!-- Alert 2 -->
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between alert-item" data-name="China" data-city="Shanghai" data-cond="badai">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cloud-lightning-rain-fill text-danger fs-3 me-3"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">China | Badai Tropis Ekstrem (Port of Shanghai)</h6>
                                    <p class="text-secondary small mb-0">Kapal dilarang sandar di dermaga utama karena angin kencang (45 knot).</p>
                                </div>
                            </div>
                            <span class="badge bg-danger text-white px-2.5 py-1.5 rounded-pill">Kritis</span>
                        </div>

                        <!-- Alert 3 -->
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between alert-item" data-name="Afrika Selatan" data-city="Durban" data-cond="cerah">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-thermometer-sun text-warning fs-3 me-3"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Afrika Selatan | Gelombang Panas Menghambat Operasional (Port of Durban)</h6>
                                    <p class="text-secondary small mb-0">Pembatasan jam kerja buruh pelabuhan di luar ruangan demi kesehatan.</p>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill">Sedang</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Kolom Kanan (Widgets) -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- WIDGET 1: Current Weather Widget -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Stasiun Terpilih</h5>
                    
                    <div class="text-center pb-3 border-bottom mb-3">
                        <span id="cur-modal-flag" class="fs-1 d-block mb-1">🇮🇩</span>
                        <h5 id="cur-modal-city" class="fw-bold text-dark mb-1">Jakarta</h5>
                        <span id="cur-modal-port" class="text-secondary small d-block mb-2">Tanjung Priok, Indonesia</span>
                        <div class="d-flex align-items-center justify-content-center gap-1.5">
                            <i id="cur-modal-icon" class="bi bi-cloud-rain-heavy-fill text-primary fs-2"></i>
                            <h2 id="cur-modal-temp" class="fw-bold text-dark mb-0 ms-1">28°C</h2>
                        </div>
                        <span id="cur-modal-status" class="badge badge-info mt-2">Hujan</span>
                    </div>

                    <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Kelembapan:</span>
                            <span id="cur-modal-humidity" class="text-dark fw-semibold">85%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Kecepatan Angin:</span>
                            <span id="cur-modal-wind" class="text-dark fw-semibold">12 km/jam</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Tekanan Udara:</span>
                            <span id="cur-modal-pressure" class="text-dark fw-semibold">1011 hPa</span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 2: Weather Impact Badge Indicator -->
                <div class="card p-4 border-0 text-center">
                    <h5 class="fw-bold text-dark mb-3 text-start"><i class="bi bi-shield-exclamation text-primary me-2"></i>Dampak Rantai Pasok</h5>
                    <p class="text-secondary small text-start mb-3">Penilaian gangguan cuaca saat ini terhadap rute kapal kontainer logistik.</p>
                    
                    <div class="p-3 border rounded-4" id="cur-impact-box" style="background-color: rgba(34, 197, 94, 0.06); border-color: rgba(34, 197, 94, 0.15) !important;">
                        <span class="fw-bold text-success d-block mb-1" id="cur-impact-title"><i class="bi bi-check-circle-fill me-1.5"></i>Dampak Rendah (Aman)</span>
                        <p class="text-secondary small mb-0" id="cur-impact-desc">Jalur pelayaran laut dan penumpukan logistik gudang berjalan normal tanpa gangguan.</p>
                    </div>
                </div>

                <!-- WIDGET 3: Top 5 Hottest & Top 5 Coldest -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-thermometer-high text-primary me-2"></i>Ekstrem Suhu Global</h5>
                    
                    <!-- Hottest -->
                    <div class="mb-3.5">
                        <h6 class="fw-bold text-danger mb-2.5" style="font-size: 0.85rem;"><i class="bi bi-caret-up-fill me-1"></i>Top 5 Terpanas</h6>
                        <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇸🇩 Sudan (Khartoum)</span><span class="text-danger fw-bold">42°C</span></div>
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇾🇪 Yaman (Sana'a)</span><span class="text-danger fw-bold">40°C</span></div>
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇸🇦 Arab Saudi (Riyadh)</span><span class="text-danger fw-bold">39°C</span></div>
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇪🇬 Mesir (Kairo)</span><span class="text-danger fw-bold">38°C</span></div>
                            <div class="d-flex justify-content-between"><span>🇮🇶 Irak (Baghdad)</span><span class="text-danger fw-bold">37°C</span></div>
                        </div>
                    </div>

                    <!-- Coldest -->
                    <div class="border-top pt-3">
                        <h6 class="fw-bold text-primary mb-2.5" style="font-size: 0.85rem;"><i class="bi bi-caret-down-fill me-1"></i>Top 5 Terdingin</h6>
                        <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇷🇺 Rusia (Siberia)</span><span class="text-primary fw-bold">-15°C</span></div>
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇨🇦 Kanada (Nunavut)</span><span class="text-primary fw-bold">-10°C</span></div>
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇬🇱 Greenland (Nuuk)</span><span class="text-primary fw-bold">-8°C</span></div>
                            <div class="d-flex justify-content-between border-bottom pb-1"><span>🇳🇴 Norwegia (Oslo)</span><span class="text-primary fw-bold">-2°C</span></div>
                            <div class="d-flex justify-content-between"><span>🇮🇸 Islandia (Reykjavik)</span><span class="text-primary fw-bold">-1°C</span></div>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 4: Recent Weather Update -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Log Pembaruan Cuaca</h5>
                    
                    <div style="position: relative; padding-left: 20px;">
                        <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
                        
                        <div class="position-relative mb-3.5">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--success); border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Open-Meteo API Sukses</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Sinkronisasi cuaca Port of Singapore diperbarui.</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>Baru saja</span>
                            </div>
                        </div>

                        <div class="position-relative">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--danger); border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Peringatan Badai Aktif</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Pemberitahuan Badai Shanghai diteruskan ke kapal.</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>30 menit yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial simulated loading transition
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    });

    // Map Zooming Simulation
    let mapZoomScale = 1;
    function zoomWeatherMap(factor) {
        mapZoomScale *= factor;
        if (mapZoomScale < 0.6) mapZoomScale = 0.6;
        if (mapZoomScale > 3.0) mapZoomScale = 3.0;
        document.getElementById('weather-map-container').style.transform = `scale(${mapZoomScale})`;
    }

    function resetWeatherMapZoom() {
        mapZoomScale = 1;
        document.getElementById('weather-map-container').style.transform = `scale(1)`;
    }

    // Node Selection & Sidebar Widget Update
    function selectWeatherNode(node) {
        const city = node.getAttribute('data-city');
        const country = node.getAttribute('data-country');
        const port = node.getAttribute('data-port');
        const temp = node.getAttribute('data-temp');
        const cond = node.getAttribute('data-cond');
        const icon = node.getAttribute('data-icon');
        const hum = node.getAttribute('data-hum');
        const wind = node.getAttribute('data-wind');
        const press = node.getAttribute('data-press');
        const rain = node.getAttribute('data-rain');
        const uv = node.getAttribute('data-uv');
        const impact = node.getAttribute('data-impact');

        // Flag matcher
        let flag = '🌍';
        if (country.includes('Indonesia')) flag = '🇮🇩';
        if (country.includes('Singapura')) flag = '🇸🇬';
        if (country.includes('China')) flag = '🇨🇳';
        if (country.includes('Belanda')) flag = '🇳🇱';
        if (country.includes('Amerika')) flag = '🇺🇸';
        if (country.includes('Brasil')) flag = '🇧🇷';
        if (country.includes('Afrika')) flag = '🇿🇦';

        // Update current weather widget
        document.getElementById('cur-modal-flag').textContent = flag;
        document.getElementById('cur-modal-city').textContent = city;
        document.getElementById('cur-modal-port').textContent = `${port}, ${country}`;
        document.getElementById('cur-modal-icon').className = `bi ${icon} text-primary fs-2`;
        document.getElementById('cur-modal-temp').textContent = `${temp}°C`;
        
        const statusBadge = document.getElementById('cur-modal-status');
        statusBadge.textContent = cond;
        if (cond === 'Badai') {
            statusBadge.className = 'badge badge-danger';
            document.getElementById('cur-modal-icon').className = `bi ${icon} text-danger fs-2`;
        } else if (cond === 'Cerah') {
            statusBadge.className = 'badge badge-success';
            document.getElementById('cur-modal-icon').className = `bi ${icon} text-warning fs-2`;
        } else {
            statusBadge.className = 'badge badge-info';
        }

        document.getElementById('cur-modal-humidity').textContent = `${hum}%`;
        document.getElementById('cur-modal-wind').textContent = `${wind} km/jam`;
        document.getElementById('cur-modal-pressure').textContent = `${press} hPa`;

        // Update meteorological overview row
        document.getElementById('ov-temp').textContent = `${temp}°C`;
        document.getElementById('ov-humidity').textContent = `${hum}%`;
        document.getElementById('ov-wind').textContent = `${wind} km/j`;
        document.getElementById('ov-pressure').textContent = `${press} hPa`;
        document.getElementById('ov-rainfall').textContent = `${rain} mm`;
        document.getElementById('ov-uv').textContent = `${uv} / ` + (uv > 7 ? 'Sangat Tinggi' : (uv > 4 ? 'Sedang' : 'Rendah'));

        // Update Weather Impact Badge indicator widget
        const impactBox = document.getElementById('cur-impact-box');
        const impactTitle = document.getElementById('cur-impact-title');
        const impactDesc = document.getElementById('cur-impact-desc');

        if (impact === 'high') {
            impactBox.style.backgroundColor = 'rgba(239, 68, 68, 0.06)';
            impactBox.style.borderColor = 'rgba(239, 68, 68, 0.15)';
            impactTitle.className = 'fw-bold text-danger d-block mb-1';
            impactTitle.innerHTML = '<i class="bi bi-exclamation-octagon-fill me-1.5"></i>Dampak Tinggi (Bahaya)';
            impactDesc.textContent = 'Cuaca buruk ekstrem. Jalur pelayaran ditunda dan proses bongkar muat kargo pelabuhan dihentikan sementara.';
        } else if (impact === 'medium') {
            impactBox.style.backgroundColor = 'rgba(245, 158, 11, 0.06)';
            impactBox.style.borderColor = 'rgba(245, 158, 11, 0.15)';
            impactTitle.className = 'fw-bold text-warning d-block mb-1';
            impactTitle.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1.5"></i>Dampak Sedang (Hambatan)';
            impactDesc.textContent = 'Kecepatan kapal masuk dibatasi. Terdapat potensi waktu antrean truk penjemputan kontainer bertambah panjang.';
        } else {
            impactBox.style.backgroundColor = 'rgba(34, 197, 94, 0.06)';
            impactBox.style.borderColor = 'rgba(34, 197, 94, 0.15)';
            impactTitle.className = 'fw-bold text-success d-block mb-1';
            impactTitle.innerHTML = '<i class="bi bi-check-circle-fill me-1.5"></i>Dampak Rendah (Aman)';
            impactDesc.textContent = 'Jalur pelayaran laut dan penumpukan logistik gudang berjalan normal tanpa gangguan.';
        }
    }

    // Client-side Filters & Search
    function applyFilters() {
        const query = document.getElementById('search-weather-input').value.toLowerCase();
        const region = document.getElementById('filter-region').value;
        const condition = document.getElementById('filter-condition').value;
        const tempRange = document.getElementById('filter-temp').value;

        const markers = Array.from(document.querySelectorAll('.weather-marker'));
        const alertItems = Array.from(document.querySelectorAll('.alert-item'));
        
        let visibleCount = 0;

        markers.forEach(marker => {
            const city = marker.getAttribute('data-city').toLowerCase();
            const country = marker.getAttribute('data-country').toLowerCase();
            const port = marker.getAttribute('data-port').toLowerCase();
            const markerRegion = marker.getAttribute('data-region');
            const cond = marker.getAttribute('data-cond').toLowerCase();
            const temp = parseInt(marker.getAttribute('data-temp'));

            const matchesSearch = city.includes(query) || country.includes(query) || port.includes(query);
            const matchesRegion = (region === 'all' || markerRegion === region);
            const matchesCondition = (condition === 'all' || cond === condition);
            
            let matchesTemp = true;
            if (tempRange === 'hot') matchesTemp = temp > 30;
            else if (tempRange === 'mild') matchesTemp = temp >= 15 && temp <= 30;
            else if (tempRange === 'cold') matchesTemp = temp < 15;

            if (matchesSearch && matchesRegion && matchesCondition && matchesTemp) {
                marker.style.display = 'block';
                visibleCount++;
            } else {
                marker.style.display = 'none';
            }
        });

        // Filter alerts list too for visual consistency
        alertItems.forEach(item => {
            const alertName = item.getAttribute('data-name').toLowerCase();
            const alertCity = item.getAttribute('data-city').toLowerCase();
            const alertCond = item.getAttribute('data-cond').toLowerCase();

            const matchesSearch = alertName.includes(query) || alertCity.includes(query);
            const matchesCondition = (condition === 'all' || alertCond === condition);

            if (matchesSearch && matchesCondition) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });

        // Toggle Grid or Empty State
        const grid = document.getElementById('main-content-grid');
        const emptyState = document.getElementById('empty-state-container');

        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyState.style.display = 'none';
        }
    }

    // Refresh simulation trigger
    function triggerRefreshAnimation() {
        const btn = document.getElementById('btn-refresh-weather');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Sinkronisasi...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Sinkronisasi Cuaca';
            btn.disabled = false;
            alert('Pembaruan data cuaca Open-Meteo API berhasil disimulasikan!');
        }, 1200);
    }

    // Skeleton loader simulation
    function simulateSkeletonLoading() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'flex';

        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
            applyFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-weather-input').value = 'KotaXyzTidakAda';
        applyFilters();
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('search-weather-input').value = '';
        document.getElementById('filter-region').value = 'all';
        document.getElementById('filter-condition').value = 'all';
        document.getElementById('filter-temp').value = 'all';
        applyFilters();
    }
</script>

<style>
    /* Forecast scroll style */
    .forecast-scroll-container {
        scrollbar-width: thin;
        scrollbar-color: var(--border-color) transparent;
        padding-bottom: 8px;
    }

    /* Map pulses for extreme warning nodes */
    .weather-pulse {
        animation: weather-pulse-ring 1.8s infinite;
    }

    @keyframes weather-pulse-ring {
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

    /* Shimmer cards */
    .skeleton-card {
        background-color: #FFFFFF;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color) !important;
        border-radius: var(--radius-custom) !important;
    }

    .skeleton-shimmer {
        background: #F1F5F9;
        background-image: linear-gradient(to right, #F1F5F9 0%, #E2E8F0 20%, #F1F5F9 40%, #F1F5F9 100%);
        background-repeat: no-repeat;
        background-size: 800px 100%;
        display: inline-block;
        position: relative;
        animation: shimmer-animation 1.5s linear infinite forwards;
    }

    @keyframes shimmer-animation {
        0% {
            background-position: -468px 0;
        }
        100% {
            background-position: 468px 0;
        }
    }
</style>
@endsection
