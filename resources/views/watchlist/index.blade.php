@extends('layouts.app')

@section('title', 'Watchlist - SupplyChain Platform')
@section('header_title', 'My Watchlist')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <h5 class="fw-bold text-dark mb-1">Daftar Pantauan Negara & Pelabuhan</h5>
            <p class="text-muted small mb-3">Simpan negara dan pelabuhan yang kritis terhadap operasional rantai pasok Anda untuk akses cepat dan notifikasi otomatis.</p>
            
            <div class="alert alert-light border border-light-subtle rounded-3 p-4">
                <div class="text-center text-muted">
                    <i class="bi bi-star fs-2 mb-2 d-block"></i>
                    <p class="mb-0">Fitur daftar pantauan (watchlist) disiapkan untuk diimplementasikan menggunakan pemanggilan AJAX pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
