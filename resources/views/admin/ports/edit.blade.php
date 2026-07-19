{{-- ═══════════════════════════════════════════════════
     ADMIN PORT DATASET EDIT PAGE – Milestone 3.15C
     resources/views/admin/ports/edit.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Ubah Dataset Pelabuhan - SupplyChain Platform')

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
                        <li class="breadcrumb-item active" aria-current="page">Ubah Dataset</li>
                    </ol>
                </nav>

                <div class="card p-4 border-0 shadow-sm rounded-4">
                    <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                        <i class="bi bi-pencil-fill fs-4 text-warning"></i>
                        <h5 class="fw-bold text-dark mb-0">Ubah Dataset Pelabuhan</h5>
                    </div>

                    <form class="needs-validation" novalidate onsubmit="event.preventDefault(); PortsCore.showToast('Dataset berhasil diperbarui.'); window.location.href='{{ route('admin.ports') }}';">
                        {{-- Nama Pelabuhan --}}
                        <div class="mb-3">
                            <label for="edit-port-name" class="form-label small fw-semibold text-secondary mb-1.5">Nama Pelabuhan</label>
                            <input type="text" id="edit-port-name" class="form-control" value="Tanjung Priok" required style="min-height: 44px;">
                            <div class="invalid-feedback">Nama pelabuhan wajib diisi.</div>
                        </div>

                        {{-- Kode Pelabuhan & UN/LOCODE --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="edit-port-code" class="form-label small fw-semibold text-secondary mb-1.5">Kode Pelabuhan</label>
                                <input type="text" id="edit-port-code" class="form-control" value="IDTPP" required style="min-height: 44px;" disabled>
                            </div>
                            <div class="col-sm-6">
                                <label for="edit-port-unlocode" class="form-label small fw-semibold text-secondary mb-1.5">UN/LOCODE</label>
                                <input type="text" id="edit-port-unlocode" class="form-control" value="IDTPP" required style="min-height: 44px;" disabled>
                            </div>
                        </div>

                        {{-- Negara & Region --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="edit-port-country" class="form-label small fw-semibold text-secondary mb-1.5">Negara</label>
                                <input type="text" id="edit-port-country" class="form-control" value="Indonesia" required style="min-height: 44px;">
                                <div class="invalid-feedback">Negara wajib diisi.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="edit-port-region" class="form-label small fw-semibold text-secondary mb-1.5">Region</label>
                                <input type="text" id="edit-port-region" class="form-control" value="Asia Tenggara" required style="min-height: 44px;">
                                <div class="invalid-feedback">Region wajib diisi.</div>
                            </div>
                        </div>

                        {{-- Koordinat (Lat/Lng) --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="edit-port-lat" class="form-label small fw-semibold text-secondary mb-1.5">Latitude</label>
                                <input type="number" step="any" id="edit-port-lat" class="form-control" value="-6.1033" required style="min-height: 44px;">
                                <div class="invalid-feedback">Latitude wajib diisi.</div>
                            </div>
                            <div class="col-sm-6">
                                <label for="edit-port-lng" class="form-label small fw-semibold text-secondary mb-1.5">Longitude</label>
                                <input type="number" step="any" id="edit-port-lng" class="form-control" value="106.8792" required style="min-height: 44px;">
                                <div class="invalid-feedback">Longitude wajib diisi.</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="edit-port-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="edit-port-status" class="form-select" required style="min-height: 44px;">
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1" style="min-height: 44px;">
                                Update Dataset
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
