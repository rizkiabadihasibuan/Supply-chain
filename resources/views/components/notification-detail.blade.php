@props([
    'title' => 'Pilih Notifikasi',
    'category' => 'System',
    'priority' => 'Low',
    'date' => 'Live',
    'description' => 'Silakan pilih notifikasi di daftar kiri untuk melihat rincian logistik maritim secara lengkap.',
    'impact' => 'Normal',
    'recommendation' => 'Tidak ada tindakan pencegahan yang diperlukan.',
    'module' => 'Dashboard'
])

<div class="card p-4 border-0 h-100 text-start">
    <div class="border-bottom pb-3 mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
            <span class="badge badge-info" id="detail-category-badge">{{ $category }}</span>
            <span class="badge badge-danger" id="detail-priority-badge">{{ $priority }}</span>
            <span class="text-secondary small ms-auto" id="detail-date">{{ $date }}</span>
        </div>
        <h4 class="fw-bold text-dark mb-1" id="detail-title" style="line-height: 1.4;">{{ $title }}</h4>
    </div>

    <div class="d-flex flex-column gap-3.5">
        <div>
            <span class="text-secondary small fw-medium d-block mb-1">Rincian Peristiwa</span>
            <p class="text-dark small mb-0" id="detail-description" style="line-height: 1.6;">{{ $description }}</p>
        </div>

        <div>
            <span class="text-secondary small fw-medium d-block mb-1">Dampak Rantai Pasok</span>
            <div class="p-3 border rounded-3 bg-light" style="background-color: #FAFCFF !important;">
                <p class="text-secondary small mb-0" id="detail-impact">{{ $impact }}</p>
            </div>
        </div>

        <div>
            <span class="text-secondary small fw-medium d-block mb-1">Rekomendasi Mitigasi</span>
            <div class="p-3 border border-warning-subtle rounded-3 bg-warning bg-opacity-10 text-warning-emphasis">
                <p class="small mb-0" id="detail-recommendation">{{ $recommendation }}</p>
            </div>
        </div>

        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
            <div>
                <span class="text-secondary small d-block">Modul Terkait:</span>
                <span class="fw-bold text-primary small" id="detail-module">{{ $module }}</span>
            </div>
            
            <button class="btn btn-primary px-4" style="min-height: 44px;" id="detail-action-btn" disabled>
                Buka Modul
            </button>
        </div>
    </div>
</div>
