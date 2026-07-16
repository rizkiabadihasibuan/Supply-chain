@extends('layouts.app')

@section('title', 'Dashboard - SupplyChain Platform')
@section('header_title', 'Global Supply Chain Risk Intelligence Dashboard')

@section('content')
<div class="row g-4">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <h4 class="fw-bold text-primary">Selamat Datang di SupplyChain Platform</h4>
            <p class="text-muted mb-0">Platform Intelijen Risiko Rantai Pasok Global (Global Supply Chain Risk Intelligence Platform) siap dikembangkan. Seluruh infrastruktur dasar arsitektur enterprise telah berhasil dipasang.</p>
        </div>
    </div>

    <!-- Widgets Placer -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-globe2 fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Negara Terpantau</h6>
                        <h3 class="fw-bold mb-0">0</h3>
                    </div>
                </div>
                <p class="text-muted small mb-0">Jumlah negara dalam monitoring risiko rantai pasok.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-anchor fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Pelabuhan Utama</h6>
                        <h3 class="fw-bold mb-0">0</h3>
                    </div>
                </div>
                <p class="text-muted small mb-0">Pelabuhan strategis yang masuk dalam pemantauan.</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 bg-white rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-subtle text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Indeks Risiko Rata-rata</h6>
                        <h3 class="fw-bold mb-0">-</h3>
                    </div>
                </div>
                <p class="text-muted small mb-0">Skor rata-rata risiko rantai pasok global.</p>
            </div>
        </div>
    </div>

    <!-- Alert placeholder for AJAX configuration -->
    <div class="col-12 mt-4">
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4 rounded-3" role="alert">
            <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
            <div>
                <h5 class="alert-heading fw-bold mb-1">Status AJAX Terkonfigurasi</h5>
                <p class="mb-0 small">Halaman ini telah dilengkapi dengan wrapper AJAX (`SupplyChainAPI`). Pada tahap berikutnya, data dari backend Laravel akan dipanggil secara asynchronous untuk mengisi panel dashboard.</p>
            </div>
        </div>
    </div>
</div>
@endsection
