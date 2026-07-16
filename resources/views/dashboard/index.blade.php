@extends('layouts.app')

@section('title', 'Sistem Desain - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0">

    <!-- Top Breadcrumb Showcase -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-white border p-3 rounded-4" style="box-shadow: 0 4px 15px rgba(18, 52, 88, 0.02);">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none" style="color: var(--primary);"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-secondary">Administrasi</a></li>
                    <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Sistem Desain Kustom</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Showcase -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-exclamation-triangle-fill text-primary me-2"></i>Komponen Alert (Pemberitahuan)</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="alert alert-success d-flex align-items-center mb-0" role="alert">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <div>
                                <strong>Sukses!</strong> Sinkronisasi API berhasil dijalankan.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div>
                                <strong>Peringatan!</strong> Kecepatan angin di Pelabuhan Tokyo meningkat.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-danger d-flex align-items-center mb-0" role="alert">
                            <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                            <div>
                                <strong>Bahaya!</strong> Jalur pelayaran Laut Merah terganggu.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Color Palette & Typography -->
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-palette-fill text-primary me-2"></i>Palet Warna Modern Enterprise</h5>
                
                <div class="row g-2 mb-4">
                    <div class="col-6 col-sm-4">
                        <div class="p-3 rounded-3 text-center border" style="background-color: #0EA5E9; color: #fff;">
                            <div class="fw-bold small">Primary</div>
                            <code class="small text-white">#0EA5E9</code>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 rounded-3 text-center border" style="background-color: #123458; color: #fff;">
                            <div class="fw-bold small">Sidebar</div>
                            <code class="small text-white">#123458</code>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 rounded-3 text-center border" style="background-color: #F7F9FC; color: #1E293B;">
                            <div class="fw-bold small">Background</div>
                            <code class="small text-muted">#F7F9FC</code>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 rounded-3 text-center border" style="background-color: #FFFFFF; color: #1E293B;">
                            <div class="fw-bold small">Card</div>
                            <code class="small text-muted">#FFFFFF</code>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 rounded-3 text-center border" style="background-color: #22C55E; color: #fff;">
                            <div class="fw-bold small">Success</div>
                            <code class="small text-white">#22C55E</code>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="p-3 rounded-3 text-center border" style="background-color: #F59E0B; color: #fff;">
                            <div class="fw-bold small">Warning</div>
                            <code class="small text-white">#F59E0B</code>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-type text-primary me-2"></i>Tipografi (Inter)</h5>
                <div class="d-flex flex-column gap-2">
                    <div>
                        <span class="badge bg-light text-secondary me-2">H1</span>
                        <h1 class="d-inline fw-bold text-dark fs-3">Command Center Risk Intelligence</h1>
                    </div>
                    <div>
                        <span class="badge bg-light text-secondary me-2">H2</span>
                        <h2 class="d-inline fw-semibold text-dark fs-4">Pemantauan Jalur Logistik</h2>
                    </div>
                    <div>
                        <span class="badge bg-light text-secondary me-2">Body</span>
                        <span class="text-secondary small">Teks deskripsi korporasi dengan perataan dan keterbacaan yang dioptimalkan untuk enterprise.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons, Badges, and Dropdowns -->
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-hand-index-thumb-fill text-primary me-2"></i>Tombol, Lencana & Dropdown</h5>
                
                <div class="mb-4">
                    <h6 class="text-secondary small mb-2 fw-semibold">Gaya Tombol (Button):</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary">Primary Button</button>
                        <button class="btn btn-secondary border-0" style="background-color: #64748B;">Secondary</button>
                        <button class="btn btn-success border-0">Success</button>
                        <button class="btn btn-danger border-0">Danger</button>
                        <button class="btn btn-outline-primary">Outline Primary</button>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-secondary small mb-2 fw-semibold">Lencana Status (Badge):</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge badge-success">Normal</span>
                        <span class="badge badge-warning">Siaga</span>
                        <span class="badge badge-danger">Bahaya</span>
                        <span class="badge badge-info">Informasi</span>
                    </div>
                </div>

                <div>
                    <h6 class="text-secondary small mb-2 fw-semibold">Komponen Dropdown:</h6>
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pilih Opsi Laporan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i>Ekspor ke PDF</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Ekspor ke Excel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>Hapus Laporan</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Forms, Modal, & Dialogs -->
    <div class="row g-4 mb-4">
        <!-- Form Inputs Showcase -->
        <div class="col-md-6">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Form & Input Kontrol</h5>
                <form onsubmit="event.preventDefault();">
                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-semibold">Alamat Email Korporat</label>
                        <input type="email" class="form-control" placeholder="nama@perusahaan.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-semibold">Zona Pengawasan Rantai Pasok</label>
                        <select class="form-select">
                            <option>Asia Tenggara (ASEAN)</option>
                            <option>Amerika Utara (US-Canada)</option>
                            <option>Uni Eropa (EU)</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="switchSync" checked>
                        <label class="form-check-label small text-secondary" for="switchSync">Sinkronisasi Latar Belakang Otomatis</label>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Dialog Showcase -->
        <div class="col-md-6">
            <div class="card p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-window-stack text-primary me-2"></i>Komponen Modal Dialog</h5>
                    <p class="text-secondary small">Klik tombol di bawah untuk memicu tampilan kotak dialog modal dengan desain korporat rounded 16px.</p>
                </div>
                <div class="py-3 text-center bg-light rounded-4 border border-dashed p-4">
                    <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#showcaseModal">
                        <i class="bi bi-play-circle-fill me-2"></i>Buka Modal Dialog
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables and Pagination -->
    <div class="row g-4">
        <div class="col-12">
            <div class="card p-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-table text-primary me-2"></i>Data Tabel & Halaman (Table & Pagination)</h5>
                
                <div class="table-responsive mb-3">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode ISO</th>
                                <th>Negara Rantai Pasok</th>
                                <th>Koordinat Utama</th>
                                <th>Koneksi API</th>
                                <th>Status Risiko</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-dark">ID</td>
                                <td>Indonesia</td>
                                <td>0.7893° N, 113.9213° E</td>
                                <td><span class="badge badge-success">Terhubung</span></td>
                                <td><span class="badge badge-success">Rendah - 0.12</span></td>
                                <td><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">SG</td>
                                <td>Singapura</td>
                                <td>1.3521° N, 103.8198° E</td>
                                <td><span class="badge badge-success">Terhubung</span></td>
                                <td><span class="badge badge-success">Rendah - 0.08</span></td>
                                <td><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">JP</td>
                                <td>Jepang</td>
                                <td>36.2048° N, 138.2529° E</td>
                                <td><span class="badge badge-success">Terhubung</span></td>
                                <td><span class="badge badge-warning">Sedang - 2.80</span></td>
                                <td><button class="btn btn-light btn-sm border px-3">Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Showcase -->
                <div class="d-flex justify-content-between align-items-center flex-wrap pt-2 border-top">
                    <span class="text-secondary small">Menampilkan 1-3 dari 195 negara terpantau</span>
                    <nav aria-label="Navigasi Halaman Data">
                        <ul class="pagination mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true" style="border-radius: 8px 0 0 8px;">Sebelumnya</a>
                            </li>
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" style="border-radius: 0 8px 8px 0;">Berikutnya</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Showcase Bootstrap Modal -->
<div class="modal fade" id="showcaseModal" tabindex="-1" aria-labelledby="showcaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="showcaseModalLabel"><i class="bi bi-info-circle-fill text-primary me-2"></i>Konfirmasi Sinkronisasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary mb-0">Apakah Anda yakin ingin memicu pembaruan data real-time pada seluruh API integrasi logistik luar negeri?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Ya, Sinkronkan</button>
            </div>
        </div>
    </div>
</div>

@endsection
