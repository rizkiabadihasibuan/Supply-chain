@props([
    'title',
    'subtitle' => '',
    'showRefresh' => false,
    'refreshId' => '',
    'noBorder' => false
])

<div class="card p-4 border-0 h-100 shadow-sm transition-hover rounded-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h6 class="fw-bold text-dark mb-1">{{ $title }}</h6>
            @if($subtitle)
                <p class="text-secondary small mb-0">{{ $subtitle }}</p>
            @endif
        </div>
        @if($showRefresh)
            <button type="button" class="btn btn-link text-secondary p-0 border-0" @if($refreshId) id="{{ $refreshId }}" @endif style="background: transparent; min-height: 24px; min-width: 24px;" aria-label="Refresh {{ $title }}">
                <i class="bi bi-arrow-clockwise fs-5"></i>
            </button>
        @endif
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

