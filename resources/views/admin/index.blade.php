@extends('layouts.app')

@section('title', 'Admin Panel - SupplyChain Platform')
@section('header_title', 'System Administrator')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <h5 class="fw-bold text-dark mb-1">Panel Administrasi Sistem</h5>
            <p class="text-muted small mb-3">Konfigurasi sinkronisasi API, kelola cron jobs, dan monitoring logs integrasi API.</p>
            
            <div class="alert alert-light border border-light-subtle rounded-3 p-4">
                <div class="text-center text-muted">
                    <i class="bi bi-shield-lock fs-2 mb-2 d-block"></i>
                    <p class="mb-0">Fitur panel administrasi disiapkan untuk diimplementasikan secara dinamis menggunakan AJAX pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
