{{-- ═══════════════════════════════════════════════════
     USER MODAL CREATE COMPONENT – Milestone 3.15B
     resources/views/components/user-modal-create.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="userCreateModal" tabindex="-1" aria-labelledby="userCreateModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="userCreateModalLabel"><i class="bi bi-person-plus-fill text-primary me-2"></i>Tambah Pengguna Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                <form id="form-create-user" class="needs-validation" novalidate onsubmit="event.preventDefault(); UsersModal.saveCreate();">
                    {{-- Nama Lengkap --}}
                    <div class="mb-3">
                        <label for="create-fullname" class="form-label small fw-semibold text-secondary mb-1.5">Nama Lengkap</label>
                        <input type="text" id="create-fullname" class="form-control" placeholder="Contoh: John Doe" required style="min-height: 44px;">
                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="create-email" class="form-label small fw-semibold text-secondary mb-1.5">Email</label>
                        <input type="email" id="create-email" class="form-control" placeholder="Contoh: johndoe@domain.com" required style="min-height: 44px;">
                        <div class="invalid-feedback">Masukkan email yang valid.</div>
                    </div>

                    {{-- Password & Konfirmasi --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="create-password" class="form-label small fw-semibold text-secondary mb-1.5">Password</label>
                            <input type="password" id="create-password" class="form-control" required style="min-height: 44px;" minlength="6">
                            <div class="invalid-feedback">Minimal 6 karakter.</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="create-password-confirm" class="form-label small fw-semibold text-secondary mb-1.5">Konfirmasi Password</label>
                            <input type="password" id="create-password-confirm" class="form-control" required style="min-height: 44px;">
                            <div class="invalid-feedback">Konfirmasi password wajib diisi.</div>
                        </div>
                    </div>

                    {{-- Role & Status --}}
                    <div class="row g-3">
                        <div class="col-sm-6 mb-3">
                            <label for="create-role" class="form-label small fw-semibold text-secondary mb-1.5">Peran (Role)</label>
                            <select id="create-role" class="form-select" required style="min-height: 44px;">
                                <option value="User" selected>User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="create-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="create-status" class="form-select" required style="min-height: 44px;">
                                <option value="Active" selected>Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="d-none">
                        <button type="submit" id="btn-submit-create-form"></button>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" id="btn-save-create" onclick="document.getElementById('btn-submit-create-form').click()">
                    Simpan
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
