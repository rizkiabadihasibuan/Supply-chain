@extends('layouts.app')

@section('title', 'Admin Control Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary border-opacity-25">
    <h1 class="h2 text-white text-glow-purple">Admin Control Panel</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary d-flex align-items-center">
            <i class="bi bi-shield-check me-2"></i> Mode Administrator
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between position-relative overflow-hidden" style="border-color: rgba(139, 92, 246, 0.4); box-shadow: 0 0 20px rgba(139, 92, 246, 0.15);">
            <div class="position-relative" style="z-index: 2;">
                <h3 class="text-white">Selamat Datang di Portal Admin, {{ Auth::user()->name }}!</h3>
                <p class="text-secondary mb-0">Halaman ini dilindungi oleh middleware keamanan peran (`RoleMiddleware`). Hanya pengguna dengan peran `Admin` yang dapat masuk ke sini.</p>
            </div>
            <div class="d-none d-lg-block text-glow-purple" style="font-size: 5rem; z-index: 1; opacity: 0.15; transform: rotate(-10deg);">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-shield-check me-2 text-glow-cyan"></i> Otoritas Khusus Admin</h5>
            <ul class="text-secondary ps-3">
                <li>Mengelola Akun Pengguna & Hak Akses (RBAC).</li>
                <li>Mengonfigurasi endpoint Integrasi Multi-API.</li>
                <li>Menyesuaikan bobot kalkulasi risiko (Risk Scoring Engine).</li>
                <li>Melihat log diagnostik audit sistem & pemanggilan API eksternal.</li>
            </ul>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-cpu me-2 text-glow-purple"></i> Status Server & Log Integrasi</h5>
            <div class="text-secondary small">
                <p class="mb-1"><strong>Status MySQL:</strong> <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Running (Localhost)</span></p>
                <p class="mb-1"><strong>Laravel Version:</strong> 12.0+ (PHP 8.3)</p>
                <p class="mb-1"><strong>Security Middleware:</strong> Active</p>
                <p class="mb-0"><strong>Audit Trail Logging:</strong> Enabled</p>
            </div>
        </div>
    </div>
</div>
@endsection
