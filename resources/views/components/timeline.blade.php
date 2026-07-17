@props([
    'id' => ''
])

<div @if($id) id="{{ $id }}" @endif style="position: relative; padding-left: 20px;">
    <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
    {{ $slot }}
</div>
