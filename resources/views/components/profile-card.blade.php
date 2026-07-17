@props([
    'title' => ''
])

<div class="card p-4 border-0 mb-4 h-100">
    @if($title)
        <h5 class="fw-bold text-dark mb-3">{{ $title }}</h5>
    @endif
    {{ $slot }}
</div>
