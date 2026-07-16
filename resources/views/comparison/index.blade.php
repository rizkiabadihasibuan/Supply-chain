@extends('layouts.app')

@section('title', 'Comparison - SupplyChain Platform')
@section('header_title', 'Country Risk Comparison')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <h5 class="fw-bold text-dark mb-1">Perbandingan Risiko Antar Negara</h5>
            <p class="text-muted small mb-3">Bandingkan profil risiko rantai pasokan dari dua negara atau lebih secara berdampingan.</p>
            
            <div class="alert alert-light border border-light-subtle rounded-3 p-4">
                <div class="text-center text-muted">
                    <i class="bi bi-columns-gap fs-2 mb-2 d-block"></i>
                    <p class="mb-0">Fitur perbandingan risiko antar negara disiapkan untuk diimplementasikan menggunakan pemanggilan AJAX pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
