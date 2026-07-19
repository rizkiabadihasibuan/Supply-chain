{{-- ═══════════════════════════════════════════════════
     QUICK ACTION CARD COMPONENT – Milestone 3.15A
     resources/views/components/quick-action-card.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card p-4 border-0">
    <h6 class="fw-bold text-dark mb-3">Aksi Cepat Admin</h6>
    <p class="text-secondary small mb-4">Pintas eksekusi tugas administratif penting secara instan.</p>
    
    <div class="d-flex flex-column gap-2">
        <button class="btn btn-outline-primary w-100 text-start justify-content-start gap-2" style="min-height: 44px;" onclick="AdminDashboard.showToast('Membuka form Tambah User (Simulasi)...')">
            <i class="bi bi-person-plus-fill"></i> Tambah User Baru
        </button>
        <button class="btn btn-outline-primary w-100 text-start justify-content-start gap-2" style="min-height: 44px;" onclick="AdminDashboard.showToast('Membuka form Tambah Artikel (Simulasi)...')">
            <i class="bi bi-file-earmark-medical-fill"></i> Tambah Artikel Baru
        </button>
        <button class="btn btn-outline-primary w-100 text-start justify-content-start gap-2" style="min-height: 44px;" onclick="AdminDashboard.showToast('Membuka form Tambah Dataset Pelabuhan (Simulasi)...')">
            <i class="bi bi-anchor"></i> Tambah Dataset Pelabuhan
        </button>
        <a href="{{ route('monitoring') }}" class="btn btn-outline-primary w-100 text-start justify-content-start gap-2" style="min-height: 44px; text-decoration: none;">
            <i class="bi bi-bookmark-star-fill"></i> Lihat Monitoring Center
        </a>
        <button class="btn btn-primary w-100 text-start justify-content-start gap-2" style="min-height: 44px;" onclick="AdminDashboard.showToast('Menghasilkan laporan PDF performa sistem (Simulasi)...')">
            <i class="bi bi-file-earmark-pdf-fill"></i> Generate Report Sistem
        </button>
    </div>
</div>
