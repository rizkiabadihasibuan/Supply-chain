@props([
    'title',
    'type' => 'info' // danger, warning, success, info
])

@php
    $borderColor = 'border-primary';
    $bgLight = 'rgba(37, 99, 235, 0.05)';
    $textColor = 'text-primary';
    
    if ($type === 'danger') {
        $borderColor = 'border-danger';
        $bgLight = 'rgba(239, 68, 68, 0.05)';
        $textColor = 'text-danger';
    } elseif ($type === 'warning') {
        $borderColor = 'border-warning';
        $bgLight = 'rgba(245, 158, 11, 0.05)';
        $textColor = 'text-warning';
    } elseif ($type === 'success') {
        $borderColor = 'border-success';
        $bgLight = 'rgba(34, 197, 94, 0.05)';
        $textColor = 'text-success';
    }
@endphp

<div class="card p-4 border-0 border-start border-4 {{ $borderColor }} h-100" style="background-color: {{ $bgLight }} !important;">
    <h6 class="fw-bold {{ $textColor }} mb-2">{{ $title }}</h6>
    <div class="text-secondary small" style="line-height: 1.6;">
        {{ $slot }}
    </div>
</div>
