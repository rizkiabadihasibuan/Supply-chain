@props([
    'title' => 'Penyaringan Data'
])

<div class="card p-4 border-0">
    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-funnel text-primary me-2"></i>{{ $title }}</h6>
    <div class="d-flex flex-column gap-3">
        {{ $slot }}
    </div>
</div>
