@props([
    'id',
    'onchange' => ''
])

<select id="{{ $id }}" class="form-select" style="min-height: 44px;" @if($onchange) onchange="{{ $onchange }}" @endif>
    {{ $slot }}
</select>
