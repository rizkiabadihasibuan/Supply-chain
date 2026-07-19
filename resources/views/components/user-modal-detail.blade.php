{{-- ═══════════════════════════════════════════════════
     USER MODAL DETAIL COMPONENT – Milestone 3.15B
     resources/views/components/user-modal-detail.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="userDetailModalLabel"><i class="bi bi-person-badge-fill text-primary me-2"></i>Informasi Detail Pengguna</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                {{-- Avatar & Name Header --}}
                <div class="text-center mb-4">
                    <img src="" alt="User Avatar" class="user-detail-avatar-large mb-3" id="detail-avatar">
                    <h5 class="fw-bold text-dark mb-1" id="detail-fullname">Nama Lengkap</h5>
                    <span class="text-secondary small" id="detail-email">email@domain.com</span>
                </div>

                {{-- Detail Grid List --}}
                <div class="border rounded-3 p-3 bg-light-subtle">
                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Peran (Role)</span>
                        <span class="badge bg-secondary" id="detail-role">User</span>
                    </div>

                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Status Akun</span>
                        <span class="badge" id="detail-status">Active</span>
                    </div>

                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Tanggal Bergabung</span>
                        <span class="user-detail-value" id="detail-joined">Join Date</span>
                    </div>

                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Login Terakhir</span>
                        <span class="user-detail-value" id="detail-lastlogin">Last Login</span>
                    </div>

                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Nomor Telepon</span>
                        <span class="user-detail-value" id="detail-phone">Phone</span>
                    </div>

                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Perusahaan (Company)</span>
                        <span class="user-detail-value" id="detail-company">Company</span>
                    </div>

                    <div class="user-detail-row d-flex justify-content-between align-items-center">
                        <span class="user-detail-label">Negara Asal (Country)</span>
                        <span class="user-detail-value" id="detail-country">Country</span>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
