{{-- ═══════════════════════════════════════════════════
     LOGOUT MODAL COMPONENT – Authentication Flow
     resources/views/components/logout-modal.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
            {{-- Modal Header --}}
            <div class="modal-header border-0 pb-0" style="padding: 1.75rem 1.75rem .5rem;">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle" 
                         style="width: 48px; height: 48px; background: rgba(239,68,68,.1);">
                        <i class="bi bi-box-arrow-right" style="font-size: 1.2rem; color: #EF4444;"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="logoutModalLabel" style="font-size: 1.1rem;">
                            Konfirmasi Logout
                        </h5>
                        <small class="text-muted">Sesi Anda akan diakhiri</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body" style="padding: 1.25rem 1.75rem;">
                <p class="text-muted mb-0" style="font-size: .92rem; line-height: 1.6;">
                    Apakah Anda yakin ingin keluar dari platform? Anda perlu login kembali untuk mengakses dashboard.
                </p>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0" style="padding: .75rem 1.75rem 1.75rem; gap: .75rem;">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" 
                        style="border-radius: 10px; font-weight: 600; font-size: .88rem; border: 1px solid #E2E8F0;">
                    Batal
                </button>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4" 
                            style="border-radius: 10px; font-weight: 600; font-size: .88rem;">
                        <i class="bi bi-box-arrow-right me-1"></i> Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
