@props([
    'priority' => 'low',
    'text' => ''
])

@php
    $badgeClass = 'badge-primary';
    
    if ($priority === 'critical') {
        $badgeClass = 'badge-danger';
    } elseif ($priority === 'high' || $priority === 'medium') {
        $badgeClass = 'badge-warning';
    } elseif ($priority === 'low') {
        $badgeClass = 'badge-success';
    }
@endphp

<span class="badge {{ $badgeClass }}">{{ $text ?: $slot }}</span>
