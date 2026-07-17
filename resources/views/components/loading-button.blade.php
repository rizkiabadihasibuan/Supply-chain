@props([
    'id' => '',
    'text' => 'Kirim',
    'loadingText' => 'Memproses...',
    'variant' => 'primary'
])

<button 
    type="submit" 
    @if($id) id="{{ $id }}" @endif
    class="btn btn-{{ $variant }} w-100 fw-semibold d-flex align-items-center justify-content-center gap-2"
    style="min-height: 44px; border-radius: 10px !important; transition: all 0.2s;"
>
    <span class="spinner-border spinner-border-sm spinner-loading-icon" role="status" aria-hidden="true" style="display: none;"></span>
    <span class="button-label-text">{{ $text }}</span>
</button>
