{{-- ═══════════════════════════════════════════════════
     ERROR STATE COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/error-state.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'message' => 'Terjadi kesalahan saat memuat dataset pelabuhan.'
])

<div class="alert alert-danger d-flex align-items-center justify-content-between p-4" role="alert">
    <div class="d-flex align-items-center gap-2 text-start">
        <i class="bi bi-exclamation-triangle-fill fs-4 text-danger"></i>
        <div>
            <h6 class="alert-heading fw-bold mb-0.5">{{ $message }}</h6>
            <span class="small">Koneksi sinkronisasi dataset terputus. Silakan coba kembali memuat ulang server.</span>
        </div>
    </div>
    <button class="btn btn-sm btn-danger border-white border" onclick="PortsCore.retryFromError()" aria-label="Coba memuat data kembali">Coba Lagi</button>
</div>
