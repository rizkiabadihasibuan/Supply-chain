@props([
    'type' => 'submit',
    'id' => '',
    'onclick' => '',
    'variant' => 'primary', // primary, outline-primary, secondary
    'disabled' => false
])

<button 
    type="{{ $type }}"
    @if($id) id="{{ $id }}" @endif
    @if($onclick) onclick="{{ $onclick }}" @endif
    @if($disabled) disabled @endif
    class="btn btn-{{ $variant }} w-100 fw-semibold"
    style="min-height: 44px; border-radius: 10px !important; transition: all 0.2s;"
>
    {{ $slot }}
</button>
