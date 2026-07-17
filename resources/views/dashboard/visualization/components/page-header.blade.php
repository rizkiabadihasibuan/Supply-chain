{{-- ============================================================
     PAGE HEADER - Visualization Dashboard
     resources/views/dashboard/visualization/components/page-header.blade.php
     ============================================================ --}}
@props([
    'title'    => 'Data Visualization Dashboard',
    'subtitle' => 'Visualisasikan kondisi ekonomi, nilai tukar, inflasi, dan tingkat risiko negara secara interaktif.',
    'date'     => '',
])

<div class="row align-items-center mb-4 g-3">
    {{-- Left: Title --}}
    <div class="col-12 col-md-7">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-3" style="background: rgba(37,99,235,0.08);">
                <i class="bi bi-bar-chart-line-fill text-primary fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">{{ $title }}</h4>
                <p class="text-secondary small mb-0 mt-1">{{ $subtitle }}</p>
            </div>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="col-12 col-md-5 d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
        {{-- Date indicator --}}
        <span class="badge bg-white text-secondary border shadow-sm px-3 py-2 rounded-pill d-flex align-items-center gap-1" style="font-size:0.78rem;">
            <i class="bi bi-calendar3 text-primary"></i>
            {{ $date ?: now()->translatedFormat('d F Y') }}
        </span>

        {{-- Refresh --}}
        <button id="btnRefreshPage"
                class="btn btn-primary btn-sm d-flex align-items-center gap-2"
                style="min-height:38px;"
                data-bs-toggle="tooltip" title="Segarkan seluruh data grafik">
            <i class="bi bi-arrow-clockwise"></i>
            <span class="d-none d-sm-inline">Segarkan</span>
        </button>

        {{-- Export dropdown --}}
        <div class="dropdown">
            <button class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 dropdown-toggle"
                    type="button" data-bs-toggle="dropdown" style="min-height:38px;">
                <i class="bi bi-download"></i>
                <span class="d-none d-sm-inline">Ekspor</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                <li>
                    <button class="dropdown-item small py-2" type="button" onclick="TableManager.exportPDF()">
                        <i class="bi bi-filetype-pdf text-danger me-2"></i> PDF
                    </button>
                </li>
                <li>
                    <button class="dropdown-item small py-2" type="button" onclick="TableManager.exportExcel()">
                        <i class="bi bi-filetype-xls text-success me-2"></i> Excel
                    </button>
                </li>
                <li>
                    <button class="dropdown-item small py-2" type="button" onclick="TableManager.exportCSV()">
                        <i class="bi bi-filetype-csv text-info me-2"></i> CSV
                    </button>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button class="dropdown-item small py-2" type="button" onclick="TableManager.exportPrint()">
                        <i class="bi bi-printer me-2"></i> Print
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>
