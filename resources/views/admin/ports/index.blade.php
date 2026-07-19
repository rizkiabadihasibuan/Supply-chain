{{-- ═══════════════════════════════════════════════════
     ADMIN PORT DATASET INDEX – Milestone 3.15C
     resources/views/admin/ports/index.blade.php
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

        {{-- Skeleton Loading Container --}}
        <div id="ports-skeleton-container" class="mb-2">
            <x-admin.ports.loading-state />
        </div>

        {{-- ══════ MAIN CONTENT AREA (Hidden on loading skeleton) ══════ --}}
        <div id="ports-main-content" style="display: none;">
            
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

            {{-- ══════ STATISTICS (4 Cards) ══════ --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Total Port" value="1,258" icon="anchor" color="primary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Countries Covered" value="195" icon="globe" color="success" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Active Port" value="1,173" icon="check-circle" color="info" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.ports.statistics-card title="Last Synchronization" value="Today 08:30 UTC" icon="clock" color="warning" />
                </div>
            </div>

            {{-- ══════ FILTER SECTION ══════ --}}
            <div class="mb-4">
                <x-admin.ports.filter-card />
            </div>

            {{-- ══════ PORT DATASET TABLE ══════ --}}
            <div class="mb-4">
                <x-admin.ports.port-table>
                    {{-- Row 1 --}}
                    <x-admin.ports.table-row 
                        no="1"
                        code="IDTPP"
                        name="Tanjung Priok"
                        country="Indonesia"
                        region="Asia Tenggara"
                        status="Aktif"
                        latitude="-6.1033"
                        longitude="106.8792"
                        lastUpdated="Today 08:30 UTC"
                        capacity="High"
                        timezone="Asia/Jakarta (GMT+07:00)"
                    />

                    {{-- Row 2 --}}
                    <x-admin.ports.table-row 
                        no="2"
                        code="SGSIN"
                        name="Port of Singapore"
                        country="Singapore"
                        region="Asia Tenggara"
                        status="Aktif"
                        latitude="1.2652"
                        longitude="103.8294"
                        lastUpdated="Today 08:28 UTC"
                        capacity="High"
                        timezone="Asia/Singapore (GMT+08:00)"
                    />

                    {{-- Row 3 --}}
                    <x-admin.ports.table-row 
                        no="3"
                        code="CNSHA"
                        name="Port of Shanghai"
                        country="China"
                        region="Asia Timur"
                        status="Aktif"
                        latitude="30.6252"
                        longitude="122.0673"
                        lastUpdated="Yesterday 14:15 UTC"
                        capacity="High"
                        timezone="Asia/Shanghai (GMT+08:00)"
                    />

                    {{-- Row 4 --}}
                    <x-admin.ports.table-row 
                        no="4"
                        code="USLAX"
                        name="Port of Los Angeles"
                        country="United States"
                        region="Amerika Utara"
                        status="Maintenance"
                        latitude="33.7292"
                        longitude="-118.2620"
                        lastUpdated="Yesterday 09:12 UTC"
                        capacity="High"
                        timezone="America/Los_Angeles (GMT-08:00)"
                    />

                    {{-- Row 5 --}}
                    <x-admin.ports.table-row 
                        no="5"
                        code="IDTPE"
                        name="Tanjung Perak"
                        country="Indonesia"
                        region="Asia Tenggara"
                        status="Tidak Aktif"
                        latitude="-7.2023"
                        longitude="112.7371"
                        lastUpdated="16-07-2026 11:30 UTC"
                        capacity="Medium"
                        timezone="Asia/Jakarta (GMT+07:00)"
                    />
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
