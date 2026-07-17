@props([
    'title' => 'Stasiun data tidak terhubung.',
    'description' => 'Gagal memuat logistik. Silakan periksa koneksi server.',
    'buttonText' => 'Coba Lagi',
    'onclick' => ''
])

<div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
    <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-3">
        <i class="bi bi-cloud-slash fs-2"></i>
    </div>
    <h5 class="fw-bold text-dark mb-1">{{ $title }}</h5>
    <p class="text-secondary small mb-3.5" style="max-width: 340px;">{{ $description }}</p>
    @if($buttonText && $onclick)
        <button class="btn btn-danger px-4" style="min-height: 44px;" onclick="{{ $onclick }}">{{ $buttonText }}</button>
    @endif
</div>
