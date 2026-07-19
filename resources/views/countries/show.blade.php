@extends('layouts.user.app')

@section('title', 'Detail Negara - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Breadcrumbs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-3 border-0">
                <nav aria-label="breadcrumb" class="mb-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('countries') }}">Negara</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Negara</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Country Detail Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center">
                        <span class="fs-1 me-3">🇮🇩</span>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold text-dark mb-0">Indonesia</h3>
                                <span class="badge bg-light text-secondary border">ID / IDN</span>
                                <span class="badge badge-success">Aktif</span>
                                <span class="badge badge-success">Risiko Rendah</span>
                            </div>
                            <p class="text-secondary small mb-0">Wilayah: Asia Tenggara | Koordinat Hub: 6.1320° S, 106.8778° E</p>
                        </div>
                    </div>
                    <!-- Action buttons -->
                    <div class="d-flex gap-2.5">
                        <button class="btn btn-primary" id="btn-watchlist" onclick="toggleWatchlist()" style="min-height: 44px;">
                            <i class="bi bi-bookmark-star me-2"></i>Tambah ke Watchlist
                        </button>
                        <button class="btn btn-light" disabled style="min-height: 44px; opacity: 0.65;" title="Ekspor PDF siap digunakan pada milestone berikutnya">
                            <i class="bi bi-file-earmark-pdf me-2"></i>Ekspor PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6 Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- 1. Risk Score -->
        <div class="col-6 col-lg-2">
            <div class="card p-3 h-100 border-0 text-center">
                <div class="p-2.5 rounded-circle d-inline-block border mx-auto mb-2" style="background-color: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.15) !important;">
                    <i class="bi bi-shield-check text-success fs-4"></i>
                </div>
                <span class="text-secondary small d-block mb-1">Risk Score</span>
                <h4 class="fw-bold text-dark mb-0">1.25</h4>
                <span class="text-success small" style="font-size: 0.65rem;">Stabil (Rendah)</span>
            </div>
        </div>

        <!-- 2. GDP -->
        <div class="col-6 col-lg-2">
            <div class="card p-3 h-100 border-0 text-center">
                <div class="p-2.5 rounded-circle d-inline-block border mx-auto mb-2" style="background-color: rgba(37, 99, 235, 0.08); border-color: rgba(37, 99, 235, 0.15) !important;">
                    <i class="bi bi-currency-dollar text-primary fs-4"></i>
                </div>
                <span class="text-secondary small d-block mb-1">GDP Est</span>
                <h4 class="fw-bold text-dark mb-0">USD 1.37 T</h4>
                <span class="text-success small" style="font-size: 0.65rem;">+5.05% Pertumbuhan</span>
            </div>
        </div>

        <!-- 3. Populasi -->
        <div class="col-6 col-lg-2">
            <div class="card p-3 h-100 border-0 text-center">
                <div class="p-2.5 rounded-circle d-inline-block border mx-auto mb-2" style="background-color: rgba(6, 182, 212, 0.08); border-color: rgba(6, 182, 212, 0.15) !important;">
                    <i class="bi bi-people text-info fs-4"></i>
                </div>
                <span class="text-secondary small d-block mb-1">Populasi</span>
                <h4 class="fw-bold text-dark mb-0">275 Juta</h4>
                <span class="text-secondary small" style="font-size: 0.65rem;">Sensus Penduduk</span>
            </div>
        </div>

        <!-- 4. Mata Uang -->
        <div class="col-6 col-lg-2">
            <div class="card p-3 h-100 border-0 text-center">
                <div class="p-2.5 rounded-circle d-inline-block border mx-auto mb-2" style="background-color: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.15) !important;">
                    <i class="bi bi-cash-coin text-success fs-4"></i>
                </div>
                <span class="text-secondary small d-block mb-1">Mata Uang</span>
                <h4 class="fw-bold text-dark mb-0">IDR</h4>
                <span class="text-secondary small" style="font-size: 0.65rem;">Rupiah (Rp)</span>
            </div>
        </div>

        <!-- 5. Pelabuhan Utama -->
        <div class="col-6 col-lg-2">
            <div class="card p-3 h-100 border-0 text-center">
                <div class="p-2.5 rounded-circle d-inline-block border mx-auto mb-2" style="background-color: rgba(37, 99, 235, 0.08); border-color: rgba(37, 99, 235, 0.15) !important;">
                    <i class="bi bi-anchor text-primary fs-4"></i>
                </div>
                <span class="text-secondary small d-block mb-1">Pelabuhan</span>
                <h4 class="fw-bold text-dark mb-0">Tanjung Priok</h4>
                <span class="text-secondary small" style="font-size: 0.65rem;">Jakarta Port Hub</span>
            </div>
        </div>

        <!-- 6. Cuaca Saat Ini -->
        <div class="col-6 col-lg-2">
            <div class="card p-3 h-100 border-0 text-center">
                <div class="p-2.5 rounded-circle d-inline-block border mx-auto mb-2" style="background-color: rgba(6, 182, 212, 0.08); border-color: rgba(6, 182, 212, 0.15) !important;">
                    <i class="bi bi-cloud-rain-heavy text-info fs-4"></i>
                </div>
                <span class="text-secondary small d-block mb-1">Cuaca</span>
                <h4 class="fw-bold text-dark mb-0">28°C</h4>
                <span class="text-primary small" style="font-size: 0.65rem;">Hujan Ringan</span>
            </div>
        </div>
    </div>

    <!-- Simulation Controls -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-3 border-0 d-flex flex-row gap-2">
                <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateSkeletonLoading()">
                    <i class="bi bi-hourglass-split me-2"></i>Simulasikan Loading Halaman
                </button>
            </div>
        </div>
    </div>

    <!-- Skeleton Loading Container -->
    <div id="skeleton-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                <div class="card p-4 border-0 skeleton-card" style="height: 180px;">
                    <div class="skeleton-shimmer mb-3" style="width: 30%; height: 24px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer mb-2" style="width: 100%; height: 16px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer mb-2" style="width: 95%; height: 16px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 70%; height: 16px; border-radius: 4px;"></div>
                </div>
                <div class="card p-4 border-0 skeleton-card" style="height: 300px;">
                    <div class="skeleton-shimmer" style="width: 100%; height: 100%; border-radius: 8px;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4 border-0 skeleton-card" style="height: 500px;">
                <div class="skeleton-shimmer mb-4" style="width: 50%; height: 24px; border-radius: 4px;"></div>
                <div class="skeleton-shimmer mb-3" style="width: 100%; height: 80px; border-radius: 12px;"></div>
                <div class="skeleton-shimmer mb-3" style="width: 100%; height: 80px; border-radius: 12px;"></div>
                <div class="skeleton-shimmer" style="width: 100%; height: 120px; border-radius: 12px;"></div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div id="main-content-grid" class="row g-4">
        <!-- Kolom Kiri (Konten Utama) -->
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                
                <!-- SECTION 1: Overview -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-text-fill text-primary me-2"></i>Ikhtisar Profil Logistik Negara</h5>
                    <p class="text-secondary mb-0" style="line-height: 1.6;">
                        Indonesia memegang peran krusial dalam rantai pasok global sebagai produsen utama komoditas penting seperti batubara, minyak kelapa sawit (CPO), nikel, dan karet. Berada di posisi strategis yang diapit oleh Samudra Hindia dan Pasifik, serta dilalui oleh Alur Laut Kepulauan Indonesia (ALKI) dan Selat Malaka, konektivitas pelabuhan utama di Indonesia menjadi indikator penting stabilitas pelayaran laut kawasan Asia Tenggara. Keamanan maritim dan efisiensi birokrasi pelabuhan terus mengalami peningkatan berkat digitalisasi pelabuhan yang diimplementasikan pada tahun 2026.
                    </p>
                </div>

                <!-- SECTION 2: Supply Chain Risk Overview -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Indeks Risiko Rantai Pasok Histori (12 Bulan)</h5>
                    <p class="text-secondary small mb-4">Grafik tingkat kerentanan pelayaran dan pergudangan domestik.</p>
                    
                    <!-- SVG Area Chart -->
                    <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 250px; background-color: #FAFCFF !important;">
                        <svg viewBox="0 0 600 200" class="w-100 h-100 p-2">
                            <defs>
                                <linearGradient id="detailChartGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="var(--primary)" stop-opacity="0.25"></stop>
                                    <stop offset="100%" stop-color="var(--primary)" stop-opacity="0.0"></stop>
                                </linearGradient>
                            </defs>
                            <!-- Grid lines -->
                            <line x1="40" y1="30" x2="560" y2="30" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            <line x1="40" y1="80" x2="560" y2="80" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            <line x1="40" y1="130" x2="560" y2="130" stroke="#E2E8F0" stroke-dasharray="4"></line>
                            
                            <!-- Path -->
                            <path d="M40,130 L90,110 Q140,80 190,105 T290,120 T390,95 T490,70 L560,40 L560,170 L40,170 Z" fill="url(#detailChartGrad)"></path>
                            <path d="M40,130 L90,110 Q140,80 190,105 T290,120 T390,95 T490,70 L560,40" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round"></path>

                            <!-- Nodes -->
                            <circle cx="560" cy="40" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                            <circle cx="490" cy="70" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>

                            <!-- Axis text -->
                            <text x="40" y="185" fill="#94A3B8" font-size="9" text-anchor="middle">Jul 25</text>
                            <text x="190" y="185" fill="#94A3B8" font-size="9" text-anchor="middle">Nov 25</text>
                            <text x="390" y="185" fill="#94A3B8" font-size="9" text-anchor="middle">Mar 26</text>
                            <text x="560" y="185" fill="#94A3B8" font-size="9" text-anchor="middle">Hari Ini</text>
                        </svg>
                    </div>
                </div>

                <!-- SECTION 3: Import & Export Summary -->
                <div class="row g-4">
                    <!-- Import Card -->
                    <div class="col-md-6">
                        <div class="card p-4 border-0 h-100">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-box-arrow-in-down text-primary me-2"></i>Ringkasan Impor</h5>
                            <div class="d-flex flex-column gap-2.5 mb-3" style="font-size: 0.825rem;">
                                <div class="d-flex justify-content-between"><span class="text-secondary">Volume Tahunan:</span><span class="text-dark fw-bold">USD 230 Miliar</span></div>
                                <div class="d-flex justify-content-between"><span class="text-secondary">Komoditas Utama:</span><span class="text-dark fw-medium text-end" style="max-width: 140px;">Mesin, Elektronik, Kimia, Gandum</span></div>
                                <div class="d-flex justify-content-between"><span class="text-secondary">Mitra Utama:</span><span class="text-dark fw-medium">China, Jepang, Singapura</span></div>
                            </div>
                            <span class="badge badge-primary px-3 py-2 text-center mt-auto"><i class="bi bi-shield-check me-1.5"></i>Aliran Impar Aman</span>
                        </div>
                    </div>

                    <!-- Export Card -->
                    <div class="col-md-6">
                        <div class="card p-4 border-0 h-100">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-box-arrow-up text-success me-2"></i>Ringkasan Ekspor</h5>
                            <div class="d-flex flex-column gap-2.5 mb-3" style="font-size: 0.825rem;">
                                <div class="d-flex justify-content-between"><span class="text-secondary">Volume Tahunan:</span><span class="text-dark fw-bold">USD 292 Miliar</span></div>
                                <div class="d-flex justify-content-between"><span class="text-secondary">Komoditas Utama:</span><span class="text-dark fw-medium text-end" style="max-width: 140px;">Batubara, CPO, Nikel, Karet, Tekstil</span></div>
                                <div class="d-flex justify-content-between"><span class="text-secondary">Mitra Utama:</span><span class="text-dark fw-medium">Tiongkok, AS, India, Jepang</span></div>
                            </div>
                            <span class="badge badge-success px-3 py-2 text-center mt-auto"><i class="bi bi-graph-up-arrow me-1.5"></i>Ekspor Mengalami Surplus</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: Port Activity -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-anchor text-primary me-2"></i>Aktivitas Pelabuhan Utama</h5>
                    
                    <div class="table-responsive-card">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Pelabuhan</th>
                                    <th>Status Operasional</th>
                                    <th>Kapasitas TEUs</th>
                                    <th>Tingkat Risiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Tanjung Priok, Jakarta</td>
                                    <td data-label="Status Operasional"><span class="badge badge-success">Online / Lancar</span></td>
                                    <td data-label="Kapasitas TEUs">8.5 Juta TEUs</td>
                                    <td data-label="Tingkat Risiko"><span class="badge badge-success">Rendah (1.20)</span></td>
                                </tr>
                                <tr>
                                    <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Tanjung Perak, Surabaya</td>
                                    <td data-label="Status Operasional"><span class="badge badge-success">Online / Lancar</span></td>
                                    <td data-label="Kapasitas TEUs">3.8 Juta TEUs</td>
                                    <td data-label="Tingkat Risiko"><span class="badge badge-success">Rendah (1.35)</span></td>
                                </tr>
                                <tr>
                                    <td data-label="Nama Pelabuhan" class="fw-bold text-dark">Belawan, Medan</td>
                                    <td data-label="Status Operasional"><span class="badge badge-warning">Hambatan Cuaca</span></td>
                                    <td data-label="Kapasitas TEUs">1.5 Juta TEUs</td>
                                    <td data-label="Tingkat Risiko"><span class="badge badge-warning">Sedang (2.65)</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- SECTION 5: Latest News -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-newspaper text-primary me-2"></i>Berita Terkait Indonesia</h5>
                    
                    <div class="d-flex flex-column gap-3.5">
                        <!-- News item 1 -->
                        <div class="row g-3 align-items-center pb-3 border-bottom">
                            <div class="col-auto">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                                    <i class="bi bi-gear-wide-connected fs-4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge badge-primary" style="font-size: 0.65rem;">Digitalisasi</span>
                                    <span class="text-secondary" style="font-size: 0.725rem;">17 Jul 2026</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">Digitalisasi Gerbang Logistik Otomatis Mulai Dioperasikan di Tanjung Priok</h6>
                                <button class="btn btn-light btn-sm px-3" style="min-height: 44px;" onclick="viewNewsDetail('Digitalisasi Gerbang Logistik Otomatis Mulai Dioperasikan di Tanjung Priok', 'Pemerintah meresmikan sistem IoT gerbang otomatis untuk mempercepat proses keluar masuk truk kontainer. Waktu tunggu diperkirakan terpangkas hingga 40%.')">Baca Berita</button>
                            </div>
                        </div>

                        <!-- News item 2 -->
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                                    <i class="bi bi-cloud-rain fs-4"></i>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge badge-warning" style="font-size: 0.65rem;">Cuaca</span>
                                    <span class="text-secondary" style="font-size: 0.725rem;">16 Jul 2026</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 0.875rem;">Gelombang Tinggi di Selat Sunda Menunda Distribusi Kargo Antar Pulau</h6>
                                <button class="btn btn-light btn-sm px-3" style="min-height: 44px;" onclick="viewNewsDetail('Gelombang Tinggi di Selat Sunda Menunda Distribusi Kargo Antar Pulau', 'BMKG merilis peringatan dini cuaca buruk yang memicu pembatasan tonase pelayaran kapal penyeberangan logistik Merak-Bakauheni.')">Baca Berita</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Kolom Kanan (Widgets & Info Panel) -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- WIDGET 1: Weather Widget -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-sun-fill text-info me-2"></i>Layanan Cuaca Pelabuhan</h5>
                    
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cloud-rain-heavy text-primary fs-1 me-3"></i>
                            <div>
                                <span class="text-secondary small d-block">Pelabuhan Tanjung Priok</span>
                                <h6 class="text-dark fw-bold mb-0">Hujan Ringan</h6>
                            </div>
                        </div>
                        <h2 class="fw-bold text-dark mb-0">28°C</h2>
                    </div>

                    <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Kelembapan:</span>
                            <span class="text-dark fw-semibold">85%</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Kecepatan Angin:</span>
                            <span class="text-dark fw-semibold">12 km/jam</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Arah Angin:</span>
                            <span class="text-dark fw-semibold">Barat Daya</span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 2: Currency Widget -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cash-stack text-success me-2"></i>Valuta Asing & Kurs</h5>
                    
                    <div class="p-3 rounded-4 border text-center mb-3" style="background-color: #F8FAFC !important;">
                        <span class="text-secondary small d-block">Mata Uang Nasional</span>
                        <h5 class="fw-bold text-dark mb-1">Rupiah (IDR)</h5>
                        <span class="badge badge-success">Simbol: Rp</span>
                    </div>

                    <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary">1 USD ke IDR:</span>
                            <span class="text-dark fw-bold">Rp16.245,00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary">1 EUR ke IDR:</span>
                            <span class="text-dark fw-bold">Rp17.650,00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Perubahan Harian:</span>
                            <span class="text-success fw-semibold"><i class="bi bi-caret-up-fill me-1"></i>+0.15% (Menguat)</span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 3: Risk Indicator Circular Progress -->
                <div class="card p-4 border-0 text-center">
                    <h5 class="fw-bold text-dark mb-3 text-start"><i class="bi bi-shield-exclamation text-primary me-2"></i>Indikator Kepadatan Risiko</h5>
                    
                    <!-- Circular Progress Bar SVG -->
                    <div class="position-relative d-flex align-items-center justify-content-center mx-auto my-3" style="width: 140px; height: 140px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                            <!-- Background track -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#F1F5F9" stroke-width="3.5"></circle>
                            <!-- Progress highlight (Risk Score 1.25 / 5.0 = 25% progress) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--success)" stroke-dasharray="25 75" stroke-dashoffset="100" stroke-width="4" stroke-linecap="round"></circle>
                        </svg>
                        <div class="position-absolute text-center">
                            <h3 class="fw-bold text-dark mb-0">1.25</h3>
                            <span class="text-secondary" style="font-size: 0.65rem;">Dari 5.0</span>
                        </div>
                    </div>
                    <span class="badge badge-success mt-2">Kondisi Sangat Aman</span>
                </div>

                <!-- WIDGET 4: Quick Information -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-stars text-primary me-2"></i>Informasi Cepat</h5>
                    
                    <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary">Ibukota:</span>
                            <span class="text-dark fw-bold">Jakarta</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary">Bahasa:</span>
                            <span class="text-dark fw-semibold">Bahasa Indonesia</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary">Zona Waktu:</span>
                            <span class="text-dark fw-semibold">UTC+7 s/d UTC+9</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary">Benua:</span>
                            <span class="text-dark fw-semibold">Asia (Tenggara)</span>
                        </div>
                    </div>
                </div>

                <!-- WIDGET 5: Recent Activity timeline -->
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Kontrol Koridor</h5>
                    
                    <div style="position: relative; padding-left: 20px;">
                        <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
                        
                        <!-- Timeline Item 1 -->
                        <div class="position-relative mb-3.5">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--success); border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Otomatisasi Gerbang Logistik</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Terminal Tanjung Priok mendigitalisasi IoT Gate.</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>3 jam yang lalu</span>
                            </div>
                        </div>

                        <!-- Timeline Item 2 -->
                        <div class="position-relative">
                            <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--warning); border: 2px solid #FFFFFF;"></div>
                            <div class="small">
                                <span class="text-dark fw-bold d-block">Peringatan Gelombang BMKG</span>
                                <span class="text-secondary d-block" style="font-size: 0.725rem;">Kecepatan penyeberangan logistik laut diturunkan sementara.</span>
                                <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>1 hari yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- News Detail Modal Component -->
<div class="modal fade" id="newsDetailModal" tabindex="-1" aria-labelledby="newsDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="newsDetailModalLabel"><i class="bi bi-newspaper text-primary me-2"></i>Berita Rantai Pasok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <h5 class="fw-bold text-dark mb-3" id="news-modal-title">Judul Berita</h5>
                <p class="text-secondary small mb-0" id="news-modal-body">Isi Berita...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
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
        }, 800);
    });

    // Skeleton loader simulation trigger
    function simulateSkeletonLoading() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'flex';
        
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    }

    // Toggle watchlist button action
    function toggleWatchlist() {
        const btn = document.getElementById('btn-watchlist');
        if (btn.classList.contains('btn-primary')) {
            btn.className = 'btn btn-success';
            btn.innerHTML = '<i class="bi bi-bookmark-star-fill me-2"></i>Terpantau di Watchlist';
            alert('Negara Indonesia berhasil ditambahkan ke Watchlist pemantauan!');
        } else {
            btn.className = 'btn btn-primary';
            btn.innerHTML = '<i class="bi bi-bookmark-star me-2"></i>Tambah ke Watchlist';
            alert('Negara Indonesia dihapus dari Watchlist pemantauan.');
        }
    }

    // Open News Detail Modal
    function viewNewsDetail(title, body) {
        document.getElementById('news-modal-title').textContent = title;
        document.getElementById('news-modal-body').textContent = body;
        
        const modal = new bootstrap.Modal(document.getElementById('newsDetailModal'));
        modal.show();
    }
</script>

<style>
    /* Skeleton Card style */
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
