{{-- ═══════════════════════════════════════════════════
     STATISTICS CARD COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/statistics-card.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => '',
    'value' => '0',
    'icon' => 'file-earmark-text',
    'color' => 'primary'
])

<div class="card articles-stat-card border-0 p-4 shadow-sm" tabindex="0">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-start">
            <span class="text-secondary small d-block mb-1.5 fw-semibold">{{ $title }}</span>
            <h3 class="fw-bold text-dark mb-0">{{ $value }}</h3>
        </div>
        <div class="rounded-3 p-2 bg-{{ $color }} bg-opacity-10 text-{{ $color }} fs-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-{{ $icon }}" aria-hidden="true"></i>
        </div>
    </div>
</div>
