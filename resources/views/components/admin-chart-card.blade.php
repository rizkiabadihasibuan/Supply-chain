{{-- ═══════════════════════════════════════════════════
     ADMIN CHART CARD COMPONENT – Milestone 3.15A
     resources/views/components/admin-chart-card.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => 'Statistik',
    'chartId' => '',
    'description' => ''
])

<div class="card p-4 border-0">
    <h6 class="fw-bold text-dark mb-1">{{ $title }}</h6>
    @if($description)
        <p class="text-secondary small mb-3">{{ $description }}</p>
    @else
        <div class="mb-3"></div>
    @endif
    
    <div class="admin-chart-wrapper">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>
