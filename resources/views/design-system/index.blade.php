@extends('layouts.app')

@section('title', 'Design System Foundation - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Design System Foundation</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Design System Foundation</h3>
                        <p class="text-secondary small mb-0">Identitas Visual dan Komponen Rantai Pasok Global (Milestone 3.1)</p>
                    </div>
                    <span class="badge badge-primary px-3 py-2 rounded-pill"><i class="bi bi-shield-check me-1"></i>Siap Enterprise</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Color Palette & Typography -->
    <div class="row g-4 mb-4">
        <!-- Color Palette -->
        <div class="col-lg-7">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-palette text-primary me-2"></i>Palet Warna (Color Palette)</h5>
                <p class="text-secondary small mb-4">Warna resmi yang digunakan untuk membangun antarmuka kontrol logistik enterprise global.</p>
                
                <div class="row g-3">
                    <!-- Primary -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #2563EB; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Primary</span>
                                <code class="small text-secondary">#2563EB</code>
                                <span class="d-block text-muted small mt-1">Aksen utama & navigasi aktif.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #123458; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Sidebar</span>
                                <code class="small text-secondary">#123458</code>
                                <span class="d-block text-muted small mt-1">Latar belakang menu samping.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Background -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #F8FAFC; height: 80px; border-bottom: 1px solid var(--border-color);"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Background</span>
                                <code class="small text-secondary">#F8FAFC</code>
                                <span class="d-block text-muted small mt-1">Warna latar utama aplikasi.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Card -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #FFFFFF; height: 80px; border-bottom: 1px solid var(--border-color);"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Card</span>
                                <code class="small text-secondary">#FFFFFF</code>
                                <span class="d-block text-muted small mt-1">Warna latar modul/kartu.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Success -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #22C55E; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Success</span>
                                <code class="small text-secondary">#22C55E</code>
                                <span class="d-block text-muted small mt-1">Status stabil / normal.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Warning -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #F59E0B; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Warning</span>
                                <code class="small text-secondary">#F59E0B</code>
                                <span class="d-block text-muted small mt-1">Status pemantauan / risiko sedang.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Danger -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #EF4444; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Danger</span>
                                <code class="small text-secondary">#EF4444</code>
                                <span class="d-block text-muted small mt-1">Status kritis / risiko tinggi.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #06B6D4; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Info</span>
                                <code class="small text-secondary">#06B6D4</code>
                                <span class="d-block text-muted small mt-1">Status informasi / sekunder.</span>
                            </div>
                        </div>
                    </div>
                    <!-- Border -->
                    <div class="col-md-4 col-sm-6">
                        <div class="border rounded-4 overflow-hidden h-100 shadow-sm bg-white">
                            <div style="background-color: #E2E8F0; height: 80px;"></div>
                            <div class="p-3 text-start">
                                <span class="fw-bold text-dark d-block">Border</span>
                                <code class="small text-secondary">#E2E8F0</code>
                                <span class="d-block text-muted small mt-1">Garis pemisah komponen.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Typography -->
        <div class="col-lg-5">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-type text-primary me-2"></i>Tipografi (Typography)</h5>
                <p class="text-secondary small mb-3">Font Utama: <strong>Inter</strong>. Fallback: <strong>Poppins</strong>.</p>
                
                <div class="border rounded-4 p-4 bg-light" style="background-color: #F8FAFC !important;">
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-secondary small d-block mb-1">Heading 1 (Inter)</span>
                        <h1 class="fw-bold text-dark mb-0">Rantai Pasok</h1>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-secondary small d-block mb-1">Heading 4 (Inter)</span>
                        <h4 class="fw-bold text-dark mb-0">Control Center Tower</h4>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-secondary small d-block mb-1">Paragraph (Poppins Fallback Demo)</span>
                        <p class="mb-0" style="font-family: 'Poppins', sans-serif;">Ini adalah visualisasi tipografi menggunakan font Poppins untuk keterbacaan yang optimal pada modul sekunder.</p>
                    </div>
                    <div>
                        <span class="text-secondary small d-block mb-1">Meta Text</span>
                        <span class="text-secondary small"><i class="bi bi-clock me-1"></i>Diperbarui secara real-time pada 12:00:00 WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons & Badges & Breadcrumb -->
    <div class="row g-4 mb-4">
        <!-- Buttons Section -->
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-hand-index-thumb text-primary me-2"></i>Tombol (Buttons)</h5>
                <p class="text-secondary small mb-3">Tombol memiliki <strong>tinggi minimal 44px</strong> untuk kenyamanan sentuh (touch targets) layar tablet & mobile.</p>
                
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <button class="btn btn-primary">Primary Button</button>
                    <button class="btn btn-success">Success Button</button>
                    <button class="btn btn-danger">Danger Button</button>
                    <button class="btn btn-warning text-white">Warning Button</button>
                    <button class="btn btn-info text-white">Info Button</button>
                </div>

                <h6 class="fw-semibold text-dark mb-2">Tombol Variasi & Ukuran</h6>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button class="btn btn-outline-primary"><i class="bi bi-plus-lg me-2"></i>Tambah Data</button>
                    <button class="btn btn-light"><i class="bi bi-arrow-clockwise me-2"></i>Segarkan</button>
                    <button class="btn btn-primary" disabled>Tombol Mati (Disabled)</button>
                </div>
            </div>
        </div>

        <!-- Badges & Alerts Section -->
        <div class="col-lg-6">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-tags text-primary me-2"></i>Label (Badges)</h5>
                <p class="text-secondary small mb-3">Label berukuran pas dengan aksen visual yang halus dan informatif.</p>
                
                <div class="d-flex flex-wrap gap-2.5 mb-4">
                    <span class="badge badge-primary">Primary</span>
                    <span class="badge badge-success">Success / Stabil</span>
                    <span class="badge badge-warning">Warning / Sedang</span>
                    <span class="badge badge-danger">Danger / Kritis</span>
                    <span class="badge badge-info">Info / Data</span>
                </div>

                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-exclamation-triangle text-primary me-2"></i>Notifikasi (Alerts)</h5>
                <div class="d-flex flex-column gap-3">
                    <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-3"></i>
                        <div>Koneksi API World Bank berhasil disinkronisasi.</div>
                    </div>
                    <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                        <i class="bi bi-exclamation-octagon-fill fs-5 me-3"></i>
                        <div>Krisis cuaca ekstrem terdeteksi di Pelabuhan Shanghai!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Layout Grid -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-card-heading text-primary me-2"></i>Modul Kartu (Cards)</h5>
                <p class="text-secondary small mb-4">Radius kelengkungan sudut <strong>16px</strong> dengan efek bayangan halus (Soft Shadow) serta transisi hover interaktif.</p>

                <div class="row g-4">
                    <!-- Card 1 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card p-4 h-100 text-center">
                            <div class="p-3 rounded-circle d-inline-block border mx-auto mb-3" style="background-color: rgba(37, 99, 235, 0.08); border-color: rgba(37, 99, 235, 0.15) !important;">
                                <i class="bi bi-globe2 fs-3 text-primary"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Cakupan Global</h6>
                            <p class="text-secondary small mb-0">Memantau risiko rantai pasok global di 195 negara secara aktif.</p>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card p-4 h-100 text-center">
                            <div class="p-3 rounded-circle d-inline-block border mx-auto mb-3" style="background-color: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.15) !important;">
                                <i class="bi bi-shield-check fs-3 text-success"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Keamanan Jalur</h6>
                            <p class="text-secondary small mb-0">Analisis stabilitas koridor pengapalan logistik hub utama.</p>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card p-4 h-100 text-center">
                            <div class="p-3 rounded-circle d-inline-block border mx-auto mb-3" style="background-color: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.15) !important;">
                                <i class="bi bi-wind fs-3 text-warning"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Ancaman Iklim</h6>
                            <p class="text-secondary small mb-0">Deteksi anomali cuaca ekstrem, angin kencang, dan badai laut.</p>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-md-6 col-lg-3">
                        <div class="card p-4 h-100 text-center">
                            <div class="p-3 rounded-circle d-inline-block border mx-auto mb-3" style="background-color: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.15) !important;">
                                <i class="bi bi-activity fs-3 text-danger"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Indeks Kerentanan</h6>
                            <p class="text-secondary small mb-0">Pelacakan fluktuasi geopolitik dan ekonomi pelabuhan utama.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inputs & Forms & Dropdowns -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-input-cursor-text text-primary me-2"></i>Form & Input & Dropdowns</h5>
                
                <div class="row g-4">
                    <!-- Text Input -->
                    <div class="col-md-4">
                        <label class="form-label small text-secondary fw-semibold">Pencarian Global</label>
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Cari pelabuhan, negara...">
                        </div>
                        <span class="text-secondary small mt-1 d-block">Tampilan focus memiliki border aksen primary.</span>
                    </div>

                    <!-- Select Input -->
                    <div class="col-md-4">
                        <label class="form-label small text-secondary fw-semibold">Pilih Wilayah Regional</label>
                        <select class="form-select">
                            <option>Semua Wilayah</option>
                            <option>Asia Tenggara</option>
                            <option>Amerika Utara</option>
                            <option>Eropa Barat</option>
                        </select>
                    </div>

                    <!-- Dropdown & Modals Interaction -->
                    <div class="col-md-4">
                        <label class="form-label small text-secondary fw-semibold">Interaksi Dropdown & Modal</label>
                        <div class="d-flex gap-2">
                            <!-- Bootstrap Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Menu Pilihan
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit Data</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-share me-2"></i>Bagikan</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Hapus</a></li>
                                </ul>
                            </div>

                            <!-- Modal Trigger -->
                            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#demoModal">
                                <i class="bi bi-window-fullscreen me-2"></i>Buka Modal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table (Responsive Card Transition) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-table text-primary me-2"></i>Tabel Data Responsif (Table to Card Layout)</h5>
                        <p class="text-secondary small mb-0">Tabel otomatis berubah menjadi tata letak **Card** di layar Handphone (Mobile First).</p>
                    </div>
                    <span class="badge badge-info px-3 py-1.5 rounded-pill text-white"><i class="bi bi-phone me-1"></i>Uji Mobile-Ready</span>
                </div>

                <div class="table-responsive-card">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Negara Hub</th>
                                <th>Pelabuhan Utama</th>
                                <th>Kondisi Cuaca</th>
                                <th>Status Operasional</th>
                                <th>Indeks Risiko</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-label="Negara Hub" class="fw-bold text-dark">🇮🇩 Indonesia</td>
                                <td data-label="Pelabuhan Utama">Tanjung Priok, Jakarta</td>
                                <td data-label="Kondisi Cuaca"><span class="badge badge-info">Hujan Ringan</span></td>
                                <td data-label="Status Operasional"><span class="badge badge-success">Stabil</span></td>
                                <td data-label="Indeks Risiko" class="fw-bold text-success">1.25 / Rendah</td>
                                <td data-label="Aksi" class="text-end"><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                            <tr>
                                <td data-label="Negara Hub" class="fw-bold text-dark">🇸🇬 Singapura</td>
                                <td data-label="Pelabuhan Utama">Port of Singapore</td>
                                <td data-label="Kondisi Cuaca"><span class="badge badge-success">Cerah</span></td>
                                <td data-label="Status Operasional"><span class="badge badge-success">Stabil</span></td>
                                <td data-label="Indeks Risiko" class="fw-bold text-success">0.95 / Rendah</td>
                                <td data-label="Aksi" class="text-end"><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                            <tr>
                                <td data-label="Negara Hub" class="fw-bold text-dark">🇺🇸 Amerika Serikat</td>
                                <td data-label="Pelabuhan Utama">Port of Los Angeles</td>
                                <td data-label="Kondisi Cuaca"><span class="badge badge-warning">Mendung</span></td>
                                <td data-label="Status Operasional"><span class="badge badge-warning">Hambatan</span></td>
                                <td data-label="Indeks Risiko" class="fw-bold text-warning">3.48 / Sedang</td>
                                <td data-label="Aksi" class="text-end"><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                            <tr>
                                <td data-label="Negara Hub" class="fw-bold text-dark">🇨🇳 China</td>
                                <td data-label="Pelabuhan Utama">Port of Shanghai</td>
                                <td data-label="Kondisi Cuaca"><span class="badge badge-danger">Badai Tropis</span></td>
                                <td data-label="Status Operasional"><span class="badge badge-danger">Tertunda</span></td>
                                <td data-label="Indeks Risiko" class="fw-bold text-danger">4.92 / Tinggi</td>
                                <td data-label="Aksi" class="text-end"><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination & Footer preview -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <span class="text-secondary small">Menampilkan 1-4 dari 4 data Hub Utama</span>
                
                <!-- Pagination -->
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

<!-- Modal Demo Component -->
<div class="modal fade" id="demoModal" tabindex="-1" aria-labelledby="demoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="demoModalLabel"><i class="bi bi-info-circle text-primary me-2"></i>Detail Status Logistik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="p-3 rounded-circle d-inline-block border mb-2" style="background-color: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.15) !important;">
                        <i class="bi bi-check2-circle fs-2 text-success"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Seluruh Jalur Koridor Aman</h6>
                    <p class="text-secondary small mb-0">Sistem memantau tidak ada hambatan cuaca atau geopolitik mayor.</p>
                </div>
                <div class="border rounded-4 p-3 bg-light" style="background-color: #F8FAFC !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary">Waktu Pemindaian</span>
                        <span class="text-dark fw-semibold">Baru Saja</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-secondary">Node yang Dipindai</span>
                        <span class="text-dark fw-semibold">412 Titik Hub</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="alert('Simulasi aksi berhasil dijalankan!')">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

@endsection
