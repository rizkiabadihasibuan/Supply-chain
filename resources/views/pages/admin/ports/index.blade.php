{{-- ═══════════════════════════════════════════════════
     ADMIN PORT DATASET INDEX – Milestone 3.16A
     resources/views/pages/admin/ports/index.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Port Dataset Management - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/ports/ports.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/ports/responsive.css') }}">
@endsection

@section('content')
    <div class="admin-ports-wrapper">
        {{-- ══════ HEADER ══════ --}}
        <x-admin.ports.port-header />

        {{-- Container Success Alert (Success Alert) --}}
        <div id="ports-success-alert-container" style="display: none;" class="mb-2">
            <x-admin.ports.success-alert />
        </div>

        {{-- Container Error Simulator --}}
        <div id="ports-error-container" style="display: none;" class="mb-2">
            <x-admin.ports.error-state />
        </div>

        {{-- Skeleton Loading Container (Hidden) --}}
        <div id="ports-skeleton-container" style="display: none;" class="mb-2">
            <x-admin.ports.loading-state />
        </div>

        {{-- ══════ MAIN CONTENT AREA (Direct Display) ══════ --}}
        <div id="ports-main-content">
            
            {{-- ══════ ACTION TOOLBAR ══════ --}}
            <div class="port-toolbar-container">
                <div class="btn-group" role="group" aria-label="Toolbar Aksi Pelabuhan">
                    {{-- Tambah Dataset --}}
                    <button class="btn btn-primary d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#portCreateModal" style="min-height: 38px; font-size: 0.85rem;" data-bs-toggle="tooltip" title="Tambah data pelabuhan baru ke sistem" aria-label="Tambah Dataset Baru">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Dataset
                    </button>
                    {{-- Import CSV --}}
                    <button class="btn btn-success d-flex align-items-center gap-1.5 text-white" data-bs-toggle="modal" data-bs-target="#portImportModal" style="min-height: 38px; font-size: 0.85rem;" data-bs-toggle="tooltip" title="Impor dataset pelabuhan dari file CSV" aria-label="Impor berkas CSV">
                        <i class="bi bi-upload"></i> Import CSV
                    </button>
                    {{-- Export Dropdown --}}
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-1.5" data-bs-toggle="dropdown" aria-expanded="false" style="min-height: 38px; font-size: 0.85rem;" aria-label="Ekspor data pelabuhan">
                            <i class="bi bi-download"></i> Export Data
                        </button>
                        <ul class="dropdown-menu shadow-sm">
                            <li><button class="dropdown-item small" type="button" onclick="PortsExport.triggerExport('csv')"><i class="bi bi-filetype-csv text-success me-1.5"></i>Export CSV</button></li>
                            <li><button class="dropdown-item small" type="button" onclick="PortsExport.triggerExport('excel')"><i class="bi bi-file-earmark-excel text-primary me-1.5"></i>Export Excel</button></li>
                            <li><button class="dropdown-item small" type="button" onclick="PortsExport.triggerExport('pdf')"><i class="bi bi-file-pdf text-danger me-1.5"></i>Export PDF</button></li>
                        </ul>
                    </div>
                    {{-- Refresh --}}
                    <button class="btn btn-secondary d-flex align-items-center gap-1.5 text-white" onclick="PortsCore.refreshData()" style="min-height: 38px; font-size: 0.85rem;" data-bs-toggle="tooltip" title="Sinkronisasi ulang dataset pelabuhan" aria-label="Refresh Dataset">
                        <i class="bi bi-arrow-clockwise"></i> Refresh Dataset
                    </button>
                </div>
            </div>

            {{-- ══════ STATISTICS (4 Cards from DB) ══════ --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Total Port" value="{{ number_format($ports->count()) }}" icon="anchor" color="primary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Countries Covered" value="{{ $ports->pluck('country_id')->unique()->count() }}" icon="globe" color="success" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Active Port" value="{{ number_format($ports->count()) }}" icon="check-circle" color="info" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Last Synchronization" value="Terhubung WPI" icon="clock" color="warning" />
                </div>
            </div>

            {{-- ══════ FILTER SECTION ══════ --}}
            <div class="mb-4">
                <x-admin.ports.filter-card />
            </div>

            {{-- ══════ PORT DATASET TABLE (Real DB Data) ══════ --}}
            <div class="mb-4">
                <x-admin.ports.port-table>
                    @foreach($ports as $index => $p)
                        <x-admin.ports.table-row 
                            :no="$index + 1"
                            :code="$p->code"
                            :name="$p->name"
                            :country="$p->country?->name ?? '—'"
                            :region="$p->country?->region?->name ?? '—'"
                            status="Aktif"
                            :latitude="number_format($p->latitude, 4)"
                            :longitude="number_format($p->longitude, 4)"
                            :lastUpdated="$p->updated_at ? $p->updated_at->format('d-m-Y') : 'Hari ini'"
                            :capacity="$p->size ?? 'Medium'"
                            timezone="UTC"
                        />
                    @endforeach
                </x-admin.ports.port-table>
            </div>

            {{-- ══════ PAGINATION ══════ --}}
            <div class="mt-4 mb-4">
                <x-admin.ports.pagination />
            </div>

            {{-- Empty State (Simulasi/Sembunyi by default) --}}
            <div id="ports-empty-state" style="display: none;" class="mb-4">
                <x-admin.ports.empty-state />
            </div>

        </div>

        {{-- Action Simulator Testing UI --}}
        <div class="d-flex justify-content-end gap-2 pt-3 border-top mb-4">
            <button class="btn btn-sm btn-outline-danger" onclick="PortsCore.simulateError()" aria-label="Simulasikan data error">
                ⚡ Simulasikan Error Layanan
            </button>
            <button class="btn btn-sm btn-outline-secondary" onclick="simulateEmptyState()" id="btn-toggle-ports-empty" aria-label="Simulasikan data kosong">
                ⚡ Simulasikan Kosong
            </button>
        </div>
    </div>

    {{-- ══════ MODAL DIALOGS ══════ --}}
    <x-admin.ports.modal-create />
    <x-admin.ports.modal-edit />
    <x-admin.ports.modal-detail />
    <x-admin.ports.modal-delete />
    <x-admin.ports.modal-import />
@endsection

@section('scripts')
    <!-- Tooltips initialization script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Simulasi Empty State
        function simulateEmptyState() {
            const content = document.getElementById('ports-main-content');
            const empty = document.getElementById('ports-empty-state');
            const toggleBtn = document.getElementById('btn-toggle-ports-empty');
            const tableContainer = document.querySelector('.port-table-container');

            if (tableContainer.style.display !== 'none') {
                tableContainer.style.display = 'none';
                empty.style.display = 'block';
                toggleBtn.textContent = '⚡ Pulihkan Dataset';
            } else {
                tableContainer.style.display = 'block';
                empty.style.display = 'none';
                toggleBtn.textContent = '⚡ Simulasikan Kosong';
            }
        }
    </script>
    <!-- Port Management scripts -->
    <script src="{{ asset('js/admin/ports/ports.js') }}"></script>
    <script src="{{ asset('js/admin/ports/modal.js') }}"></script>
    <script src="{{ asset('js/admin/ports/table.js') }}"></script>
    <script src="{{ asset('js/admin/ports/filter.js') }}"></script>
    <script src="{{ asset('js/admin/ports/search.js') }}"></script>
    <script src="{{ asset('js/admin/ports/import.js') }}"></script>
    <script src="{{ asset('js/admin/ports/export.js') }}"></script>
@endsection
