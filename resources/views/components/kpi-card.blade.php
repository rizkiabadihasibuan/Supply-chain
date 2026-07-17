@props([
    'title',
    'value',
    'description' => '',
    'icon' => 'bi-circle',
    'type' => 'primary', // primary, success, danger, warning, info
    'trend' => '',
    'trendUp' => true,
    'statusBadge' => '',
    'statusBadgeType' => 'success'
])

@php
    $bgOpacity = 'rgba(37, 99, 235, 0.08)';
    $iconColor = 'var(--primary)';
    
    if ($type === 'success') {
        $bgOpacity = 'rgba(34, 197, 94, 0.08)';
        $iconColor = 'var(--success)';
    } elseif ($type === 'danger') {
        $bgOpacity = 'rgba(239, 68, 68, 0.08)';
        $iconColor = 'var(--danger)';
    } elseif ($type === 'warning') {
        $bgOpacity = 'rgba(245, 158, 11, 0.08)';
        $iconColor = 'var(--warning)';
    } elseif ($type === 'info') {
        $bgOpacity = 'rgba(6, 182, 212, 0.08)';
        $iconColor = 'var(--info)';
    }
@endphp

<div class="card p-4 h-100 border-0 kpi-card-hover d-flex flex-column justify-content-between shadow-sm transition-hover rounded-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="p-3 rounded-4" style="background-color: {{ $bgOpacity }}; color: {{ $iconColor }};">
            <i class="bi {{ $icon }} fs-4"></i>
        </div>
        @if($statusBadge)
            <span class="badge bg-{{ $statusBadgeType }} bg-opacity-10 text-{{ $statusBadgeType }} border border-{{ $statusBadgeType }} border-opacity-25 px-2.5 py-1 rounded-pill small">
                {{ $statusBadge }}
            </span>
        @endif
    </div>
    
    <div>
        <span class="text-secondary small fw-medium d-block mb-1">{{ $title }}</span>
        <h3 class="fw-bold mb-1.5 @if($type === 'danger') text-danger @elseif($type === 'warning') text-warning @elseif($type === 'success') text-success @else text-dark @endif">{{ $value }}</h3>
        
        <div class="d-flex align-items-center gap-2 mt-1 flex-wrap">
            @if($trend)
                <span class="small fw-semibold {{ $trendUp ? 'text-success' : 'text-danger' }}">
                    <i class="bi {{ $trendUp ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }} me-0.5"></i>{{ $trend }}
                </span>
            @endif
            @if($description)
                <span class="text-secondary small" style="font-size: 0.725rem;">{{ $description }}</span>
            @endif
        </div>
    </div>
</div>

<style>
    .kpi-card-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .kpi-card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(18, 52, 88, 0.08) !important;
    }
</style>

