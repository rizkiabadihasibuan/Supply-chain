@props([
    'title',
    'subtitle' => '',
    'showRefresh' => true,
    'refreshId' => '',
    'noBorder' => false,
    'badgeText' => '',
    'badgeType' => 'warning'
])

<div class="card p-4 border-0 h-100 shadow-sm transition-hover rounded-4">
    <div class="card-header border-0 bg-transparent p-0 d-flex justify-content-between align-items-start mb-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold text-dark mb-0">{{ $title }}</h6>
                @if($badgeText)
                    <span class="badge bg-{{ $badgeType }} bg-opacity-10 text-{{ $badgeType }} border border-{{ $badgeType }} border-opacity-25 px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">
                        {{ $badgeText }}
                    </span>
                @endif
            </div>
            @if($subtitle)
                <p class="text-secondary small mb-0 mt-0.5">{{ $subtitle }}</p>
            @endif
        </div>
        
        <div class="d-flex align-items-center gap-1">
            <!-- Refresh Icon -->
            @if($showRefresh)
                <button type="button" class="btn btn-link text-secondary p-1 border-0 rounded-circle hover-bg-light" @if($refreshId) id="{{ $refreshId }}" @endif style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; min-height: unset; background: transparent;" aria-label="Refresh {{ $title }}">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            @endif
            
            <!-- Expand Button (Placeholder) -->
            <button type="button" class="btn btn-link text-secondary p-1 border-0 rounded-circle hover-bg-light" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; min-height: unset; background: transparent;" onclick="alert('Simulasi memperluas grafik (Expand)...')" aria-label="Expand {{ $title }}">
                <i class="bi bi-arrows-angle-expand" style="font-size: 0.85rem;"></i>
            </button>
            
            <!-- Action Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-secondary p-1 border-0 rounded-circle hover-bg-light" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; min-height: unset; background: transparent;" aria-label="Opsi grafik">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                    <li><button class="dropdown-item small" type="button" onclick="alert('Simulasi mengubah jenis bagan...')"><i class="bi bi-palette me-2"></i>Ubah Tampilan</button></li>
                    <li><button class="dropdown-item small" type="button" onclick="alert('Simulasi mengunduh gambar bagan...')"><i class="bi bi-image me-2"></i>Unduh PNG</button></li>
                </ul>
            </div>
        </div>
    </div>
    
    @if($noBorder)
        <div class="position-relative w-100" style="height: 220px;">
            {{ $slot }}
        </div>
    @else
        <div class="border rounded-4 d-flex align-items-center justify-content-center overflow-hidden bg-light position-relative w-100" style="height: 200px; background-color: #FAFCFF !important;">
            {{ $slot }}
        </div>
    @endif
</div>
