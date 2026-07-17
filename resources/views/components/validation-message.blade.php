@props([
    'type' => 'error', // success, error, warning, info
    'message' => ''
])

@php
    $alertClass = 'alert-danger';
    $icon = 'bi-exclamation-octagon-fill';
    
    if ($type === 'success') {
        $alertClass = 'alert-success';
        $icon = 'bi-check-circle-fill';
    } elseif ($type === 'warning') {
        $alertClass = 'alert-warning';
        $icon = 'bi-exclamation-triangle-fill';
    } elseif ($type === 'info') {
        $alertClass = 'alert-info';
        $icon = 'bi-info-circle-fill';
    }
@endphp

<div class="alert {{ $alertClass }} d-flex align-items-center gap-2.5 p-3 rounded-3" role="alert" style="font-size: 0.825rem;">
    <i class="bi {{ $icon }} fs-6"></i>
    <div>
        {{ $message ?: $slot }}
    </div>
</div>
