@props([
    'placeholder' => 'Cari...',
    'id' => 'search-input',
    'oninput' => ''
])

<div class="search-wrapper">
    <i class="bi bi-search"></i>
    <input type="text" id="{{ $id }}" placeholder="{{ $placeholder }}" class="form-control ps-5 w-100" style="min-height: 44px;" @if($oninput) oninput="{{ $oninput }}" @endif>
</div>
