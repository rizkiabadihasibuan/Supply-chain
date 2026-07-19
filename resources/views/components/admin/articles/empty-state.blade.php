{{-- ═══════════════════════════════════════════════════
     EMPTY STATE COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/empty-state.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => 'Belum ada artikel.',
    'description' => 'Silakan buat artikel pertama Anda.'
])

<div class="card p-5 border-0 shadow-sm rounded-4 text-center">
    <div class="d-flex flex-column align-items-center">
        <div class="bg-light rounded-circle p-4 mb-4 d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
            <i class="bi bi-file-earmark-text fs-1 text-secondary"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
        <p class="text-secondary small max-width-350 mb-4">{{ $description }}</p>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#articleCreateModal" style="min-height: 40px;">
            <i class="bi bi-plus-circle-fill me-1.5"></i> Tambah Artikel
        </button>
    </div>
</div>
