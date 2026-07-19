@extends('layouts.user.app')

@section('title', 'Global Exchange Rate Center - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Breadcrumb & Header Action -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Global Exchange Rate Center</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Global Exchange Rate Center</h3>
                        <p class="text-secondary small mb-0">Pantau pergerakan nilai tukar mata uang dunia secara real-time untuk mendukung analisis rantai pasok global.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" id="btn-refresh-rates" onclick="triggerRefreshRates()" style="min-height: 44px;">
                            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Kurs
                        </button>
                        <button class="btn btn-light" disabled style="min-height: 44px; opacity: 0.65;" title="Ekspor data keuangan siap digunakan pada milestone berikutnya">
                            <i class="bi bi-download me-2"></i>Unduh Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 Summary KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- 1. Total Mata Uang Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Total Mata Uang Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-total">10</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;">Pemantauan Valuta Global</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-cash-coin fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 2. Mata Uang Menguat -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Mata Uang Menguat</span>
                    <h3 class="fw-bold text-success mb-1" id="kpi-gainers">6</h3>
                    <span class="text-success small d-block" style="font-size: 0.725rem;"><i class="bi bi-graph-up me-1"></i>Tren Harian Positif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-caret-up-square-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 3. Mata Uang Melemah -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Mata Uang Melemah</span>
                    <h3 class="fw-bold text-danger mb-1" id="kpi-losers">4</h3>
                    <span class="text-danger small d-block" style="font-size: 0.725rem;"><i class="bi bi-graph-down me-1"></i>Tren Harian Negatif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-caret-down-square-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 4. Update Terakhir -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Update Terakhir</span>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Hari Ini (WIB)</h4>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;"><i class="bi bi-cpu me-1"></i>Sinkronisasi Otomatis</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(6, 182, 212, 0.08); color: var(--info);">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Toolbar / Search & Filters -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Bar -->
                    <div class="col-xl-4 col-lg-3 col-md-12 col-12">
                        <div class="search-wrapper w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-currency-input" placeholder="Cari kode atau nama mata uang..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyCurrencyFilters()">
                        </div>
                    </div>

                    <!-- Filter Region -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-currency-region" class="form-select" style="min-height: 44px;" onchange="applyCurrencyFilters()">
                            <option value="all">Semua Benua</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="america">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Filter Base Currency -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-base-currency" class="form-select" style="min-height: 44px;" onchange="changeBaseCurrency()">
                            <option value="USD">Mata Uang Dasar: USD ($)</option>
                            <option value="EUR">Mata Uang Dasar: EUR (€)</option>
                            <option value="JPY">Mata Uang Dasar: JPY (¥)</option>
                            <option value="IDR">Mata Uang Dasar: IDR (Rp)</option>
                            <option value="GBP">Mata Uang Dasar: GBP (£)</option>
                        </select>
                    </div>

                    <!-- Sort Selection -->
                    <div class="col-xl-2 col-lg-3 col-md-4 col-12">
                        <select id="sort-currency-select" class="form-select" style="min-height: 44px;" onchange="applyCurrencyFilters()">
                            <option value="code">Kode Mata Uang</option>
                            <option value="rate-desc">Kurs Tertinggi</option>
                            <option value="rate-asc">Kurs Terendah</option>
                            <option value="change-desc">Kenaikan Harian</option>
                        </select>
                    </div>
                </div>

                <!-- Simulation Tools bar -->
                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                    <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateSkeletonLoading()">
                        <i class="bi bi-hourglass-split me-2"></i>Simulasikan Loading
                    </button>
                    <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateEmptyState()">
                        <i class="bi bi-x-circle me-2"></i>Simulasikan Data Kosong
                    </button>
                    <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateErrorState()">
                        <i class="bi bi-exclamation-octagon me-2"></i>Simulasikan Error
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Skeleton Loading -->
    <div id="skeleton-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-lg-8">
            <div class="card p-4 border-0 skeleton-card" style="height: 420px; mb-4">
                <div class="skeleton-shimmer mb-3" style="width: 30%; height: 24px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer mb-2" style="width: 100%; height: 20px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer mb-2" style="width: 100%; height: 20px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer mb-2" style="width: 100%; height: 20px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer" style="width: 100%; height: 20px; border-radius: 4px;"></div>
            </div>
            <div class="card p-4 border-0 skeleton-card" style="height: 250px;">
                <div class="skeleton-shimmer" style="width: 100%; height: 100%; border-radius: 8px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 border-0 skeleton-card" style="height: 580px;">
                <div class="skeleton-shimmer mb-4" style="width: 50%; height: 24px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer mb-3" style="width: 100%; height: 80px; border-radius: 12px;"></div>
                <div class="skeleton-shimmer mb-3" style="width: 100%; height: 120px; border-radius: 12px;"></div>
                <div class="skeleton-shimmer" style="width: 100%; height: 160px; border-radius: 12px;"></div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                <svg viewBox="0 0 200 120" style="width: 160px; height: 100px;" class="mb-3.5">
                    <rect x="30" y="30" width="140" height="70" rx="12" fill="none" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
                    <path d="M100,45 L100,75 M85,60 L115,60" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" />
                </svg>
                <h5 class="fw-bold text-dark mb-1">Belum ada data nilai tukar.</h5>
                <p class="text-secondary small mb-3.5">Silakan reset filter atau ketik pencarian valuta lainnya.</p>
                <button class="btn btn-primary px-4" style="min-height: 44px;" onclick="resetFilters()">Reset Filter</button>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-3">
                    <i class="bi bi-cloud-slash fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1">Stasiun Finansial Tidak Terhubung.</h5>
                <p class="text-secondary small mb-3.5" style="max-width: 320px;">Gagal memuat kurs mata uang asing. Pastikan koneksi server atau stasiun data logistik aktif.</p>
                <button class="btn btn-danger px-4" style="min-height: 44px;" onclick="retryLoadingFromError()">Coba Lagi</button>
            </div>
        </div>
    </div>

    <!-- Main Content Layout Grid -->
    <div id="main-content-grid" class="row g-4">
        <!-- Kolom Kiri (Tabel, Trend Chart, Top Movers) -->
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                
                <!-- SECTION 1: Exchange Rate Table -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-table text-primary me-2"></i>Daftar Pengawasan Kurs Keuangan</h5>
                    
                    <div class="table-responsive-card">
                        <table class="table table-hover align-middle mb-0" id="currency-table">
                            <thead>
                                <tr>
                                    <th>Flag</th>
                                    <th>Kode</th>
                                    <th>Nama Mata Uang</th>
                                    <th>Nilai Tukar</th>
                                    <th>Perubahan Harian</th>
                                    <th>Perubahan Mingguan</th>
                                    <th>Tren (Sparkline)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="currency-table-body">
                                <!-- IDR -->
                                <tr class="currency-row-item" data-code="IDR" data-name="Rupiah Indonesia" data-region="asia" data-rate-usd="16245.00" data-rate-eur="17650.00" data-rate-jpy="102.80" data-rate-idr="1.00" data-rate-gbp="20960.00" data-change="0.15" data-weekly="-0.42" data-status="stabil">
                                    <td data-label="Flag"><span class="fs-4">🇮🇩</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">IDR</td>
                                    <td data-label="Nama Mata Uang">Rupiah Indonesia</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">16,245.00</td>
                                    <td data-label="Perubahan Harian" class="text-success small fw-semibold">+0.15%</td>
                                    <td data-label="Perubahan Mingguan" class="text-danger small fw-semibold">-0.42%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,15 L10,13 L20,17 L30,12 L40,16 L50,8 L60,11" fill="none" stroke="var(--success)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-success">Stabil</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('IDR', 'Rupiah Indonesia', 'Rp')">Pilih</button></td>
                                </tr>

                                <!-- EUR -->
                                <tr class="currency-row-item" data-code="EUR" data-name="Euro" data-region="europe" data-rate-usd="0.92" data-rate-eur="1.00" data-rate-jpy="0.0058" data-rate-idr="0.000057" data-rate-gbp="1.18" data-change="-0.08" data-weekly="0.22" data-status="melemah">
                                    <td data-label="Flag"><span class="fs-4">🇪🇺</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">EUR</td>
                                    <td data-label="Nama Mata Uang">Euro</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">0.92</td>
                                    <td data-label="Perubahan Harian" class="text-danger small fw-semibold">-0.08%</td>
                                    <td data-label="Perubahan Mingguan" class="text-success small fw-semibold">+0.22%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,8 L10,12 L20,9 L30,14 L40,11 L50,15 L60,16" fill="none" stroke="var(--danger)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-danger">Melemah</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('EUR', 'Euro', '€')">Pilih</button></td>
                                </tr>

                                <!-- JPY -->
                                <tr class="currency-row-item" data-code="JPY" data-name="Yen Jepang" data-region="asia" data-rate-usd="158.05" data-rate-eur="171.80" data-rate-jpy="1.00" data-rate-idr="0.0097" data-rate-gbp="203.90" data-change="-0.35" data-weekly="-1.20" data-status="melemah">
                                    <td data-label="Flag"><span class="fs-4">🇯🇵</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">JPY</td>
                                    <td data-label="Nama Mata Uang">Yen Jepang</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">158.05</td>
                                    <td data-label="Perubahan Harian" class="text-danger small fw-semibold">-0.35%</td>
                                    <td data-label="Perubahan Mingguan" class="text-danger small fw-semibold">-1.20%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,5 L10,9 L20,12 L30,10 L40,15 L50,18 L60,19" fill="none" stroke="var(--danger)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-danger">Melemah</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('JPY', 'Yen Jepang', '¥')">Pilih</button></td>
                                </tr>

                                <!-- SGD -->
                                <tr class="currency-row-item" data-code="SGD" data-name="Dolar Singapura" data-region="asia" data-rate-usd="1.34" data-rate-eur="1.46" data-rate-jpy="0.0085" data-rate-idr="0.000083" data-rate-gbp="1.73" data-change="0.22" data-weekly="0.85" data-status="menguat">
                                    <td data-label="Flag"><span class="fs-4">🇸🇬</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">SGD</td>
                                    <td data-label="Nama Mata Uang">Dolar Singapura</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">1.34</td>
                                    <td data-label="Perubahan Harian" class="text-success small fw-semibold">+0.22%</td>
                                    <td data-label="Perubahan Mingguan" class="text-success small fw-semibold">+0.85%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,17 L10,15 L20,11 L30,14 L40,9 L50,8 L60,5" fill="none" stroke="var(--success)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-success">Menguat</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('SGD', 'Dolar Singapura', 'S$')">Pilih</button></td>
                                </tr>

                                <!-- GBP -->
                                <tr class="currency-row-item" data-code="GBP" data-name="Poundsterling Inggris" data-region="europe" data-rate-usd="0.77" data-rate-eur="0.84" data-rate-jpy="0.0049" data-rate-idr="0.000048" data-rate-gbp="1.00" data-change="0.05" data-weekly="0.10" data-status="stabil">
                                    <td data-label="Flag"><span class="fs-4">🇬🇧</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">GBP</td>
                                    <td data-label="Nama Mata Uang">Poundsterling Inggris</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">0.77</td>
                                    <td data-label="Perubahan Harian" class="text-success small fw-semibold">+0.05%</td>
                                    <td data-label="Perubahan Mingguan" class="text-success small fw-semibold">+0.10%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,13 L10,12 L20,15 L30,13 L40,11 L50,11 L60,10" fill="none" stroke="var(--success)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-success">Stabil</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('GBP', 'Poundsterling Inggris', '£')">Pilih</button></td>
                                </tr>

                                <!-- AUD -->
                                <tr class="currency-row-item" data-code="AUD" data-name="Dolar Australia" data-region="oceania" data-rate-usd="1.49" data-rate-eur="1.62" data-rate-jpy="0.0094" data-rate-idr="0.000092" data-rate-gbp="1.92" data-change="-0.12" data-weekly="0.05" data-status="stabil">
                                    <td data-label="Flag"><span class="fs-4">🇦🇺</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">AUD</td>
                                    <td data-label="Nama Mata Uang">Dolar Australia</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">1.49</td>
                                    <td data-label="Perubahan Harian" class="text-danger small fw-semibold">-0.12%</td>
                                    <td data-label="Perubahan Mingguan" class="text-success small fw-semibold">+0.05%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,10 L10,12 L20,8 L30,11 L40,13 L50,11 L60,12" fill="none" stroke="var(--warning)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-warning">Stabil</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('AUD', 'Dolar Australia', 'A$')">Pilih</button></td>
                                </tr>

                                <!-- CNY -->
                                <tr class="currency-row-item" data-code="CNY" data-name="Yuan China" data-region="asia" data-rate-usd="7.26" data-rate-eur="7.90" data-rate-jpy="0.046" data-rate-idr="0.00045" data-rate-gbp="9.36" data-change="0.08" data-weekly="0.35" data-status="menguat">
                                    <td data-label="Flag"><span class="fs-4">🇨🇳</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">CNY</td>
                                    <td data-label="Nama Mata Uang">Yuan China</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">7.26</td>
                                    <td data-label="Perubahan Harian" class="text-success small fw-semibold">+0.08%</td>
                                    <td data-label="Perubahan Mingguan" class="text-success small fw-semibold">+0.35%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,15 L10,13 L20,14 L30,10 L40,11 L50,6 L60,8" fill="none" stroke="var(--success)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-success">Menguat</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('CNY', 'Yuan China', '¥')">Pilih</button></td>
                                </tr>

                                <!-- BRL -->
                                <tr class="currency-row-item" data-code="BRL" data-name="Real Brasil" data-region="america" data-rate-usd="5.42" data-rate-eur="5.90" data-rate-jpy="0.034" data-rate-idr="0.00033" data-rate-gbp="6.99" data-change="0.45" data-weekly="1.15" data-status="menguat">
                                    <td data-label="Flag"><span class="fs-4">🇧🇷</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">BRL</td>
                                    <td data-label="Nama Mata Uang">Real Brasil</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">5.42</td>
                                    <td data-label="Perubahan Harian" class="text-success small fw-semibold">+0.45%</td>
                                    <td data-label="Perubahan Mingguan" class="text-success small fw-semibold">+1.15%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,18 L10,16 L20,13 L30,15 L40,10 L50,6 L60,3" fill="none" stroke="var(--success)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-success">Menguat</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('BRL', 'Real Brasil', 'R$')">Pilih</button></td>
                                </tr>

                                <!-- ZAR -->
                                <tr class="currency-row-item" data-code="ZAR" data-name="Rand Afrika Selatan" data-region="africa" data-rate-usd="18.15" data-rate-eur="19.72" data-rate-jpy="0.11" data-rate-idr="0.0011" data-rate-gbp="23.40" data-change="-0.52" data-weekly="-1.85" data-status="melemah">
                                    <td data-label="Flag"><span class="fs-4">🇿🇦</span></td>
                                    <td data-label="Kode" class="fw-bold text-dark">ZAR</td>
                                    <td data-label="Nama Mata Uang">Rand Afrika Selatan</td>
                                    <td data-label="Nilai Tukar" class="fw-bold text-dark currency-val">18.15</td>
                                    <td data-label="Perubahan Harian" class="text-danger small fw-semibold">-0.52%</td>
                                    <td data-label="Perubahan Mingguan" class="text-danger small fw-semibold">-1.85%</td>
                                    <td data-label="Tren (Sparkline)">
                                        <svg width="60" height="20" class="sparkline">
                                            <path d="M0,3 L10,7 L20,12 L30,9 L40,15 L50,18 L60,20" fill="none" stroke="var(--danger)" stroke-width="1.5"></path>
                                        </svg>
                                    </td>
                                    <td data-label="Status"><span class="badge badge-danger">Melemah</span></td>
                                    <td data-label="Aksi"><button class="btn btn-light btn-sm border px-2.5" style="min-height: 38px;" onclick="selectCurrencyForWidget('ZAR', 'Rand Afrika Selatan', 'R')">Pilih</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 2: Exchange Rate Trend Chart -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-1"><i class="bi bi-graph-up text-primary me-2"></i>Tren Indeks Volatilitas Valuta</h5>
                    <p class="text-secondary small mb-4">Grafik fluktuasi rata-rata kurs mata uang terhadap base currency.</p>
                    
                    <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 220px; background-color: #FAFCFF !important;">
                        <svg viewBox="0 0 600 200" class="w-100 h-100 p-2">
                            <defs>
                                <linearGradient id="currencyChartGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="var(--primary)" stop-opacity="0.2"></stop>
                                    <stop offset="100%" stop-color="var(--primary)" stop-opacity="0.0"></stop>
                                </linearGradient>
                            </defs>
                            <!-- Grid lines -->
                            <line x1="40" y1="40" x2="560" y2="40" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            <line x1="40" y1="90" x2="560" y2="90" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            <line x1="40" y1="140" x2="560" y2="140" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            
                            <!-- Curve path -->
                            <path d="M40,110 L90,130 Q140,150 190,115 T290,95 T390,120 T490,75 L560,95 L560,180 L40,180 Z" fill="url(#currencyChartGrad)"></path>
                            <path d="M40,110 L90,130 Q140,150 190,115 T290,95 T390,120 T490,75 L560,95" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round"></path>
                            
                            <circle cx="490" cy="75" r="4.5" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                            <circle cx="560" cy="95" r="4.5" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>

                            <!-- Axis labels -->
                            <text x="40" y="195" fill="#94A3B8" font-size="9" text-anchor="middle">Senin</text>
                            <text x="190" y="195" fill="#94A3B8" font-size="9" text-anchor="middle">Rabu</text>
                            <text x="390" y="195" fill="#94A3B8" font-size="9" text-anchor="middle">Jumat</text>
                            <text x="560" y="195" fill="#94A3B8" font-size="9" text-anchor="middle">Hari Ini</text>
                        </svg>
                    </div>
                </div>

                <!-- SECTION 3: Top Movers -->
                <div class="row g-4">
                    <!-- Top Gainers -->
                    <div class="col-md-6">
                        <div class="card p-4 border-0 h-100">
                            <h6 class="fw-bold text-success mb-3"><i class="bi bi-caret-up-fill me-2"></i>Top Gainers (Menguat)</h6>
                            <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-1.5">
                                    <span>🇧🇷 BRL (Real Brasil)</span><span class="text-success fw-bold">+0.45%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-1.5">
                                    <span>🇸🇬 SGD (Dolar Singapura)</span><span class="text-success fw-bold">+0.22%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>🇮🇩 IDR (Rupiah Indonesia)</span><span class="text-success fw-bold">+0.15%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Losers -->
                    <div class="col-md-6">
                        <div class="card p-4 border-0 h-100">
                            <h6 class="fw-bold text-danger mb-3"><i class="bi bi-caret-down-fill me-2"></i>Top Losers (Melemah)</h6>
                            <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-1.5">
                                    <span>🇿🇦 ZAR (Rand Afrika Selatan)</span><span class="text-danger fw-bold">-0.52%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-1.5">
                                    <span>🇯🇵 JPY (Yen Jepang)</span><span class="text-danger fw-bold">-0.35%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>🇺🇸 USD (Dolar AS)</span><span class="text-danger fw-bold">-0.12%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Kolom Kanan (Widgets & Insight) -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- WIDGET 1: Base Currency Info Panel -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bookmark-fill text-primary me-2"></i>Mata Uang Dasar</h5>
                    
                    <div class="p-3 rounded-4 border text-center" style="background-color: #F8FAFC !important;">
                        <span class="text-secondary small d-block">Base Currency Pembanding</span>
                        <h3 class="fw-bold text-dark mb-1" id="cur-base-title">USD</h3>
                        <span class="badge badge-primary px-3 py-1.5" id="cur-base-subtitle">Dolar Amerika Serikat ($)</span>
                    </div>
                </div>

                <!-- WIDGET 2: Quick Currency Converter -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calculator text-primary me-2"></i>Kalkulator Konversi Instan</h5>
                    
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <label class="form-label text-secondary small">Dari (From)</label>
                            <select id="conv-from" class="form-select" style="min-height: 44px;" onchange="calculateConversion()">
                                <option value="USD">USD - Dolar AS</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="JPY">JPY - Yen Jepang</option>
                                <option value="IDR" selected>IDR - Rupiah Indonesia</option>
                                <option value="GBP">GBP - Pound Inggris</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-secondary small">Ke (To)</label>
                            <select id="conv-to" class="form-select" style="min-height: 44px;" onchange="calculateConversion()">
                                <option value="USD" selected>USD - Dolar AS</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="JPY">JPY - Yen Jepang</option>
                                <option value="IDR">IDR - Rupiah Indonesia</option>
                                <option value="GBP">GBP - Pound Inggris</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-secondary small">Jumlah (Amount)</label>
                            <input type="number" id="conv-amount" class="form-control" value="16245" style="min-height: 44px;" oninput="calculateConversion()">
                        </div>
                        <div class="p-3 bg-light rounded-4 border text-center" style="background-color: #FAFCFF !important;">
                            <span class="text-secondary small d-block">Hasil Konversi</span>
                            <h4 class="fw-bold text-primary mb-0" id="conv-result">1.00 USD</h4>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 3: Risk Impact Indicator -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-exclamation text-primary me-2"></i>Dampak Risiko Finansial</h5>
                    
                    <div class="p-3 border rounded-4" style="background-color: rgba(245, 158, 11, 0.06); border-color: rgba(245, 158, 11, 0.15) !important;">
                        <span class="badge bg-warning text-dark mb-2">Dampak Sedang</span>
                        <h6 class="fw-bold text-dark mb-1">Volatilitas JPY & ZAR Mengancam Margin</h6>
                        <p class="text-secondary small mb-0">Depresiasi nilai tukar Yen Jepang menyebabkan kenaikan biaya komponen manufaktur asal importir Asia Timur.</p>
                    </div>
                </div>

                <!-- WIDGET 4: Recent Updates Timeline -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas Finansial</h5>
                    
                    <div style="position: relative; padding-left: 20px;">
                        <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
                        
                        <div class="position-relative mb-3.5">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--success); border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Sinkronisasi Berhasil</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Data kurs ditarik dari stasiun finansial utama.</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>15 menit yang lalu</span>
                            </div>
                        </div>

                        <div class="position-relative">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--warning); border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Depresiasi JPY Kritis</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Nilai JPY menyentuh batas kritis deviasi impor.</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>2 jam yang lalu</span>
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
        // Run simulated loader
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
            calculateConversion();
        }, 800);
    });

    // Mock exchange rates table
    const currencyRates = {
        USD: { IDR: 16245.00, EUR: 0.92, JPY: 158.05, SGD: 1.34, GBP: 0.77, AUD: 1.49, CNY: 7.26, BRL: 5.42, ZAR: 18.15, USD: 1.00 },
        EUR: { IDR: 17650.00, EUR: 1.00, JPY: 171.80, SGD: 1.46, GBP: 0.84, AUD: 1.62, CNY: 7.90, BRL: 5.90, ZAR: 19.72, USD: 1.08 },
        JPY: { IDR: 102.80, EUR: 0.0058, JPY: 1.00, SGD: 0.0085, GBP: 0.0049, AUD: 0.0094, CNY: 0.046, BRL: 0.034, ZAR: 0.11, USD: 0.0063 },
        IDR: { IDR: 1.00, EUR: 0.000057, JPY: 0.0097, SGD: 0.000083, GBP: 0.000048, AUD: 0.000092, CNY: 0.00045, BRL: 0.00033, ZAR: 0.0011, USD: 0.000062 },
        GBP: { IDR: 20960.00, EUR: 1.18, JPY: 203.90, SGD: 1.73, GBP: 1.00, AUD: 1.92, CNY: 9.36, BRL: 6.99, ZAR: 23.40, USD: 1.30 }
    };

    // Calculate Conversion instan
    function calculateConversion() {
        const from = document.getElementById('conv-from').value;
        const to = document.getElementById('conv-to').value;
        const amount = parseFloat(document.getElementById('conv-amount').value) || 0;

        // Calculate using base conversion via USD mock
        let valInUSD = amount;
        if (from !== 'USD') {
            // value in usd = amount / rate_from_against_usd
            // Since our rates are USD-based: e.g. 1 USD = 16245 IDR
            // So 16245 IDR = 16245 / 16245 = 1 USD
            valInUSD = amount / (currencyRates.USD[from] || 1);
        }

        const finalResult = valInUSD * (currencyRates.USD[to] || 1);
        document.getElementById('conv-result').textContent = `${finalResult.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${to}`;
    }

    // Select currency for input/quick action
    function selectCurrencyForWidget(code, name, symbol) {
        document.getElementById('conv-from').value = code;
        calculateConversion();
        alert(`Mata uang ${name} (${code}) dipilih sebagai sumber kalkulator konversi.`);
    }

    // Change Base Currency
    function changeBaseCurrency() {
        const base = document.getElementById('filter-base-currency').value;
        
        // Update Side Widget
        document.getElementById('cur-base-title').textContent = base;
        
        let label = 'Dolar Amerika Serikat ($)';
        if (base === 'EUR') label = 'Euro (€)';
        if (base === 'JPY') label = 'Yen Jepang (¥)';
        if (base === 'IDR') label = 'Rupiah Indonesia (Rp)';
        if (base === 'GBP') label = 'Poundsterling Inggris (£)';
        document.getElementById('cur-base-subtitle').textContent = label;

        // Update Rates in table
        const rows = document.querySelectorAll('.currency-row-item');
        rows.forEach(row => {
            const code = row.getAttribute('data-code');
            
            // Look up rate of `code` against `base`
            // If base is USD, rate is rate-usd.
            // If base is EUR, rate is rate-eur, etc.
            const rateValAttr = 'data-rate-' + base.toLowerCase();
            const rateVal = parseFloat(row.getAttribute(rateValAttr)) || 0;

            row.querySelector('.currency-val').textContent = rateVal.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
        });

        applyCurrencyFilters();
    }

    // Apply filters and sorting
    function applyCurrencyFilters() {
        const query = document.getElementById('search-currency-input').value.toLowerCase();
        const region = document.getElementById('filter-currency-region').value;
        const sortVal = document.getElementById('sort-currency-select').value;
        const base = document.getElementById('filter-base-currency').value;

        const tableBody = document.getElementById('currency-table-body');
        const rows = Array.from(document.querySelectorAll('.currency-row-item'));
        
        let visibleCount = 0;

        rows.forEach(row => {
            const code = row.getAttribute('data-code').toLowerCase();
            const name = row.getAttribute('data-name').toLowerCase();
            const rowRegion = row.getAttribute('data-region');

            const matchesSearch = code.includes(query) || name.includes(query);
            const matchesRegion = (region === 'all' || rowRegion === region);

            if (matchesSearch && matchesRegion) {
                row.style.display = 'table-row';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        // Sorting
        if (visibleCount > 0) {
            rows.sort((a, b) => {
                if (sortVal === 'code') {
                    return a.getAttribute('data-code').localeCompare(b.getAttribute('data-code'));
                } else if (sortVal === 'rate-desc') {
                    const rateA = parseFloat(a.getAttribute('data-rate-' + base.toLowerCase()));
                    const rateB = parseFloat(b.getAttribute('data-rate-' + base.toLowerCase()));
                    return rateB - rateA;
                } else if (sortVal === 'rate-asc') {
                    const rateA = parseFloat(a.getAttribute('data-rate-' + base.toLowerCase()));
                    const rateB = parseFloat(b.getAttribute('data-rate-' + base.toLowerCase()));
                    return rateA - rateB;
                } else if (sortVal === 'change-desc') {
                    const chA = parseFloat(a.getAttribute('data-change'));
                    const chB = parseFloat(b.getAttribute('data-change'));
                    return chB - chA;
                }
                return 0;
            });

            rows.forEach(row => tableBody.appendChild(row));
        }

        // Toggle Views
        const grid = document.getElementById('main-content-grid');
        const emptyState = document.getElementById('empty-state-container');
        const errorState = document.getElementById('error-state-container');

        errorState.style.display = 'none';

        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyState.style.display = 'none';
        }
    }

    // Refresh animation trigger
    function triggerRefreshRates() {
        const btn = document.getElementById('btn-refresh-rates');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Kurs';
            btn.disabled = false;
            alert('Pergerakan kurs mata uang berhasil disinkronkan ke API Finansial!');
        }, 1200);
    }

    // Skeleton loader simulation
    function simulateSkeletonLoading() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'flex';

        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
            applyCurrencyFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-currency-input').value = 'MataUangXyz';
        applyCurrencyFilters();
    }

    // Error state simulation
    function simulateErrorState() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'flex';
    }

    function retryLoadingFromError() {
        simulateSkeletonLoading();
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('search-currency-input').value = '';
        document.getElementById('filter-currency-region').value = 'all';
        document.getElementById('filter-base-currency').value = 'USD';
        document.getElementById('sort-currency-select').value = 'code';
        changeBaseCurrency();
    }
</script>

<style>
    /* Sparklines path design */
    .sparkline {
        display: inline-block;
        vertical-align: middle;
    }

    /* Skeleton style */
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
