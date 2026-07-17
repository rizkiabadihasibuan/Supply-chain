@props([
    'id',
    'label',
    'options' => [],
    'selected' => ''
])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label small fw-semibold text-secondary mb-1.5">{{ $label }}</label>
    <select id="{{ $id }}" class="form-select" style="min-height: 44px; border-radius: 10px !important;">
        {{ $slot }}
    </select>
</div>
