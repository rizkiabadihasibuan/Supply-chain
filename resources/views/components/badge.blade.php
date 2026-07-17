@props([
    'type' => 'primary', // primary, success, danger, warning, info
    'text' => ''
])

@php
    $badgeClass = 'badge-primary';
    if ($type === 'success') {
        $badgeClass = 'badge-success';
    } elseif ($type === 'danger') {
        $badgeClass = 'badge-danger';
    } elseif ($type === 'warning') {
        $badgeClass = 'badge-warning';
    } elseif ($type === 'info') {
        $badgeClass = 'badge-info';
    }
@endphp

<span class="badge {{ $badgeClass }}">{{ $text ?: $slot }}</span>
