@props([
    'title',
    'value',
    'description' => '',
    'icon' => 'bi-circle',
    'type' => 'primary' // primary, success, danger, warning, info
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

<div class="card p-4 h-100 border-0 kpi-card-hover d-flex flex-row align-items-center justify-content-between">
    <div>
        <span class="text-secondary small fw-medium d-block mb-1">{{ $title }}</span>
        <h3 class="fw-bold mb-1 @if($type === 'danger') text-danger @elseif($type === 'warning') text-warning @elseif($type === 'success') text-success @else text-dark @endif">{{ $value }}</h3>
        @if($description)
            <span class="text-secondary small d-block" style="font-size: 0.725rem;">{{ $description }}</span>
        @endif
    </div>
    <div class="p-3 rounded-4" style="background-color: {{ $bgOpacity }}; color: {{ $iconColor }};">
        <i class="bi {{ $icon }} fs-3"></i>
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
