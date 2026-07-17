@props([
    'title',
    'subtitle' => ''
])

<div class="card p-5 border-0 shadow-lg text-center" style="max-width: 440px; width: 100%; border-radius: var(--radius-custom) !important; background-color: #FFFFFF;">
    <div class="mb-4 d-flex justify-content-center">
        <x-page-logo />
    </div>
    
    <h3 class="fw-bold text-dark mb-1.5">{{ $title }}</h3>
    @if($subtitle)
        <p class="text-secondary small mb-4.5">{{ $subtitle }}</p>
    @endif
    
    {{ $slot }}
</div>
