{{-- ═══════════════════════════════════════════════════
     PREVIEW CARD COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/preview-card.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card shadow-sm border border-light-subtle rounded-3 overflow-hidden flex-grow-1 d-flex flex-column bg-white text-start">
    <img id="preview-card-img" src="" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Pratinjau Gambar Artikel">
    <div class="card-body p-3 d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary bg-opacity-10 text-primary small fw-bold" id="preview-card-category" style="font-size: 0.65rem;">Kategori</span>
                <small class="text-secondary" id="preview-card-date" style="font-size: 0.65rem;">Tanggal</small>
            </div>
            <h6 class="fw-bold text-dark mb-1.5" id="preview-card-title" style="font-size: 0.8rem; line-height: 1.3;">Judul Artikel</h6>
            <p class="text-secondary small mb-2" id="preview-card-summary" style="font-size: 0.725rem; line-height: 1.4;">Ringkasan artikel...</p>
        </div>
        <div class="d-flex align-items-center gap-1.5 pt-2 border-top mt-2">
            <i class="bi bi-person-circle text-secondary" style="font-size: 0.75rem;"></i>
            <span class="text-secondary fw-semibold" style="font-size:0.7rem;" id="preview-card-author">Penulis</span>
        </div>
    </div>
</div>
