@props([
    'id',
    'name',
    'category', // country, port, currency, weather, news, risk
    'status' => 'monitoring', // monitoring, paused, resolved
    'risk' => 'low', // low, medium, high, critical
    'date' => 'Baru saja',
    'isPinned' => false,
    'icon' => 'bi-circle'
])

@php
    $catLabel = 'Kategori';
    $catBadgeClass = 'badge-primary';
    
    if ($category === 'country') {
        $catLabel = 'Negara';
        $catBadgeClass = 'badge-primary';
    } elseif ($category === 'port') {
        $catLabel = 'Pelabuhan';
        $catBadgeClass = 'badge-info';
    } elseif ($category === 'currency') {
        $catLabel = 'Mata Uang';
        $catBadgeClass = 'badge-warning';
    } elseif ($category === 'weather') {
        $catLabel = 'Cuaca';
        $catBadgeClass = 'badge-info';
    } elseif ($category === 'news') {
        $catLabel = 'Berita';
        $catBadgeClass = 'badge-primary';
    } elseif ($category === 'risk') {
        $catLabel = 'Analisis Risiko';
        $catBadgeClass = 'badge-danger';
    }
@endphp

<div class="card p-4 border-0 mb-3 monitor-card-item h-100" id="monitor-card-{{ $id }}" data-id="{{ $id }}" data-name="{{ $name }}" data-category="{{ $category }}" data-status="{{ $status }}" data-risk="{{ $risk }}" data-pinned="{{ $isPinned ? 'true' : 'false' }}">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2.5 rounded-3 bg-light border d-flex align-items-center justify-content-center text-primary" style="width: 46px; height: 46px; background-color: #F8FAFC !important;">
                <i class="bi {{ $icon }} fs-4"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0.5" style="font-size: 1.05rem;">{{ $name }}</h5>
                <span class="badge {{ $catBadgeClass }}" style="font-size: 0.65rem;">{{ $catLabel }}</span>
            </div>
        </div>

        <button class="btn btn-link p-1 text-secondary pin-toggle-btn" style="min-height: 44px;" onclick="togglePin('{{ $id }}')" aria-label="Sematkan Item">
            <i class="bi {{ $isPinned ? 'bi-star-fill text-warning' : 'bi-star' }} fs-5"></i>
        </button>
    </div>

    <div class="row g-3 mb-4.5" style="font-size: 0.8rem;">
        <div class="col-6">
            <span class="text-secondary d-block">Status Pengawasan</span>
            <span class="status-badge-text fw-semibold @if($status === 'monitoring') text-success @elseif($status === 'paused') text-warning @else text-secondary @endif">
                {{ $status === 'monitoring' ? '● Aktif Memantau' : ($status === 'paused' ? '⏸️ Ditangguhkan' : '✓ Selesai') }}
            </span>
        </div>
        <div class="col-6">
            <span class="text-secondary d-block">Tingkat Kerawanan</span>
            @if($risk === 'critical')
                <x-badge type="danger" text="Critical" />
            @elseif($risk === 'high')
                <span class="badge" style="background-color: #F97316; color: white;">High</span>
            @elseif($risk === 'medium')
                <x-badge type="warning" text="Medium" />
            @else
                <x-badge type="success" text="Low" />
            @endif
        </div>
    </div>

    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
        <span class="text-secondary small" style="font-size: 0.725rem;"><i class="bi bi-clock me-1 text-primary"></i>{{ $date }}</span>
        
        <div class="d-flex gap-2">
            <button class="btn btn-light btn-sm border px-3" style="min-height: 38px;" onclick="togglePause('{{ $id }}')" aria-label="Jeda Pantauan">
                <i class="bi {{ $status === 'paused' ? 'bi-play-fill' : 'bi-pause-fill' }}"></i>
            </button>
            <button class="btn btn-danger btn-sm border-0 px-3" style="min-height: 38px;" onclick="deleteMonitorItem('{{ $id }}')" aria-label="Hapus Pantauan">
                <i class="bi bi-trash-fill text-white"></i>
            </button>
        </div>
    </div>
</div>

<style>
    .monitor-card-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .monitor-card-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(18, 52, 88, 0.07) !important;
    }
</style>
