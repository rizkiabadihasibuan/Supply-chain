@props([
    'id' => ''
])

<div class="table-responsive-card">
    <table class="table table-hover align-middle mb-0" @if($id) id="{{ $id }}" @endif>
        {{ $slot }}
    </table>
</div>
