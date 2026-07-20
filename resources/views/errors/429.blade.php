{{-- ERROR 429 – Terlalu Banyak Permintaan --}}
@extends('layouts.user.app')
@section('title', '429 Terlalu Banyak Permintaan - SupplyChain Platform')
@section('content')
<div class="d-flex flex-column align-items-center justify-content-center" style="min-height:60vh; text-align:center;">
    <div class="mb-4">
        <i class="bi bi-speedometer2 display-1 text-secondary opacity-50"></i>
    </div>
    <h1 class="display-4 fw-bold text-dark">429</h1>
    <h2 class="h4 fw-semibold mb-3">Terlalu Banyak Permintaan</h2>
    <p class="text-muted mb-4 col-md-6">Anda telah melampaui batas request. Silakan tunggu sebentar.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary">
        <i class="bi bi-house-door-fill me-2"></i>Kembali ke Dashboard
    </a>
</div>
@endsection
