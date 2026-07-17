@props([
    'id' => 'news-list'
])

<div id="{{ $id }}" class="d-flex flex-column gap-3">
    {{ $slot }}
</div>
