/**
 * ============================================================
 * ADMIN USER MANAGEMENT – Table Sorting & Actions JS
 * public/js/admin/users/table.js
 * ============================================================
 */

const UsersTable = (() => {
    'use strict';

    let currentSortCol = -1;
    let isAscending = true;

    // Menyortir data tabel pengguna berdasarkan indeks kolom
    function sortTable(columnIndex) {
        const table = document.getElementById('user-data-table');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Cek jika urutan naik atau turun
        if (currentSortCol === columnIndex) {
            isAscending = !isAscending;
        } else {
            currentSortCol = columnIndex;
            isAscending = true;
        }

        // Tanda indikator arah sortir pada header
        const headers = table.querySelectorAll('th.sortable');
        headers.forEach((th, idx) => {
            const icon = th.querySelector('.sort-icon');
            if (icon) {
                if (idx === columnIndex) {
                    icon.className = isAscending ? 'bi bi-sort-up sort-icon ms-1 text-primary' : 'bi bi-sort-down sort-icon ms-1 text-primary';
                } else {
                    icon.className = 'bi bi-arrow-down-up sort-icon ms-1 text-secondary';
                }
            }
        });

        // Pengurutan
        rows.sort((rowA, rowB) => {
            let valA = rowA.cells[columnIndex].textContent.trim();
            let valB = rowB.cells[columnIndex].textContent.trim();

            // Pengecekan tipe tanggal pada kolom Tanggal Bergabung (index 5)
            if (columnIndex === 5) {
                const parseDate = (dStr) => {
                    const parts = dStr.split('-');
                    return new Date(parts[2], parts[1] - 1, parts[0]);
                };
                return isAscending ? parseDate(valA) - parseDate(valB) : parseDate(valB) - parseDate(valA);
            }

            return isAscending ? valA.localeCompare(valB) : valB.localeCompare(valA);
        });

        // Memasang kembali baris tersortir ke DOM
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }

    return { sortTable };
})();
