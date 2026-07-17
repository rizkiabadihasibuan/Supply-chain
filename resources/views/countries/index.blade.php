@extends('layouts.app')

@section('title', 'Negara - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Negara</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Negara</h3>
                        <p class="text-secondary small mb-0">Pantau seluruh negara yang menjadi bagian dari rantai pasok global.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary (4 Cards) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Total Negara -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Total Negara</span>
                    <h3 class="fw-bold text-dark mb-1" id="stat-total">12</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem;">Cakupan Global Terpantau</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Negara Risiko Tinggi -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Tinggi</span>
                    <h3 class="fw-bold text-danger mb-1" id="stat-high">3</h3>
                    <span class="text-danger small d-block" style="font-size: 0.75rem;"><i class="bi bi-exclamation-octagon-fill me-1"></i>Butuh Tindakan Segera</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Negara Risiko Sedang -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Sedang</span>
                    <h3 class="fw-bold text-warning mb-1" id="stat-medium">3</h3>
                    <span class="text-warning small d-block" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Dalam Pengawasan Ketat</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-shield-slash fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Negara Risiko Rendah -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Rendah</span>
                    <h3 class="fw-bold text-success mb-1" id="stat-low">6</h3>
                    <span class="text-success small d-block" style="font-size: 0.75rem;"><i class="bi bi-shield-check me-1"></i>Jalur Logistik Stabil</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-shield-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search, Filter & Sort Row -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Bar -->
                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <div class="search-wrapper w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-country-input" placeholder="Cari negara atau ibukota..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyFiltersAndSearch()">
                        </div>
                    </div>

                    <!-- Dropdown Region -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-region-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="all">Semua Wilayah</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="america">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Dropdown Risk -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-risk-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="all">Semua Risiko</option>
                            <option value="high">Risiko Tinggi</option>
                            <option value="medium">Risiko Sedang</option>
                            <option value="low">Risiko Rendah</option>
                        </select>
                    </div>

                    <!-- Dropdown Status -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-status-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="all">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak-aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Dropdown Sorting -->
                    <div class="col-xl-2 col-lg-3 col-md-12 col-6">
                        <select id="sort-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="nama">Urutkan: Nama</option>
                            <option value="risk-desc">Urutkan: Risiko Tertinggi</option>
                            <option value="risk-asc">Urutkan: Risiko Terendah</option>
                            <option value="gdp-desc">Urutkan: GDP Terbesar</option>
                            <option value="pop-desc">Urutkan: Populasi Terbanyak</option>
                        </select>
                    </div>
                </div>

                <!-- Simulation Buttons bar -->
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

    <!-- Skeleton Loading Container (Hidden after simulation loads) -->
    <div id="skeleton-container" class="row g-4 mb-4">
        @for ($i = 0; $i < 8; $i++)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card p-4 border-0 h-100 skeleton-card">
                <div class="skeleton-shimmer" style="width: 50px; height: 35px; border-radius: 6px; mb-3"></div>
                <div class="skeleton-shimmer" style="width: 70%; height: 20px; border-radius: 4px; mb-2"></div>
                <div class="skeleton-shimmer" style="width: 40%; height: 14px; border-radius: 4px; mb-4"></div>
                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="skeleton-shimmer" style="width: 90%; height: 12px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 80%; height: 12px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 70%; height: 12px; border-radius: 4px;"></div>
                </div>
                <div class="skeleton-shimmer mt-auto" style="width: 100%; height: 44px; border-radius: 10px;"></div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Empty State Container (Displayed if no results) -->
    <div id="empty-state-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                <!-- Premium SVG Empty State Illustration -->
                <svg viewBox="0 0 200 120" style="width: 160px; height: 100px;" class="mb-3.5">
                    <rect x="30" y="30" width="140" height="70" rx="12" fill="none" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
                    <circle cx="100" cy="55" r="22" fill="rgba(37, 99, 235, 0.05)" stroke="rgba(37, 99, 235, 0.2)" stroke-width="1.5" />
                    <path d="M92,55 L108,55 M100,47 L100,63" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" />
                    <line x1="45" y1="45" x2="65" y2="45" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" />
                    <line x1="45" y1="55" x2="55" y2="55" stroke="#E2E8F0" stroke-width="2" stroke-linecap="round" />
                    <line x1="135" y1="75" x2="155" y2="75" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" />
                </svg>
                <h5 class="fw-bold text-dark mb-1">Belum ada negara yang tersedia.</h5>
                <p class="text-secondary small mb-3.5" style="max-width: 320px;">Silakan atur kembali kata kunci pencarian atau penyaringan filter Anda.</p>
                <button class="btn btn-primary px-4" style="min-height: 44px;" onclick="resetFiltersAndSearch()">Setel Ulang Filter</button>
            </div>
        </div>
    </div>

    <!-- Countries Grid (Fades in) -->
    <div id="countries-grid" class="row g-4 mb-4" style="display: none;">
        <!-- Card 1: Indonesia -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Indonesia" data-capital="Jakarta" data-region="asia" data-risk-level="low" data-risk-score="1.25" data-pop="275000000" data-gdp="1370000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇮🇩</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Indonesia</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Jakarta</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Asia</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">275 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 1.37 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">28°C / Hujan</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">IDR (Rupiah)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 2: Singapura -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Singapura" data-capital="Singapura" data-region="asia" data-risk-level="low" data-risk-score="0.95" data-pop="5900000" data-gdp="466000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇸🇬</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Singapura</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Singapura</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Asia</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">5.9 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 466 M</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">31°C / Cerah</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">SGD (Dolar SG)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 3: Amerika Serikat -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Amerika Serikat" data-capital="Washington D.C." data-region="america" data-risk-level="medium" data-risk-score="3.48" data-pop="333000000" data-gdp="26850000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇺🇸</span>
                    <span class="badge badge-warning">Risiko Sedang</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Amerika Serikat</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Washington D.C.</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Amerika</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">333 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 26.85 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">19°C / Cerah</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">USD (Dolar AS)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 4: China -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="China" data-capital="Beijing" data-region="asia" data-risk-level="high" data-risk-score="4.92" data-pop="1410000000" data-gdp="17900000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇨🇳</span>
                    <span class="badge badge-danger">Risiko Tinggi</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">China</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Beijing</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Asia</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">1.41 Miliar</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 17.9 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">24°C / Badai</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">CNY (Yuan)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 5: Belanda -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Belanda" data-capital="Amsterdam" data-region="europe" data-risk-level="low" data-risk-score="1.85" data-pop="17800000" data-gdp="1090000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇳🇱</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Belanda</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Amsterdam</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Eropa</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">17.8 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 1.09 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">17°C / Berawan</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">EUR (Euro)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 6: Sudan -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Sudan" data-capital="Khartoum" data-region="africa" data-risk-level="high" data-risk-score="4.80" data-pop="46000000" data-gdp="34000000000" data-status="tidak-aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇸🇩</span>
                    <span class="badge badge-danger">Risiko Tinggi</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Sudan</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Khartoum</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Afrika</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">46 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 34 Miliar</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">38°C / Berdebu</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">SDG (Pound Sudan)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-danger small fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Tidak Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 7: Yaman -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Yaman" data-capital="Sana'a" data-region="asia" data-risk-level="high" data-risk-score="4.50" data-pop="33000000" data-gdp="21000000000" data-status="tidak-aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇾🇪</span>
                    <span class="badge badge-danger">Risiko Tinggi</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Yaman</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Sana'a</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Asia</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">33 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 21 Miliar</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">35°C / Cerah</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">YER (Riyal)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-danger small fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Tidak Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 8: Brasil -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Brasil" data-capital="Brasilia" data-region="america" data-risk-level="low" data-risk-score="2.35" data-pop="215000000" data-gdp="1920000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇧🇷</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Brasil</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Brasilia</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Amerika</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">215 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 1.92 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">26°C / Badai</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">BRL (Real)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 9: Jerman -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Jerman" data-capital="Berlin" data-region="europe" data-risk-level="low" data-risk-score="1.20" data-pop="83000000" data-gdp="4070000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇩🇪</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Jerman</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Berlin</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Eropa</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">83 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 4.07 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">19°C / Mendung</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">EUR (Euro)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 10: Jepang -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Jepang" data-capital="Tokyo" data-region="asia" data-risk-level="low" data-risk-score="1.30" data-pop="125000000" data-gdp="4230000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇯🇵</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Jepang</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Tokyo</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Asia</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">125 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 4.23 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">22°C / Cerah</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">JPY (Yen)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 11: Somalia -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Somalia" data-capital="Mogadishu" data-region="africa" data-risk-level="high" data-risk-score="4.70" data-pop="17000000" data-gdp="8000000000" data-status="tidak-aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇸🇴</span>
                    <span class="badge badge-danger">Risiko Tinggi</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Somalia</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Mogadishu</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Afrika</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">17 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 8 Miliar</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">31°C / Cerah</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">SOS (Shilling)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-danger small fw-semibold"><i class="bi bi-x-circle-fill me-1"></i>Tidak Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>

        <!-- Card 12: Australia -->
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item" data-name="Australia" data-capital="Canberra" data-region="oceania" data-risk-level="low" data-risk-score="1.45" data-pop="26000000" data-gdp="1680000000000" data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="fs-1">🇦🇺</span>
                    <span class="badge badge-success">Risiko Rendah</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">Australia</h5>
                <span class="text-secondary small d-block mb-3.5"><i class="bi bi-geo-alt me-1"></i>Ibukota: Canberra</span>
                
                <div class="d-flex flex-column gap-2 mb-4" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">Oceania</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">26 Juta</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">GDP Est:</span><span class="text-dark fw-medium">USD 1.68 T</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Cuaca Port:</span><span class="text-dark fw-medium">14°C / Cerah</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kurs Utama:</span><span class="text-dark fw-medium">AUD (Dolar Aus)</span></div>
                </div>

                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                    <span class="text-success small fw-semibold"><span class="pulse-indicator"></span>Aktif</span>
                    <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-3">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="row g-4" style="display: none;">
        <div class="col-12">
            <div class="card p-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <span class="text-secondary small" id="pagination-info">Menampilkan 1-12 dari 12 data Negara</span>
                
                <nav aria-label="Navigasi Halaman">
                    <ul class="pagination">
                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial simulated loading
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('countries-grid').style.display = 'flex';
            document.getElementById('pagination-container').style.display = 'block';
            updateStatistics();
        }, 800);
    });

    // Skeleton loading simulator trigger
    function simulateSkeletonLoading() {
        document.getElementById('countries-grid').style.display = 'none';
        document.getElementById('pagination-container').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'flex';
        
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            applyFiltersAndSearch();
        }, 800);
    }

    // Empty state simulator trigger
    function simulateEmptyState() {
        document.getElementById('search-country-input').value = 'NegaraXyzYangTidakAda';
        applyFiltersAndSearch();
    }

    // Reset filters
    function resetFiltersAndSearch() {
        document.getElementById('search-country-input').value = '';
        document.getElementById('filter-region-select').value = 'all';
        document.getElementById('filter-risk-select').value = 'all';
        document.getElementById('filter-status-select').value = 'all';
        document.getElementById('sort-select').value = 'nama';
        applyFiltersAndSearch();
    }

    // Dynamic Filter, Search and Sort Logic (client-side ES6)
    function applyFiltersAndSearch() {
        const query = document.getElementById('search-country-input').value.toLowerCase();
        const region = document.getElementById('filter-region-select').value;
        const risk = document.getElementById('filter-risk-select').value;
        const status = document.getElementById('filter-status-select').value;
        const sortVal = document.getElementById('sort-select').value;

        const grid = document.getElementById('countries-grid');
        const cards = Array.from(document.querySelectorAll('.country-card-item'));
        
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const capital = card.getAttribute('data-capital').toLowerCase();
            const cardRegion = card.getAttribute('data-region');
            const cardRisk = card.getAttribute('data-risk-level');
            const cardStatus = card.getAttribute('data-status');

            const matchesSearch = name.includes(query) || capital.includes(query);
            const matchesRegion = (region === 'all' || cardRegion === region);
            const matchesRisk = (risk === 'all' || cardRisk === risk);
            const matchesStatus = (status === 'all' || cardStatus === status);

            if (matchesSearch && matchesRegion && matchesRisk && matchesStatus) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sorting implementation
        if (visibleCount > 0) {
            cards.sort((a, b) => {
                if (sortVal === 'nama') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortVal === 'risk-desc') {
                    return parseFloat(b.getAttribute('data-risk-score')) - parseFloat(a.getAttribute('data-risk-score'));
                } else if (sortVal === 'risk-asc') {
                    return parseFloat(a.getAttribute('data-risk-score')) - parseFloat(b.getAttribute('data-risk-score'));
                } else if (sortVal === 'gdp-desc') {
                    return parseFloat(b.getAttribute('data-gdp')) - parseFloat(a.getAttribute('data-gdp'));
                } else if (sortVal === 'pop-desc') {
                    return parseFloat(b.getAttribute('data-pop')) - parseFloat(a.getAttribute('data-pop'));
                }
                return 0;
            });

            // Append sorted cards back to grid
            cards.forEach(card => grid.appendChild(card));
        }

        // Toggle Grid or Empty State views
        const emptyState = document.getElementById('empty-state-container');
        const pagination = document.getElementById('pagination-container');

        if (visibleCount === 0) {
            grid.style.display = 'none';
            pagination.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            pagination.style.display = 'block';
            emptyState.style.display = 'none';
            document.getElementById('pagination-info').textContent = `Menampilkan 1-${visibleCount} dari ${visibleCount} data Negara`;
        }
    }

    // Dynamic Summary Stats Update based on current listings
    function updateStatistics() {
        const cards = Array.from(document.querySelectorAll('.country-card-item'));
        let total = cards.length;
        let high = 0;
        let medium = 0;
        let low = 0;

        cards.forEach(card => {
            const risk = card.getAttribute('data-risk-level');
            if (risk === 'high') high++;
            else if (risk === 'medium') medium++;
            else if (risk === 'low') low++;
        });

        document.getElementById('stat-total').textContent = total;
        document.getElementById('stat-high').textContent = high;
        document.getElementById('stat-medium').textContent = medium;
        document.getElementById('stat-low').textContent = low;
    }
</script>

<style>
    /* Skeleton Loading placeholder style */
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

    /* Transition for items */
    .country-card-item {
        transition: transform 0.25s ease-in-out, opacity 0.25s ease-in-out;
    }

    .country-card-item:hover {
        transform: scale(1.02) translateY(-2px);
    }
    
    .country-card-item:hover .card {
        box-shadow: 0 10px 22px rgba(18, 52, 88, 0.08) !important;
        border-color: rgba(37, 99, 235, 0.2) !important;
    }
</style>
@endsection
