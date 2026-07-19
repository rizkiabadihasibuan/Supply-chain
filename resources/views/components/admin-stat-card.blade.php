{{-- ═══════════════════════════════════════════════════
     ADMIN STAT CARD COMPONENT – Milestone 3.15A
     resources/views/components/admin-stat-card.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => '',
    'icon' => 'people',
    'value' => '0',
    'subtitle' => '',
    'badgeText' => '',
    'badgeColor' => 'success'
])

<div class="card admin-kpi-card border-0">
    <div class="admin-kpi-header">
        <span class="admin-kpi-subtitle">{{ $title }}</span>
        <div class="admin-kpi-icon">
            <i class="bi bi-{{ $icon }}"></i>
        </div>
    </div>
    <h3 class="admin-kpi-value">{{ $value }}</h3>
    
    <div class="admin-kpi-footer justify-content-between align-items-center">
        <span class="text-secondary small">{{ $subtitle }}</span>
        @if($badgeText)
            <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} border-opacity-25" style="font-size:0.65rem;">
                {{ $badgeText }}
            </span>
        @endif
    </div>
</div>
