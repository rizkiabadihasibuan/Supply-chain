{{-- ============================================================
     ERROR STATE
     resources/views/dashboard/visualization/components/error-state.blade.php
     ============================================================ --}}
@props([
    'title'       => 'Data gagal dimuat.',
    'description' => 'Koneksi ke server data terputus. Silakan coba beberapa saat lagi.',
])

<div id="errorState" class="py-5 text-center viz-fade-in" style="display:none;">
    <div class="d-flex justify-content-center mb-4">
        <div class="p-4 rounded-circle" style="background:rgba(220,38,38,0.08); display:inline-flex;">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3.5rem;"></i>
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
    <p class="text-secondary mb-4" style="max-width: 360px; margin: 0 auto;">{{ $description }}</p>

    <div class="card border border-danger border-opacity-25 bg-danger bg-opacity-10 rounded-3 py-3 px-4 d-inline-block mb-4 text-start">
        <p class="text-danger small mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Kemungkinan penyebab: timeout jaringan, API tidak merespons, atau data tidak tersedia untuk filter ini.
        </p>
    </div>

    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <button type="button"
                id="btnRetry"
                class="btn btn-danger px-4"
                style="min-height:40px; border-radius:10px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi
        </button>
        <button type="button"
                class="btn btn-outline-secondary px-4"
                onclick="FilterManager.reset()"
                style="min-height:40px; border-radius:10px;">
            <i class="bi bi-funnel me-2"></i>Reset Filter
        </button>
    </div>
</div>
