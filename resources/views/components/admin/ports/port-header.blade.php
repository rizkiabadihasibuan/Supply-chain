{{-- ═══════════════════════════════════════════════════
     ADMIN PORT HEADER COMPONENT
     resources/views/components/admin/ports/port-header.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => 'Port Dataset Management',
    'subtitle' => 'Kelola dataset pelabuhan World Port Index (WPI) yang digunakan dalam analisis rantai pasok global.',
])

<div class="card p-4 border-0 mb-4 shadow-sm" style="border-radius: 20px; background: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #0C4A6E 100%); color: #fff; position: relative; overflow: hidden;">
    <div style="position: absolute; right: -40px; top: -40px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(56,189,248,0.15) 0%, transparent 70%); border-radius: 50%;"></div>
    
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2" style="font-size: 0.82rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none"><i class="bi bi-house-door-fill me-1"></i>Admin</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">Ports</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 relative-index" style="z-index: 2;">
        <div>
            <h3 class="fw-bold text-white mb-1 d-flex align-items-center gap-2">
                <i class="bi bi-anchor text-info"></i> {{ $title }}
            </h3>
            <p class="text-white-50 small mb-0">{{ $subtitle }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                <i class="bi bi-geo-alt-fill me-1"></i> WPI Database Connected
            </span>
        </div>
    </div>
</div>
