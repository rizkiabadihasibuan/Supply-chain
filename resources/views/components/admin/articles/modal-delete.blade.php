{{-- ═══════════════════════════════════════════════════
     ARTICLE MODAL DELETE COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/modal-delete.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="articleDeleteModal" tabindex="-1" aria-labelledby="articleDeleteModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="articleDeleteModalLabel"><i class="bi bi-trash-fill text-danger me-2"></i>Konfirmasi Penghapusan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="bi bi-exclamation-triangle fs-2 text-danger d-block mb-3"></i>
                <h6 class="fw-bold text-dark mb-2">Apakah Anda yakin ingin menghapus artikel ini?</h6>
                <p class="text-secondary small mb-0">Artikel yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-danger flex-grow-1" id="btn-save-delete-article" onclick="ArticlesModal.confirmDelete()">
                    Hapus
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
