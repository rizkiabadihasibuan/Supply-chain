{{-- ═══════════════════════════════════════════════════
     ADMIN PORT DATASET DETAIL PAGE – Milestone 3.15C
     resources/views/admin/ports/detail.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Detail Pelabuhan - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/ports/ports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/table.css') }}">
@endsection

@section('content')
    <div class="container py-4 text-start">
        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.ports') }}">Port Dataset</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pelabuhan</li>
            </ol>
        </nav>

        <div class="row g-4">
            {{-- Detail Information --}}
            <div class="col-12 col-lg-6">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100">
                    <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                        <i class="bi bi-info-circle-fill fs-4 text-primary"></i>
                        <h5 class="fw-bold text-dark mb-0">Informasi Detail Pelabuhan</h5>
                    </div>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Kode Pelabuhan</span>
                            <span class="port-detail-value fw-bold text-dark">IDTPP</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Nama Pelabuhan</span>
                            <span class="port-detail-value text-primary">Tanjung Priok</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Negara</span>
                            <span class="port-detail-value">Indonesia</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Wilayah (Region)</span>
                            <span class="port-detail-value">Asia Tenggara</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Latitude</span>
                            <span class="port-detail-value">-6.1033</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Longitude</span>
                            <span class="port-detail-value">106.8792</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Timezone</span>
                            <span class="port-detail-value">Asia/Jakarta (GMT+07:00)</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">UN/LOCODE</span>
                            <span class="port-detail-value fw-bold text-dark">IDTPP</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Status</span>
                            <span class="badge badge-status-aktif">Aktif</span>
                        </div>

                        <div class="port-detail-row d-flex justify-content-between align-items-center">
                            <span class="port-detail-label">Tanggal Sinkronisasi Terakhir</span>
                            <span class="port-detail-value text-secondary">Today 08:30 UTC</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Preview --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 rounded-4 p-4 bg-light shadow-sm h-100 text-center d-flex flex-column justify-content-center align-items-center">
                    <span class="d-block small fw-bold text-secondary mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Lokasi Pelabuhan</span>
                    <div class="p-3 border rounded border-2 border-dashed bg-white w-100 flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                        <i class="bi bi-map-fill fs-1 text-secondary mb-2"></i>
                        <span class="text-secondary" style="font-size:0.8rem;">Peta akan ditampilkan setelah integrasi OpenStreetMap selesai.</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex mt-4">
            <a href="{{ route('admin.ports') }}" class="btn btn-light border w-100 style="min-height: 44px; display:flex; align-items:center; justify-content:center;">
                Kembali
            </a>
        </div>
    </div>
@endsection
