@props([
    'title',
    'icon' => '',
    'badgeText' => '',
    'badgeType' => 'primary'
])

<div class="card p-4 border-0 mb-4 h-100 shadow-sm transition-hover rounded-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            @if($icon)
                <i class="bi {{ $icon }} text-primary fs-5"></i>
            @endif
            {{ $title }}
        </h6>
        @if($badgeText)
            <span class="badge bg-{{ $badgeType }} bg-opacity-10 text-{{ $badgeType }} border border-{{ $badgeType }} border-opacity-25 px-2.5 py-1 rounded-pill small">
                {{ $badgeText }}
            </span>
        @endif
    </div>
    <div class="card-body p-0">
        {{ $slot }}
    </div>
</div>
