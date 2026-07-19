{{-- ═══════════════════════════════════════════════════
     USER MODAL RESET PASSWORD COMPONENT – Milestone 3.15B
     resources/views/components/user-modal-reset-password.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="userResetPasswordModal" tabindex="-1" aria-labelledby="userResetPasswordModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="userResetPasswordModalLabel"><i class="bi bi-key-fill text-info me-2"></i>Reset Password Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                <form id="form-reset-password" class="needs-validation" novalidate onsubmit="event.preventDefault(); UsersModal.saveResetPassword();">
                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label for="reset-password-val" class="form-label small fw-semibold text-secondary mb-1.5">Password Baru</label>
                        <input type="password" id="reset-password-val" class="form-control" placeholder="Masukkan password baru..." required style="min-height: 44px;" minlength="6">
                        <div class="invalid-feedback">Minimal 6 karakter.</div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-3">
                        <label for="reset-password-confirm-val" class="form-label small fw-semibold text-secondary mb-1.5">Konfirmasi Password</label>
                        <input type="password" id="reset-password-confirm-val" class="form-control" placeholder="Konfirmasi password baru..." required style="min-height: 44px;">
                        <div class="invalid-feedback">Konfirmasi password wajib diisi.</div>
                    </div>

                    {{-- Submit Trigger --}}
                    <div class="d-none">
                        <button type="submit" id="btn-submit-reset-form"></button>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" id="btn-save-reset" onclick="document.getElementById('btn-submit-reset-form').click()">
                    Reset Password
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
