{{-- ============================================================
     EMPTY STATE
     resources/views/dashboard/visualization/components/empty-state.blade.php
     ============================================================ --}}
@props([
    'title'       => 'Belum ada data visualisasi.',
    'description' => 'Silakan pilih negara atau ubah filter untuk mulai melihat analisis data ekonomi global.',
])

<div id="emptyState" class="py-5 text-center viz-fade-in" style="display:none;">
    <div class="d-flex justify-content-center mb-4">
        {{-- Modern SVG illustration --}}
        <svg width="180" height="140" viewBox="0 0 180 140" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="90" width="160" height="10" rx="5" fill="#E2E8F0"/>
            <rect x="30" y="50" width="24" height="40" rx="4" fill="#BFDBFE"/>
            <rect x="64" y="35" width="24" height="55" rx="4" fill="#93C5FD"/>
            <rect x="98" y="58" width="24" height="32" rx="4" fill="#BFDBFE"/>
            <rect x="132" y="20" width="24" height="70" rx="4" fill="#2563EB" opacity="0.6"/>
            <circle cx="90" cy="22" r="18" fill="#FEF3C7"/>
            <text x="90" y="27" text-anchor="middle" font-size="16" fill="#D97706">?</text>
        </svg>
    </div>

    <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
    <p class="text-secondary mb-4" style="max-width:360px; margin: 0 auto;">{{ $description }}</p>

    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <button type="button"
                class="btn btn-primary px-4"
                id="btnRetry"
                style="min-height:40px; border-radius:10px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Data
        </button>
        <button type="button"
                class="btn btn-outline-primary px-4"
                onclick="FilterManager.reset()"
                style="min-height:40px; border-radius:10px;">
            <i class="bi bi-funnel me-2"></i>Reset Filter
        </button>
    </div>
</div>
