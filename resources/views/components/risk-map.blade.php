@props([
    'title' => 'Peta Risiko Koridor Logistik'
])

<div class="card p-4 border-0">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
        <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-globe text-primary me-2"></i>{{ $title }}</h5>
            <p class="text-secondary small mb-0">{{ $slot }}</p>
        </div>
        @if(isset($controls))
            <div class="d-flex gap-2">
                {{ $controls }}
            </div>
        @endif
    </div>
    
    <div class="position-relative border rounded-4 overflow-hidden d-flex align-items-center justify-content-center" style="height: 400px; background-color: #FAFCFF !important;">
        @if(isset($mapContent))
            {{ $mapContent }}
        @endif
    </div>
</div>
