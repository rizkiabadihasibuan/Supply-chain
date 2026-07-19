{{-- ═══════════════════════════════════════════════════
     ADMIN PORT DATASET CREATE PAGE – Milestone 3.15C
     resources/views/admin/ports/create.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Tambah Dataset Pelabuhan - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/ports/ports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/modal.css') }}">
@endsection

@section('content')
    <div class="container py-4 text-start">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.ports') }}">Port Dataset</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Dataset</li>
                    </ol>
                </nav>

                <div class="card p-4 border-0 shadow-sm rounded-4">
                    <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                        <i class="bi bi-plus-circle-fill fs-4 text-primary"></i>
                        <h5 class="fw-bold text-dark mb-0">Tambah Dataset Pelabuhan Baru</h5>
                    </div>

                    <form class="needs-validation" novalidate onsubmit="event.preventDefault(); PortsCore.showToast('Dataset berhasil ditambahkan.'); window.location.href='{{ route('admin.ports') }}';">
                        {{-- Nama Pelabuhan --}}
                        <div class="mb-3">
                            <label for="create-port-name" class="form-label small fw-semibold text-secondary mb-1.5">Nama Pelabuhan</label>
                            <input type="text" id="create-port-name" class="form-control" placeholder="Masukkan nama pelabuhan..." required style="min-height: 44px;">
                            <div class="invalid-feedback">Nama pelabuhan wajib diisi.</div>
                        </div>

                        {{-- Kode Pelabuhan & UN/LOCODE --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="create-port-code" class="form-label small fw-semibold text-secondary mb-1.5">Kode Pelabuhan</label>
                                <input type="text" id="create-port-code" class="form-control" placeholder="Contoh: IDTPP" required style="min-height: 44px;">
                                <div class="invalid-feedback">Kode pelabuhan wajib diisi.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="create-port-unlocode" class="form-label small fw-semibold text-secondary mb-1.5">UN/LOCODE</label>
                                <input type="text" id="create-port-unlocode" class="form-control" placeholder="Contoh: IDTPP" required style="min-height: 44px;">
                                <div class="invalid-feedback">UN/LOCODE wajib diisi.</div>
                            </div>
                        </div>

                        {{-- Negara & Region --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="create-port-country" class="form-label small fw-semibold text-secondary mb-1.5">Negara</label>
                                <input type="text" id="create-port-country" class="form-control" placeholder="Masukkan negara..." required style="min-height: 44px;">
                                <div class="invalid-feedback">Negara wajib diisi.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="create-port-region" class="form-label small fw-semibold text-secondary mb-1.5">Region</label>
                                <input type="text" id="create-port-region" class="form-control" placeholder="Masukkan region..." required style="min-height: 44px;">
                                <div class="invalid-feedback">Region wajib diisi.</div>
                            </div>
                        </div>

                        {{-- Koordinat (Lat/Lng) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="create-port-lat" class="form-label small fw-semibold text-secondary mb-1.5">Latitude</label>
                                <input type="number" step="any" id="create-port-lat" class="form-control" placeholder="Contoh: -6.1033" required style="min-height: 44px;">
                                <div class="invalid-feedback">Latitude wajib diisi.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="create-port-lng" class="form-label small fw-semibold text-secondary mb-1.5">Longitude</label>
                                <input type="number" step="any" id="create-port-lng" class="form-control" placeholder="Contoh: 106.8792" required style="min-height: 44px;">
                                <div class="invalid-feedback">Longitude wajib diisi.</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="create-port-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="create-port-status" class="form-select" required style="min-height: 44px;">
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1" style="min-height: 44px;">
                                Simpan Dataset
                            </button>
                            <a href="{{ route('admin.ports') }}" class="btn btn-light border" style="min-height: 44px; display:flex; align-items:center; justify-content:center;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/ports/ports.js') }}"></script>
@endsection
