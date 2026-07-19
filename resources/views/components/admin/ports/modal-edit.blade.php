{{-- ═══════════════════════════════════════════════════
     PORT MODAL EDIT COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/modal-edit.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="portEditModal" tabindex="-1" aria-labelledby="portEditModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="portEditModalLabel"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit Dataset Pelabuhan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                <form id="form-edit-port" class="needs-validation" novalidate onsubmit="event.preventDefault(); PortsModal.saveEdit();">
                    {{-- Nama Pelabuhan --}}
                    <div class="mb-3 text-start">
                        <label for="edit-port-name" class="form-label small fw-semibold text-secondary mb-1.5">Nama Pelabuhan</label>
                        <input type="text" id="edit-port-name" class="form-control" required style="min-height: 44px;">
                        <div class="invalid-feedback">Nama pelabuhan wajib diisi.</div>
                    </div>

                    {{-- Kode Pelabuhan & UN/LOCODE --}}
                    <div class="row g-3 mb-3 text-start">
                        <div class="col-sm-6">
                            <label for="edit-port-code" class="form-label small fw-semibold text-secondary mb-1.5">Kode Pelabuhan</label>
                            <input type="text" id="edit-port-code" class="form-control" required style="min-height: 44px;" disabled>
                        </div>
                        <div class="col-sm-6">
                            <label for="edit-port-unlocode" class="form-label small fw-semibold text-secondary mb-1.5">UN/LOCODE</label>
                            <input type="text" id="edit-port-unlocode" class="form-control" required style="min-height: 44px;" disabled>
                        </div>
                    </div>

                    {{-- Negara & Region --}}
                    <div class="row g-3 mb-3 text-start">
                        <div class="col-sm-6">
                            <label for="edit-port-country" class="form-label small fw-semibold text-secondary mb-1.5">Negara</label>
                            <input type="text" id="edit-port-country" class="form-control" required style="min-height: 44px;">
                            <div class="invalid-feedback">Negara wajib diisi.</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="edit-port-region" class="form-label small fw-semibold text-secondary mb-1.5">Region</label>
                            <input type="text" id="edit-port-region" class="form-control" required style="min-height: 44px;">
                            <div class="invalid-feedback">Region wajib diisi.</div>
                        </div>
                    </div>

                    {{-- Koordinat (Lat/Lng) --}}
                    <div class="row g-3 mb-3 text-start">
                        <div class="col-sm-6">
                            <label for="edit-port-lat" class="form-label small fw-semibold text-secondary mb-1.5">Latitude</label>
                            <input type="number" step="any" id="edit-port-lat" class="form-control" required style="min-height: 44px;">
                            <div class="invalid-feedback">Latitude wajib diisi.</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="edit-port-lng" class="form-label small fw-semibold text-secondary mb-1.5">Longitude</label>
                            <input type="number" step="any" id="edit-port-lng" class="form-control" required style="min-height: 44px;">
                            <div class="invalid-feedback">Longitude wajib diisi.</div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-3 text-start">
                        <label for="edit-port-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                        <select id="edit-port-status" class="form-select" required style="min-height: 44px;">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div class="d-none">
                        <button type="submit" id="btn-submit-edit-port-form"></button>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" id="btn-save-edit-port" onclick="document.getElementById('btn-submit-edit-port-form').click()">
                    Update
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const originalShowEdit = PortsModal.showEdit;
        PortsModal.showEdit = function(name, code, country, region, latitude, longitude, status, capacity) {
            originalShowEdit(name, code, country, region, latitude, longitude, status, capacity);
            document.getElementById('edit-port-unlocode').value = code;
        };
    });
</script>
