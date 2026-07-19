{{-- ═══════════════════════════════════════════════════
     AVATAR MODAL COMPONENT – Milestone 3.14
     resources/views/components/avatar-modal.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="avatarUploadModal" tabindex="-1" aria-labelledby="avatarUploadModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="avatarUploadModalLabel"><i class="bi bi-camera-fill text-primary me-2"></i>Unggah & Crop Foto Profil</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="ProfileAvatar.resetModal()"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                {{-- File Input --}}
                <div class="mb-3.5">
                    <label for="avatar-file-input" class="form-label small fw-semibold text-secondary mb-1.5">Pilih Berkas Foto</label>
                    <input class="form-control" type="file" id="avatar-file-input" accept="image/*" onchange="ProfileAvatar.handleFileSelect(event)">
                </div>

                {{-- Crop Placeholder --}}
                <div class="mb-3.5">
                    <span class="d-block small fw-semibold text-secondary mb-1.5">Area Potong (Crop Area)</span>
                    <div class="avatar-crop-box" id="crop-placeholder-box">
                        <div class="text-center p-4">
                            <i class="bi bi-cloud-arrow-up fs-2 text-secondary d-block mb-1.5"></i>
                            <span>Pilih file gambar untuk disimulasikan crop</span>
                        </div>
                    </div>
                </div>

                {{-- Preview Section --}}
                <div>
                    <span class="d-block small fw-semibold text-secondary mb-1.5">Pratinjau Bundar</span>
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop" class="avatar-preview-circle" id="avatar-preview-circle-el" alt="Pratinjau Avatar">
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" id="btn-save-avatar" onclick="ProfileAvatar.saveAvatar()">
                    Simpan Foto
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" onclick="ProfileAvatar.resetModal()">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
