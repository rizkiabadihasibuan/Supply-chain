{{-- ═══════════════════════════════════════════════════
     USER PAGINATION COMPONENT – Milestone 3.15B
     resources/views/components/user-pagination.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2">
    <span class="text-secondary small">Menampilkan 1-5 dari 12 user terdaftar</span>
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo; Previous</span>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#" onclick="UsersCore.showToast('Membuka halaman 2 (Simulasi)...')">2</a></li>
            <li class="page-item"><a class="page-link" href="#" onclick="UsersCore.showToast('Membuka halaman 3 (Simulasi)...')">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next" onclick="UsersCore.showToast('Membuka halaman berikutnya (Simulasi)...')">
                    <span aria-hidden="true">Next &raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
