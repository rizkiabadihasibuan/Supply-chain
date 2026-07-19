{{-- ERROR 419 – Halaman Kedaluwarsa --}}
@extends('layouts.user.app')
@section('title', '419 Halaman Kedaluwarsa - SupplyChain Platform')
@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height:60vh; text-align:center;">
    <div class="mb-4">
        <i class="bi bi-exclamation-triangle-fill display-1 text-secondary opacity-50"></i>
    </div>
    <h1 class="display-4 fw-bold text-dark">419</h1>
    <h2 class="h4 fw-semibold mb-3">Halaman Kedaluwarsa</h2>
    <p class="text-muted mb-4 col-md-6">Token CSRF kedaluwarsa. Silakan refresh halaman dan coba lagi.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
        <i class="bi bi-house-door-fill me-2"></i>Kembali ke Dashboard
    </a>
</div>
@endsection
