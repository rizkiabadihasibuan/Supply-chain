@props([
    'id',
    'label',
    'checked' => false
])

<div class="form-check form-switch d-flex justify-content-between align-items-center p-0 mb-3" style="min-height: 38px;">
    <label class="form-check-label text-dark fw-medium" for="{{ $id }}" style="font-size: 0.875rem;">{{ $label }}</label>
    <input class="form-check-input ms-auto" type="checkbox" id="{{ $id }}" style="width: 44px; height: 22px; cursor: pointer;" @if($checked) checked @endif>
</div>
