{{-- ═══════════════════════════════════════════════════
     USER MODAL EDIT COMPONENT – Milestone 3.15B
     resources/views/components/user-modal-edit.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="userEditModalLabel"><i class="bi bi-pencil-fill text-warning me-2"></i>Ubah Data Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                <form id="form-edit-user" class="needs-validation" novalidate onsubmit="event.preventDefault(); UsersModal.saveEdit();">
                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="edit-fullname" class="form-label small fw-semibold text-secondary mb-1.5">Nama Lengkap</label>
                        <input type="text" id="edit-fullname" class="form-control" required style="min-height: 44px;">
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="edit-email" class="form-label small fw-semibold text-secondary mb-1.5">Email</label>
                        <input type="email" id="edit-email" class="form-control" required style="min-height: 44px;">
                        <div class="invalid-feedback">Masukkan email yang valid.</div>
                    </div>

                    {{-- Role & Status --}}
                    <div class="row g-3">
                        <div class="col-sm-6 mb-3">
                            <label for="edit-role" class="form-label small fw-semibold text-secondary mb-1.5">Peran (Role)</label>
                            <select id="edit-role" class="form-select" required style="min-height: 44px;">
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="edit-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="edit-status" class="form-select" required style="min-height: 44px;">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-none">
                        <button type="submit" id="btn-submit-edit-form"></button>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" id="btn-save-edit" onclick="document.getElementById('btn-submit-edit-form').click()">
                    Update
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
