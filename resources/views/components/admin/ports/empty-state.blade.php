{{-- ═══════════════════════════════════════════════════
     EMPTY STATE COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/empty-state.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => 'Belum ada dataset pelabuhan.',
    'description' => 'Silakan lakukan import data atau tambahkan dataset baru.'
])

<div class="card p-5 border-0 shadow-sm rounded-4 text-center">
    <div class="d-flex flex-column align-items-center">
        <div class="bg-light rounded-circle p-4 mb-4 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
            <i class="bi bi-anchor fs-1 text-secondary"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
        <p class="text-secondary small max-width-350 mb-4">{{ $description }}</p>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#portCreateModal" style="min-height: 40px;">
                <i class="bi bi-plus-circle-fill me-1.5"></i> Tambah Dataset
            </button>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#portImportModal" style="min-height: 40px;">
                <i class="bi bi-upload me-1.5"></i> Import CSV
            </button>
        </div>
    </div>
</div>
