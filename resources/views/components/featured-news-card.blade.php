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

<div class="card p-4 border-0 h-100 d-flex flex-column text-start">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <span class="badge badge-info text-uppercase"><i class="bi bi-star-fill me-1.5 text-warning"></i>Berita Utama</span>
        @if($risk === 'high' || $risk === 'critical')
            <span class="badge badge-danger">Risiko Tinggi</span>
        @elseif($risk === 'medium')
            <span class="badge badge-warning">Risiko Sedang</span>
        @else
            <span class="badge badge-success">Risiko Rendah</span>
        @endif
    </div>

    <!-- Big vector placeholder thumbnail instead of image -->
    <div class="border rounded-4 mb-3 d-flex align-items-center justify-content-center overflow-hidden bg-light" style="height: 200px; background-color: #F8FAFC !important;">
        <div class="text-center text-secondary">
            <i class="bi {{ $icon }} fs-1 text-primary mb-2 d-block"></i>
            <span class="small fw-semibold">Visualisasi Rantai Pasok Global</span>
        </div>
    </div>

    <h4 class="fw-bold text-dark mb-2">{{ $title }}</h4>
    <p class="text-secondary small mb-4" style="line-height: 1.6;">{{ $summary }}</p>

    <div class="mt-auto pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2" style="font-size: 0.8rem;">
            <span class="text-dark fw-semibold"><i class="bi bi-geo-alt me-1 text-primary"></i>{{ $country }}</span>
            <span class="text-muted">|</span>
            <span class="text-secondary fw-medium"><i class="bi bi-tags me-1 text-primary"></i>{{ $category }}</span>
            <span class="text-muted">|</span>
            <span class="text-secondary">{{ $date }}</span>
        </div>
        <button class="btn btn-primary px-4" style="min-height: 44px;" @if($onclick) onclick="{{ $onclick }}" @endif>
            Baca Selengkapnya
        </button>
    </div>
</div>
