@extends('layouts.app')

@section('title', 'Detail Negara - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0">

    <!-- Top Breadcrumb Showcase -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-white border p-3 rounded-4 shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none" style="color: var(--primary);"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('countries') }}" class="text-decoration-none" style="color: var(--primary);">Negara</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Detail Negara</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Country Detail Header -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <span class="fs-1 me-3">🇮🇩</span>
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Indonesia (ID)</h3>
                        <span class="badge badge-success"><i class="bi bi-check-circle-fill me-1"></i>Koneksi Jalur Aman</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: General Info, Economic, Map, Ports, News -->
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                
                <!-- Row: Info & Economy -->
                <div class="row g-4">
                    <!-- Card Informasi Negara -->
                    <div class="col-md-6">
                        <div class="card p-4 h-100 border-0 shadow-sm">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Informasi Negara</h5>
                            <div class="d-flex flex-column gap-2.5">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Ibu Kota</span>
                                    <span class="text-dark fw-semibold small">Jakarta</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Benua</span>
                                    <span class="text-dark fw-semibold small">Asia (Tenggara)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Populasi</span>
                                    <span class="text-dark fw-semibold small">275 Juta Jiwa</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Luas Wilayah</span>
                                    <span class="text-dark fw-semibold small">1.905.000 km²</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Zona Waktu</span>
                                    <span class="text-dark fw-semibold small">WIB, WITA, WIT (UTC+7 s/d +9)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Bahasa Resmi</span>
                                    <span class="text-dark fw-semibold small">Bahasa Indonesia</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small">Mata Uang</span>
                                    <span class="text-dark fw-semibold small">Rupiah (IDR)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Ekonomi -->
                    <div class="col-md-6">
                        <div class="card p-4 h-100 border-0 shadow-sm">
                            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-currency-dollar text-success me-2"></i>Kartu Ekonomi</h5>
                            <div class="d-flex flex-column gap-2.5">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">PDB (GDP) Nominal</span>
                                    <span class="text-dark fw-semibold small">USD 1.37 Triliun</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Tingkat Inflasi</span>
                                    <span class="text-dark fw-semibold small">2.8%</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                    <span class="text-secondary small">Ekspor Utama</span>
                                    <span class="text-dark fw-semibold small">USD 292 Miliar (Batubara, CPO)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary small">Impor Utama</span>
                                    <span class="text-dark fw-semibold small">USD 230 Miliar (Mesin, Elektronik)</span>
                                </div>
                            </div>
                            <div class="p-3 bg-light rounded-3 mt-3 border text-center">
                                <span class="text-success small fw-bold"><i class="bi bi-graph-up-arrow me-1"></i>Neraca Perdagangan Surplus</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peta Negara Placeholder -->
                <div class="card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-map text-primary me-2"></i>Konektivitas Spasial Wilayah</h6>
                    <div class="border rounded-4 d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 300px; background-color: #FAFCFF; background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px); background-size: 24px 24px;">
                        <div class="text-center position-relative z-index-1">
                            <div class="p-3 rounded-circle d-inline-block border mb-3" style="background-color: rgba(14, 165, 233, 0.08); border-color: rgba(14, 165, 233, 0.2) !important;">
                                <i class="bi bi-globe fs-2 text-primary"></i>
                            </div>
                            <h6 class="text-dark fw-bold mb-1">Visualisasi Spasial Wilayah Negara</h6>
                            <p class="text-secondary small mb-0">Integrasi peta interaktif Leaflet.js khusus batas teritorial negara siap dihubungkan pada tahap berikutnya.</p>
                        </div>
                    </div>
                </div>

                <!-- Panel Pelabuhan Utama -->
                <div class="card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-anchor text-primary me-2"></i>Panel Pelabuhan Utama</h6>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Pelabuhan</th>
                                    <th>Tipe Hub</th>
                                    <th>Kapasitas</th>
                                    <th>Status Pelayaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-semibold text-dark">Tanjung Priok</td>
                                    <td>Internasional / Hub Utama</td>
                                    <td>8.5 Juta TEUs</td>
                                    <td><span class="badge badge-success">Online / Lancar</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Tanjung Perak</td>
                                    <td>Domestik / Hub Timur</td>
                                    <td>3.8 Juta TEUs</td>
                                    <td><span class="badge badge-success">Online / Lancar</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-dark">Belawan</td>
                                    <td>Regional Hub</td>
                                    <td>1.5 Juta TEUs</td>
                                    <td><span class="badge badge-success">Online / Lancar</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Panel Berita Logistik -->
                <div class="card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-newspaper text-warning me-2"></i>Berita & Peristiwa Terkait</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light h-100" style="background-color: #F8FAFC !important;">
                                <span class="badge bg-primary text-light mb-1.5" style="font-size: 0.65rem;">Info Pelabuhan</span>
                                <h6 class="fw-bold text-dark small mb-1">Modernisasi Terminal Petikemas Tanjung Priok Rampung 100%</h6>
                                <p class="text-secondary small mb-2">Kapasitas bongkar muat logistik meningkat sebesar 15% pada kuartal ini.</p>
                                <span class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>1 hari yang lalu</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 border rounded-3 bg-light h-100" style="background-color: #F8FAFC !important;">
                                <span class="badge bg-warning text-dark mb-1.5" style="font-size: 0.65rem;">Cuaca Buruk</span>
                                <h6 class="fw-bold text-dark small mb-1">Gelombang Tinggi Selat Sunda Batasi Penyeberangan Logistik</h6>
                                <p class="text-secondary small mb-2">BMKG mengimbau armada logistik berhati-hati melintasi perairan Lampung.</p>
                                <span class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>3 hari yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Column: Risks, Weather, Currency -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- Panel Risk (Indikator Kategori Risiko) -->
                <div class="card p-4 border-0 shadow-sm">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-exclamation text-danger me-2"></i>Analisis Risiko Rantai Pasok</h5>
                    <p class="text-secondary small mb-4">Indikator risiko modular berdasarkan data makroekonomi dan geopolitik global.</p>
                    
                    <div class="d-flex flex-column gap-3.5">
                        <!-- Risk 1 -->
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary small fw-medium">Risiko Ekonomi</span>
                                <span class="text-dark small fw-bold">12% (Sangat Rendah)</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 12%;" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Risk 2 -->
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary small fw-medium">Risiko Politik</span>
                                <span class="text-dark small fw-bold">25% (Rendah)</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Risk 3 -->
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary small fw-medium">Risiko Cuaca</span>
                                <span class="text-dark small fw-bold">45% (Sedang)</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 45%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Risk 4 -->
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary small fw-medium">Risiko Logistik</span>
                                <span class="text-dark small fw-bold">18% (Rendah)</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 18%;" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- Risk 5 -->
                        <div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary small fw-medium">Risiko Perdagangan</span>
                                <span class="text-dark small fw-bold">10% (Sangat Rendah)</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 10%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Cuaca -->
                <div class="card p-4 border-0 shadow-sm">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-sun-fill text-info me-2"></i>Kondisi Meteorologi</h5>
                    
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cloud-rain-heavy text-primary fs-1 me-3"></i>
                            <div>
                                <span class="text-secondary small d-block">Cuaca Saat Ini</span>
                                <span class="text-dark fw-bold fs-5">Hujan Ringan</span>
                            </div>
                        </div>
                        <h2 class="fw-bold text-dark mb-0">28°C</h2>
                    </div>

                    <div class="d-flex flex-column gap-2.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Kelembapan</span>
                            <span class="text-dark fw-semibold small">85%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Kecepatan Angin</span>
                            <span class="text-dark fw-semibold small">12 km/jam (Barat Daya)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Tekanan Udara</span>
                            <span class="text-dark fw-semibold small">1011 hPa</span>
                        </div>
                    </div>
                </div>

                <!-- Kartu Nilai Tukar -->
                <div class="card p-4 border-0 shadow-sm">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cash-stack text-success me-2"></i>Informasi Valuta Asing</h5>
                    
                    <div class="p-3 bg-light rounded-4 border text-center mb-3" style="background-color: #F8FAFC !important;">
                        <span class="text-secondary small d-block">Mata Uang Lokal</span>
                        <h4 class="fw-bold text-dark mb-1">Rupiah (IDR)</h4>
                        <span class="badge badge-info" style="font-size: 0.65rem;">Simbol: Rp</span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary small">Kurs USD terhadap IDR</span>
                            <span class="text-dark fw-semibold small">Rp16.245,00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <span class="text-secondary small">Kurs EUR terhadap IDR</span>
                            <span class="text-dark fw-semibold small">Rp17.650,00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Perubahan Harian</span>
                            <span class="text-success small fw-bold"><i class="bi bi-caret-up-fill me-1"></i>+0.15% (Menguat)</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
