@props([
    'label',
    'type' => 'text',
    'id',
    'name',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'state' => 'normal', // normal, success, error, disabled
    'ariaLabel' => ''
])

<div class="mb-3.5 position-relative">
    <label for="{{ $id }}" class="form-label small fw-semibold text-secondary mb-1.5">{{ $label }}</label>
    <div class="position-relative">
        <input 
            type="{{ $type }}" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}" 
            value="{{ $value }}"
            @if($required) required @endif
            @if($state === 'disabled') disabled @endif
            class="form-control w-100 pe-5 @if($state === 'error') is-invalid @elseif($state === 'success') is-valid @endif"
            style="min-height: 44px; border-radius: 10px !important;"
            @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        >
        {{ $slot }}
    </div>
</div>
