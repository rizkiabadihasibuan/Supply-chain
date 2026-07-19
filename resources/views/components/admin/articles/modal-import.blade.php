{{-- ═══════════════════════════════════════════════════
     ARTICLE MODAL IMPORT COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/modal-import.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="articleImportModal" tabindex="-1" aria-labelledby="articleImportModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="articleImportModalLabel"><i class="bi bi-upload text-success me-2"></i>Impor Artikel (CSV)</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="resetImportModal()"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4 text-center">
                {{-- Drag & Drop Area --}}
                <div class="border border-2 border-dashed rounded-3 p-4 bg-light-subtle mb-3" style="cursor: pointer;" onclick="document.getElementById('import-csv-file').click()" id="drag-drop-zone">
                    <i class="bi bi-cloud-arrow-up fs-2 text-success d-block mb-2"></i>
                    <span class="d-block fw-semibold text-dark small mb-1">Seret & lepas berkas di sini</span>
                    <span class="text-secondary" style="font-size: 0.75rem;">Atau klik untuk menelusuri folder berkas</span>
                    <span class="d-block text-secondary mt-1" style="font-size: 0.7rem;">Hanya mendukung format .CSV</span>
                    <input type="file" id="import-csv-file" accept=".csv" class="d-none" onchange="handleCSVFileSelect(event)">
                </div>

                {{-- Preview Section --}}
                <div id="import-preview-section" style="display: none;" class="mb-3 text-start">
                    <span class="d-block small fw-semibold text-secondary mb-1.5">Nama Berkas</span>
                    <div class="border rounded p-2.5 bg-light d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-filetype-csv text-success fs-4"></i>
                            <span class="small fw-semibold text-dark" id="csv-filename">artikel_analisis.csv</span>
                        </div>
                        <button type="button" class="btn-close" style="font-size:0.6rem;" onclick="resetImportModal()"></button>
                    </div>

                    {{-- Progress Bar --}}
                    <span class="d-block small fw-semibold text-secondary mb-1.5">Progres Impor Data</span>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%" id="csv-import-progress-bar"></div>
                    </div>
                </div>

                {{-- Summary Section --}}
                <div id="import-summary-section" style="display: none;" class="text-start">
                    <span class="d-block small fw-semibold text-secondary mb-1.5">Ringkasan Hasil Impor</span>
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="border rounded p-2.5 bg-light">
                                <span class="text-secondary d-block" style="font-size: 0.65rem;">Total Rekor</span>
                                <span class="fw-bold text-dark fs-5" id="csv-total-records">15</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2.5 bg-success bg-opacity-10">
                                <span class="text-success d-block" style="font-size: 0.65rem;">Sukses</span>
                                <span class="fw-bold text-success fs-5" id="csv-success-records">12</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2.5 bg-danger bg-opacity-10">
                                <span class="text-danger d-block" style="font-size: 0.65rem;">Gagal</span>
                                <span class="fw-bold text-danger fs-5" id="csv-failed-records">3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-success flex-grow-1 text-white" id="btn-start-import" onclick="startImportCSV()" disabled>
                    Impor Berkas
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" onclick="resetImportModal()">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
