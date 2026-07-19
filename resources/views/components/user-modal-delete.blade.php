{{-- ═══════════════════════════════════════════════════
     USER MODAL DELETE & SUSPEND COMPONENTS – Milestone 3.15B
     resources/views/components/user-modal-delete.blade.php
     ═══════════════════════════════════════════════════ --}}

{{-- 1. MODAL SUSPEND USER --}}
<div class="modal fade" id="userSuspendModal" tabindex="-1" aria-labelledby="userSuspendModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="userSuspendModalLabel"><i class="bi bi-slash-circle-fill text-danger me-2"></i>Nonaktifkan Akun Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-exclamation-octagon fs-2 text-danger d-block mb-3"></i>
                <h6 class="fw-bold text-dark mb-2">Konfirmasi Penangguhan Akun</h6>
                <p class="text-secondary small mb-0">Apakah Anda yakin ingin menonaktifkan akun pengguna ini?</p>
            </div>
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-danger flex-grow-1" id="btn-save-suspend" onclick="UsersModal.confirmSuspend()">
                    Ya
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 2. MODAL DELETE USER --}}
<div class="modal fade" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="userDeleteModalLabel"><i class="bi bi-trash-fill text-danger me-2"></i>Hapus Data Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-trash fs-2 text-danger d-block mb-3"></i>
                <h6 class="fw-bold text-dark mb-2">Konfirmasi Hapus Akun</h6>
                <p class="text-secondary small mb-0">Data yang dihapus tidak dapat dikembalikan secara utuh.</p>
            </div>
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-danger flex-grow-1" id="btn-save-delete" onclick="UsersModal.confirmDelete()">
                    Hapus
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
