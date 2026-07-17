@props([
    'title' => 'Data tidak ditemukan.',
    'description' => 'Silakan sesuaikan kriteria penyaringan filter Anda.',
    'buttonText' => 'Reset Filter',
    'onclick' => ''
])

<div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
    <svg viewBox="0 0 200 120" style="width: 160px; height: 100px;" class="mb-3.5">
        <rect x="30" y="30" width="140" height="70" rx="12" fill="none" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
        <path d="M100,45 L100,75 M85,60 L115,60" stroke="#CBD5E1" stroke-width="2" stroke-linecap="round" />
    </svg>
    <h5 class="fw-bold text-dark mb-1">{{ $title }}</h5>
    <p class="text-secondary small mb-3.5">{{ $description }}</p>
    @if($buttonText && $onclick)
        <button class="btn btn-primary px-4" style="min-height: 44px;" onclick="{{ $onclick }}">{{ $buttonText }}</button>
    @endif
</div>
