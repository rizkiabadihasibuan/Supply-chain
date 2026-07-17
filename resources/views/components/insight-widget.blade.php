@props([
    'title',
    'icon' => ''
])

<div class="card p-4 border-0">
    <h5 class="fw-bold text-dark mb-3">
        @if($icon)
            <i class="bi {{ $icon }} text-primary me-2"></i>
        @endif
        {{ $title }}
    </h5>
    {{ $slot }}
</div>
