{{-- ═══════════════════════════════════════════════════
     PAGINATION COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/pagination.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2">
    <span class="text-secondary small" aria-live="polite">Menampilkan 1–10 dari 1.258 data.</span>
    <nav aria-label="Navigasi halaman dataset pelabuhan">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Halaman Sebelumnya">
                    <span aria-hidden="true">&laquo; Previous</span>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#" aria-current="page">1</a></li>
            <li class="page-item"><a class="page-link" href="#" onclick="PortsCore.showToast('Membuka halaman 2 (Simulasi)...')" aria-label="Halaman 2">2</a></li>
            <li class="page-item"><a class="page-link" href="#" onclick="PortsCore.showToast('Membuka halaman 3 (Simulasi)...')" aria-label="Halaman 3">3</a></li>
            <li class="page-item"><a class="page-link" href="#" onclick="PortsCore.showToast('Membuka halaman 4 (Simulasi)...')" aria-label="Halaman 4">4</a></li>
            <li class="page-item"><a class="page-link" href="#" onclick="PortsCore.showToast('Membuka halaman 5 (Simulasi)...')" aria-label="Halaman 5">5</a></li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Halaman Selanjutnya" onclick="PortsCore.showToast('Membuka halaman berikutnya (Simulasi)...')">
                    <span aria-hidden="true">Next &raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
