@extends('layouts.app')

@section('title', 'Dashboard - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0">

    <!-- Row 1: KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Negara Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-dark mb-0">195</h3>
                    <span class="text-primary small fw-semibold"><i class="bi bi-globe2 me-1"></i>Cakupan Global</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(14, 165, 233, 0.1); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2: Risiko Tinggi -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Tinggi</span>
                    <h3 class="fw-bold text-danger mb-0">12</h3>
                    <span class="text-danger small fw-semibold"><i class="bi bi-exclamation-octagon-fill me-1"></i>Butuh Tindakan</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.1); color: var(--danger);">
                    <i class="bi bi-exclamation-octagon-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3: Risiko Sedang -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Sedang</span>
                    <h3 class="fw-bold text-warning mb-0">48</h3>
                    <span class="text-warning small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Dalam Pemantauan</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.1); color: var(--warning);">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4: Risiko Rendah -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 shadow-sm d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Rendah</span>
                    <h3 class="fw-bold text-success mb-0">135</h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-shield-check me-1"></i>Jalur Stabil</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.1); color: var(--success);">
                    <i class="bi bi-shield-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: World Map & Highest Risk Countries -->
    <div class="row g-4 mb-4">
        <!-- World Map Placeholder -->
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-map-fill text-primary me-2"></i>Visualisasi Peta Risiko Global</h5>
                    <span class="badge badge-info px-2.5 py-1 text-uppercase rounded-pill" style="font-size: 0.7rem; font-weight: 600;">Leaflet Engine</span>
                </div>
                
                <!-- Map Container Box -->
                <div class="position-relative border rounded-4 overflow-hidden d-flex flex-column align-items-center justify-content-center" style="height: 400px; background-color: #FAFCFF; background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px); background-size: 24px 24px;">
                    <div class="text-center p-4">
                        <div class="p-3 rounded-circle d-inline-block border mb-3" style="background-color: rgba(14, 165, 233, 0.08); border-color: rgba(14, 165, 233, 0.2) !important;">
                            <i class="bi bi-geo-alt-fill fs-2 text-primary"></i>
                        </div>
                        <h6 class="text-dark fw-bold mb-1">Modul Peta Rantai Pasok</h6>
                        <p class="text-secondary small mb-3" style="max-width: 380px;">Integrasi pemetaan interaktif Leaflet.js siap dimuat secara real-time pada tahap pengembangan berikutnya.</p>
                        <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill small">Pusat Peta: 0.0000, 0.0000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Highest Risk Countries Panel -->
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-sort-down text-danger me-2"></i>Panel Negara Risiko</h5>
                <p class="text-secondary small mb-3">Daftar negara dengan indeks kerentanan logistik & rantai pasok tertinggi saat ini.</p>
                
                <div class="d-flex flex-column gap-3">
                    <!-- Item 1 -->
                    <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-dark fw-semibold small">Amerika Serikat</span>
                            <span class="badge badge-danger">Tinggi - 4.82</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 82%;" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-secondary small mt-1.5 d-block" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>Diperbarui: Baru saja</span>
                    </div>

                    <!-- Item 2 -->
                    <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-dark fw-semibold small">Jepang</span>
                            <span class="badge badge-danger">Tinggi - 4.15</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-secondary small mt-1.5 d-block" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>Diperbarui: Baru saja</span>
                    </div>

                    <!-- Item 3 -->
                    <div class="p-3 border rounded-4 bg-light" style="background-color: #F8FAFC !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <span class="text-dark fw-semibold small">Jerman</span>
                            <span class="badge badge-warning">Sedang - 3.45</span>
                        </div>
                        <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 58%;" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-secondary small mt-1.5 d-block" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>Diperbarui: Baru saja</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Trend Global & Ringkasan Risiko -->
    <div class="row g-4 mb-4">
        <!-- Trend Global Line Chart -->
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Tren Indeks Risiko Global</h5>
                    <span class="badge badge-info px-2.5 py-1 text-uppercase rounded-pill" style="font-size: 0.7rem; font-weight: 600;">Chart.js Engine</span>
                </div>

                <!-- Line Chart mockup -->
                <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 300px; background-color: #FAFCFF;">
                    <svg viewBox="0 0 500 200" class="w-100 h-100 position-absolute top-0 start-0 opacity-20" style="pointer-events: none;">
                        <path d="M 0 150 Q 100 110, 200 120 T 400 70 T 500 30" fill="none" stroke="var(--primary)" stroke-width="3.5"></path>
                        <path d="M 0 150 Q 100 110, 200 120 T 400 70 T 500 30 L 500 200 L 0 200 Z" fill="rgba(14, 165, 233, 0.05)"></path>
                        <line x1="0" y1="50" x2="500" y2="50" stroke="#CBD5E1" stroke-dasharray="4"></line>
                        <line x1="0" y1="100" x2="500" y2="100" stroke="#CBD5E1" stroke-dasharray="4"></line>
                        <line x1="0" y1="150" x2="500" y2="150" stroke="#CBD5E1" stroke-dasharray="4"></line>
                    </svg>
                    <div class="text-center position-relative z-index-1">
                        <i class="bi bi-bar-chart-line fs-2 text-secondary mb-2 d-block"></i>
                        <h6 class="text-dark fw-bold mb-1">Tren Fluktuasi Logistik</h6>
                        <p class="text-secondary small mb-0">Visualisasi grafik historis siap terhubung dengan Chart.js.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Risiko Donut Chart -->
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Ringkasan Risiko</h5>
                
                <div class="border rounded-4 d-flex flex-column align-items-center justify-content-center p-3" style="height: 300px; background-color: #FAFCFF;">
                    <!-- Donut Mockup -->
                    <div class="position-relative d-flex align-items-center justify-content-center mb-3" style="width: 140px; height: 140px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#E2E8F0" stroke-width="3"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--danger)" stroke-dasharray="20 80" stroke-dashoffset="100" stroke-width="3.5"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--warning)" stroke-dasharray="30 70" stroke-dashoffset="80" stroke-width="3.5"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--success)" stroke-dasharray="50 50" stroke-dashoffset="50" stroke-width="3.5"></circle>
                        </svg>
                        <div class="position-absolute text-center">
                            <span class="text-dark fw-bold fs-4">195</span>
                            <span class="d-block text-secondary" style="font-size: 0.65rem;">Total Jalur</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="w-100 d-flex flex-column gap-1.5 mt-2">
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-circle-fill text-danger me-1.5" style="font-size: 0.55rem;"></i> Risiko Tinggi</span>
                            <span class="text-dark fw-bold">20%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-circle-fill text-warning me-1.5" style="font-size: 0.55rem;"></i> Risiko Sedang</span>
                            <span class="text-dark fw-bold">30%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-circle-fill text-success me-1.5" style="font-size: 0.55rem;"></i> Risiko Rendah</span>
                            <span class="text-dark fw-bold">50%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Weather, Currency, and News Panels -->
    <div class="row g-4 mb-4">
        <!-- Weather Panel (Cuaca) -->
        <div class="col-md-4">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-sun-fill text-primary me-2"></i>Prakiraan Cuaca Pelabuhan</h5>
                <p class="text-secondary small mb-3">Kondisi meteorologi pelabuhan hub utama rantai pasok global.</p>
                
                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex align-items-center justify-content-between p-2.5 border rounded-3 bg-light" style="background-color: #F8FAFC !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cloud-rain-heavy-fill text-primary fs-4 me-2"></i>
                            <div>
                                <span class="text-dark fw-bold small d-block">Tanjung Priok</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">Jakarta, ID</span>
                            </div>
                        </div>
                        <span class="text-dark fw-semibold small">28°C / Hujan</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 border rounded-3 bg-light" style="background-color: #F8FAFC !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-sun-fill text-warning fs-4 me-2"></i>
                            <div>
                                <span class="text-dark fw-bold small d-block">Port of Singapore</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">Singapura, SG</span>
                            </div>
                        </div>
                        <span class="text-dark fw-semibold small">31°C / Cerah</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exchange Rate Panel (Nilai Tukar) -->
        <div class="col-md-4">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cash-stack text-success me-2"></i>Nilai Tukar Mata Uang</h5>
                <p class="text-secondary small mb-3">Kurs konversi mata uang asing utama terhadap Rupiah (IDR).</p>
                
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                        <span class="text-dark small fw-medium">USD (Dolar Amerika)</span>
                        <span class="text-success small fw-semibold">Rp16.245,00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                        <span class="text-dark small fw-medium">EUR (Euro)</span>
                        <span class="text-success small fw-semibold">Rp17.650,00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                        <span class="text-dark small fw-medium">SGD (Dolar Singapura)</span>
                        <span class="text-success small fw-semibold">Rp12.050,00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Panel (Berita) -->
        <div class="col-md-4">
            <div class="card p-4 h-100 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-newspaper text-warning me-2"></i>Berita Logistik Rantai Pasok</h5>
                <p class="text-secondary small mb-3">Informasi liputan berita internasional terhangat industri global.</p>
                
                <div class="d-flex flex-column gap-3">
                    <div class="p-2 border rounded-3 bg-light" style="background-color: #F8FAFC !important;">
                        <span class="badge bg-danger text-light mb-1.5" style="font-size: 0.65rem;">Krisis</span>
                        <a href="#" class="text-dark text-decoration-none fw-semibold small d-block mb-1">Kepadatan Ekstra Terjadi di Dermaga Utama Shanghai</a>
                        <span class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>3 jam yang lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 5: Global Status API (Global Monitoring Center) -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-hdd-network-fill text-primary me-2"></i>Status Koneksi Layanan API</h5>
                        <p class="text-secondary small mb-0">Pemantauan online keaktifan API pihak ketiga penyedia basis intelijen platform.</p>
                    </div>
                    <span class="badge badge-success px-3 py-1.5 rounded-pill small"><i class="bi bi-check-circle-fill me-1"></i>Semua Sistem Online</span>
                </div>

                <div class="row g-3">
                    <!-- Service 1 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border rounded-4 bg-light text-center" style="background-color: #F8FAFC !important;">
                            <span class="text-secondary d-block small mb-2">REST Countries</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 2 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border rounded-4 bg-light text-center" style="background-color: #F8FAFC !important;">
                            <span class="text-secondary d-block small mb-2">World Bank</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border rounded-4 bg-light text-center" style="background-color: #F8FAFC !important;">
                            <span class="text-secondary d-block small mb-2">Open Meteo</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 4 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border rounded-4 bg-light text-center" style="background-color: #F8FAFC !important;">
                            <span class="text-secondary d-block small mb-2">Exchange Rate</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 5 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border rounded-4 bg-light text-center" style="background-color: #F8FAFC !important;">
                            <span class="text-secondary d-block small mb-2">GNews API</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 6 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border rounded-4 bg-light text-center" style="background-color: #F8FAFC !important;">
                            <span class="text-secondary d-block small mb-2">World Port Index</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
