@extends('layouts.app')

@section('title', 'Beranda - SupplyChain Command Center')

@section('content')
<div class="container-fluid p-0">
    
    <!-- Row 1: KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Negara Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card-custom p-4 h-100 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-white mb-0">195</h3>
                    <span class="text-primary small fw-semibold"><i class="bi bi-globe2 me-1"></i>Cakupan Global</span>
                </div>
                <div class="bg-primary-subtle text-primary p-3 rounded-4 border border-primary-subtle" style="--bs-bg-opacity: 0.1;">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2: Risiko Tinggi -->
        <div class="col-xl-3 col-md-6">
            <div class="card-custom p-4 h-100 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Tinggi</span>
                    <h3 class="fw-bold text-danger mb-0">12</h3>
                    <span class="text-danger small fw-semibold"><i class="bi bi-exclamation-octagon-fill me-1"></i>Butuh Tindakan</span>
                </div>
                <div class="bg-danger-subtle text-danger p-3 rounded-4 border border-danger-subtle" style="--bs-bg-opacity: 0.1;">
                    <i class="bi bi-exclamation-octagon-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3: Risiko Sedang -->
        <div class="col-xl-3 col-md-6">
            <div class="card-custom p-4 h-100 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Sedang</span>
                    <h3 class="fw-bold text-warning mb-0">48</h3>
                    <span class="text-warning small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Dalam Pemantauan</span>
                </div>
                <div class="bg-warning-subtle text-warning p-3 rounded-4 border border-warning-subtle" style="--bs-bg-opacity: 0.1;">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4: Risiko Rendah -->
        <div class="col-xl-3 col-md-6">
            <div class="card-custom p-4 h-100 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Rendah</span>
                    <h3 class="fw-bold text-success mb-0">135</h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-shield-check me-1"></i>Jalur Stabil</span>
                </div>
                <div class="bg-success-subtle text-success p-3 rounded-4 border border-success-subtle" style="--bs-bg-opacity: 0.1;">
                    <i class="bi bi-shield-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: World Map & Highest Risk Countries -->
    <div class="row g-4 mb-4">
        <!-- World Map Placeholder -->
        <div class="col-lg-8">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="bi bi-map-fill text-primary me-2"></i>Visualisasi Peta Risiko Global</h5>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 text-uppercase rounded-pill" style="font-size: 0.7rem; font-weight: 600;">Leaflet Engine</span>
                </div>
                
                <!-- Map Container Box -->
                <div class="position-relative border border-secondary rounded-4 bg-dark overflow-hidden d-flex flex-column align-items-center justify-content-center" style="height: 400px; background-image: radial-gradient(var(--border-color) 1px, transparent 1px); background-size: 20px 20px;">
                    <div class="text-center p-4">
                        <div class="bg-secondary-subtle text-secondary p-3 rounded-circle d-inline-block border border-secondary mb-3" style="--bs-bg-opacity: 0.2;">
                            <i class="bi bi-geo-alt-fill fs-2 text-primary animate-bounce"></i>
                        </div>
                        <h6 class="text-white fw-bold mb-1">Modul Peta Rantai Pasok</h6>
                        <p class="text-secondary small mb-3" style="max-width: 350px;">Integrasi pemetaan interaktif Leaflet.js siap dimuat secara real-time pada tahap berikutnya.</p>
                        <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-1.5 rounded-pill small">Koordinat Pusat: 0.0000, 0.0000</span>
                    </div>
                    <!-- Cyber lines overlay -->
                    <div class="position-absolute w-100 h-100 top-0 start-0 pointer-events-none opacity-25" style="border: 2px dashed rgba(37, 99, 235, 0.2); pointer-events: none;"></div>
                </div>
            </div>
        </div>

        <!-- Highest Risk Countries Panel -->
        <div class="col-lg-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-sort-down text-danger me-2"></i>Negara Risiko Tertinggi</h5>
                <p class="text-secondary small mb-3">Daftar negara dengan kerentanan logistik & rantai pasok paling kritis.</p>
                
                <div class="d-flex flex-column gap-3">
                    <!-- Item 1 -->
                    <div class="p-3 border border-secondary rounded-4 bg-dark" style="background-color: rgba(30, 41, 59, 0.3) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-white fw-semibold small">Amerika Serikat</span>
                            <span class="badge-custom badge-custom-danger">Tinggi - 4.82</span>
                        </div>
                        <div class="progress bg-secondary" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 82%;" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-secondary small mt-1 d-block" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>Diperbarui: Baru saja</span>
                    </div>

                    <!-- Item 2 -->
                    <div class="p-3 border border-secondary rounded-4 bg-dark" style="background-color: rgba(30, 41, 59, 0.3) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-white fw-semibold small">Jepang</span>
                            <span class="badge-custom badge-custom-danger">Tinggi - 4.15</span>
                        </div>
                        <div class="progress bg-secondary" style="height: 6px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-secondary small mt-1 d-block" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>Diperbarui: Baru saja</span>
                    </div>

                    <!-- Item 3 -->
                    <div class="p-3 border border-secondary rounded-4 bg-dark" style="background-color: rgba(30, 41, 59, 0.3) !important;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-white fw-semibold small">Jerman</span>
                            <span class="badge-custom badge-custom-warning">Sedang - 3.45</span>
                        </div>
                        <div class="progress bg-secondary" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 58%;" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-secondary small mt-1 d-block" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>Diperbarui: Baru saja</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Chart.js Line Chart & Donut Chart -->
    <div class="row g-4 mb-4">
        <!-- Risk History Trend Line Chart -->
        <div class="col-lg-8">
            <div class="card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-white mb-0"><i class="bi bi-graph-up text-primary me-2"></i>Tren Indeks Risiko Historis</h5>
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary px-2.5 py-1 text-uppercase rounded-pill" style="font-size: 0.7rem; font-weight: 600;">Chart.js Engine</span>
                </div>

                <!-- Chart container with a beautiful vector path mockup to simulate Chart.js lines -->
                <div class="border border-secondary rounded-4 bg-dark position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 300px;">
                    <svg viewBox="0 0 500 200" class="w-100 h-100 position-absolute top-0 start-0 opacity-25" style="pointer-events: none;">
                        <path d="M 0 160 Q 100 120, 200 130 T 400 80 T 500 40" fill="none" stroke="var(--primary)" stroke-width="3"></path>
                        <path d="M 0 160 Q 100 120, 200 130 T 400 80 T 500 40 L 500 200 L 0 200 Z" fill="rgba(37, 99, 235, 0.05)"></path>
                        <line x1="0" y1="50" x2="500" y2="50" stroke="rgba(255,255,255,0.05)"></line>
                        <line x1="0" y1="100" x2="500" y2="100" stroke="rgba(255,255,255,0.05)"></line>
                        <line x1="0" y1="150" x2="500" y2="150" stroke="rgba(255,255,255,0.05)"></line>
                    </svg>
                    <div class="text-center position-relative z-index-1">
                        <i class="bi bi-bar-chart-line fs-2 text-secondary mb-2 d-block"></i>
                        <h6 class="text-white fw-bold mb-1">Grafik Pemantauan Tren</h6>
                        <p class="text-secondary small mb-0">Modul Chart.js siap memvisualisasikan tren data logistik saat API diaktifkan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Donut Chart Risk Summary -->
        <div class="col-lg-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Ringkasan Sebaran Risiko</h5>
                
                <div class="border border-secondary rounded-4 bg-dark d-flex flex-column align-items-center justify-content-center p-3" style="height: 300px;">
                    <!-- Visual mock donut -->
                    <div class="position-relative d-flex align-items-center justify-content-center mb-3" style="width: 140px; height: 140px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                            <!-- Background circle -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="3"></circle>
                            <!-- Danger sector -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--danger)" stroke-dasharray="25 75" stroke-dashoffset="100" stroke-width="3.5"></circle>
                            <!-- Warning sector -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--warning)" stroke-dasharray="35 65" stroke-dashoffset="75" stroke-width="3.5"></circle>
                            <!-- Success sector -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--success)" stroke-dasharray="40 60" stroke-dashoffset="40" stroke-width="3.5"></circle>
                        </svg>
                        <div class="position-absolute text-center">
                            <span class="text-white fw-bold fs-4">195</span>
                            <span class="d-block text-secondary" style="font-size: 0.65rem;">Total Jalur</span>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="w-100 d-flex flex-column gap-1.5 mt-2">
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-circle-fill text-danger me-1" style="font-size: 0.6rem;"></i> Risiko Tinggi</span>
                            <span class="text-light fw-bold">25%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.6rem;"></i> Risiko Sedang</span>
                            <span class="text-light fw-bold">35%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <span class="text-secondary"><i class="bi bi-circle-fill text-success me-1" style="font-size: 0.6rem;"></i> Risiko Rendah</span>
                            <span class="text-light fw-bold">40%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Weather, Currency, and News Panels -->
    <div class="row g-4 mb-4">
        <!-- Weather Panel (Cuaca Pelabuhan) -->
        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-cloud-sun-fill text-info me-2"></i>Status Cuaca Pelabuhan</h5>
                <p class="text-secondary small mb-3">Informasi cuaca terkini dari pelabuhan logistik internasional.</p>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between p-2.5 border border-secondary rounded-3 bg-dark">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cloud-rain-heavy-fill text-info fs-4 me-2"></i>
                            <div>
                                <span class="text-white fw-bold small d-block">Tanjung Priok</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">Jakarta, ID</span>
                            </div>
                        </div>
                        <span class="text-light fw-semibold">28°C / Hujan</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 border border-secondary rounded-3 bg-dark">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-sun-fill text-warning fs-4 me-2"></i>
                            <div>
                                <span class="text-white fw-bold small d-block">Port of Singapore</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">Singapura, SG</span>
                            </div>
                        </div>
                        <span class="text-light fw-semibold">31°C / Cerah</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exchange Rate Panel (Nilai Tukar) -->
        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-cash-stack text-success me-2"></i>Konverter Mata Uang Asing</h5>
                <p class="text-secondary small mb-3">Nilai konversi mata uang utama global terhadap Rupiah (IDR).</p>
                
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom border-secondary">
                        <span class="text-white small fw-medium">USD (Dolar Amerika)</span>
                        <span class="text-success small fw-semibold">Rp16.245,00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom border-secondary">
                        <span class="text-white small fw-medium">EUR (Euro)</span>
                        <span class="text-success small fw-semibold">Rp17.650,00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom border-secondary">
                        <span class="text-white small fw-medium">SGD (Dolar Singapura)</span>
                        <span class="text-success small fw-semibold">Rp12.050,00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Panel (Berita Logistik Global) -->
        <div class="col-md-4">
            <div class="card-custom p-4 h-100">
                <h5 class="fw-bold text-white mb-3"><i class="bi bi-newspaper text-warning me-2"></i>Informasi Berita Terbaru</h5>
                <p class="text-secondary small mb-3">Kliping berita internasional terkini seputar logistik & manufaktur.</p>
                
                <div class="d-flex flex-column gap-3">
                    <div class="p-2 border border-secondary rounded-3 bg-dark">
                        <span class="badge bg-danger mb-1" style="font-size: 0.65rem;">Krisis Terkini</span>
                        <a href="#" class="text-white text-decoration-none fw-semibold small d-block mb-1">Blokade Pelabuhan Terjadi di Jalur Utama Laut Merah</a>
                        <span class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>2 jam yang lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 5: Global Monitoring Center (Service Monitor Panel) -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-white mb-1"><i class="bi bi-hdd-network-fill text-primary me-2"></i>Global Monitoring Center</h5>
                        <p class="text-secondary small mb-0">Status keaktifan server API integrasi penyedia data eksternal secara real-time.</p>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill small"><i class="bi bi-check-circle-fill me-1"></i>Semua Sistem Online</span>
                </div>

                <div class="row g-3">
                    <!-- Service 1 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border border-secondary rounded-4 bg-dark text-center">
                            <span class="text-secondary d-block small mb-2">Open Meteo</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 2 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border border-secondary rounded-4 bg-dark text-center">
                            <span class="text-secondary d-block small mb-2">REST Countries</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border border-secondary rounded-4 bg-dark text-center">
                            <span class="text-secondary d-block small mb-2">World Bank</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 4 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border border-secondary rounded-4 bg-dark text-center">
                            <span class="text-secondary d-block small mb-2">Exchange Rate</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 5 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border border-secondary rounded-4 bg-dark text-center">
                            <span class="text-secondary d-block small mb-2">GNews API</span>
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="pulse-indicator"></span>
                                <span class="text-success small fw-semibold">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Service 6 -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="p-3 border border-secondary rounded-4 bg-dark text-center">
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
