@props([
    'title',
    'summary',
    'country',
    'category',
    'date',
    'risk' => 'low',
    'icon' => 'bi-newspaper',
    'onclick' => ''
])

<div class="card p-3 border-0 h-100 d-flex flex-column text-start">
    <div class="row g-3 align-items-center my-auto">
        <div class="col-auto">
            <div class="rounded-3 d-flex align-items-center justify-content-center border bg-light" style="width: 70px; height: 70px; background-color: #F8FAFC !important;">
                <i class="bi {{ $icon }} @if($risk === 'high' || $risk === 'critical') text-danger @elseif($risk === 'medium') text-warning @else text-primary @endif fs-3"></i>
            </div>
        </div>
        <div class="col">
            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                @if($risk === 'high' || $risk === 'critical')
                    <span class="badge bg-danger text-white" style="font-size: 0.65rem;">Kritis</span>
                @elseif($risk === 'medium')
                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Sedang</span>
                @else
                    <span class="badge bg-success text-white" style="font-size: 0.65rem;">Rendah</span>
                @endif
                <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">{{ $category }}</span>
                <span class="text-secondary" style="font-size: 0.725rem;">{{ $date }}</span>
            </div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $title }}</h6>
            <p class="text-secondary small mb-2 text-truncate" style="max-width: 480px;">{{ $summary }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary" style="font-size: 0.75rem;"><i class="bi bi-geo-alt me-1 text-primary"></i>{{ $country }}</span>
                <button class="btn btn-light btn-sm border px-3" style="min-height: 38px;" @if($onclick) onclick="{{ $onclick }}" @endif>
                    Baca
                </button>
            </div>
        </div>
    </div>
</div>
