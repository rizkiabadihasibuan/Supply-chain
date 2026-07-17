@props([
    'title',
    'subtitle' => ''
])

<div class="card p-4 border-0 h-100">
    <h5 class="fw-bold text-dark mb-1">{{ $title }}</h5>
    @if($subtitle)
        <p class="text-secondary small mb-3">{{ $subtitle }}</p>
    @endif
    <div class="border rounded-4 d-flex align-items-center justify-content-center overflow-hidden bg-light position-relative" style="height: 200px; background-color: #FAFCFF !important;">
        {{ $slot }}
    </div>
</div>
