@props([
    'action' => '#',
    'id' => ''
])

<form action="{{ $action }}" method="POST" @if($id) id="{{ $id }}" @endif class="d-flex flex-column gap-3 text-start">
    @csrf
    {{ $slot }}
</form>
