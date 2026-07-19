{{-- ═══════════════════════════════════════════════════
     PORT MODAL CREATE COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/modal-create.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="portCreateModal" tabindex="-1" aria-labelledby="portCreateModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="portCreateModalLabel"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Dataset Pelabuhan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                <form id="form-create-port" class="needs-validation" novalidate onsubmit="event.preventDefault(); PortsModal.saveCreate();">
                    {{-- Nama Pelabuhan --}}
                    <div class="mb-3 text-start">
                        <label for="create-port-name" class="form-label small fw-semibold text-secondary mb-1.5">Nama Pelabuhan</label>
                        <input type="text" id="create-port-name" class="form-control" placeholder="Masukkan nama pelabuhan..." required style="min-height: 44px;">
                        <div class="invalid-feedback">Nama pelabuhan wajib diisi.</div>
                    </div>

                    {{-- Kode Pelabuhan & UN/LOCODE --}}
                    <div class="row g-3 mb-3 text-start">
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
                    <div class="row g-3 mb-3 text-start">
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
                    <div class="row g-3 mb-3 text-start">
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
                    <div class="mb-3 text-start">
                        <label for="create-port-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                        <select id="create-port-status" class="form-select" required style="min-height: 44px;">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="d-none">
                        <button type="submit" id="btn-submit-create-port-form"></button>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" id="btn-save-create-port" onclick="document.getElementById('btn-submit-create-port-form').click()">
                    Simpan
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
