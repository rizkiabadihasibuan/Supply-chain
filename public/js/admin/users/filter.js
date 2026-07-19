/**
 * ============================================================
 * ADMIN USER MANAGEMENT – Filters Controls JS
 * public/js/admin/users/filter.js
 * ============================================================
 */

const UsersFilter = (() => {
    'use strict';

    function applyFilters() {
        const roleVal = document.getElementById('filter-role')?.value.toLowerCase();
        const statusVal = document.getElementById('filter-status')?.value.toLowerCase();
        const dateVal = document.getElementById('filter-joined-date')?.value; // format: YYYY-MM-DD
        const keywordVal = document.getElementById('filter-keyword')?.value.toLowerCase();

        const rows = document.querySelectorAll('#user-tbody tr');

        rows.forEach(row => {
            let matches = true;

            // 1. Keyword search (Name/Email)
            if (keywordVal) {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                if (!name.includes(keywordVal) && !email.includes(keywordVal)) {
                    matches = false;
                }
            }

            // 2. Role filter (Index 3)
            if (roleVal && roleVal !== '') {
                const role = row.cells[3].textContent.toLowerCase().trim();
                if (role !== roleVal) matches = false;
            }

            // 3. Status filter (Index 4)
            if (statusVal && statusVal !== '') {
                const status = row.cells[4].textContent.toLowerCase().trim();
                if (status !== statusVal) matches = false;
            }

            // 4. Date filter (Index 5 - format: d-m-Y)
            if (dateVal) {
                const joinedDateStr = row.cells[5].textContent.trim(); // format: 17-07-2026
                const dateParts = dateVal.split('-'); // [YYYY, MM, DD]
                const selectedDateStr = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; // DD-MM-YYYY
                
                if (joinedDateStr !== selectedDateStr) {
                    matches = false;
                }
            }

            row.style.display = matches ? '' : 'none';
        });

        // Hitung hasil pencarian
        const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        UsersCore.showToast(`Ditemukan ${visibleRows.length} akun pengguna.`);
    }

    function resetFilters() {
        const filterIds = ['filter-role', 'filter-status', 'filter-joined-date', 'filter-keyword'];
        filterIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const rows = document.querySelectorAll('#user-tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });

        UsersCore.showToast('Filter pencarian berhasil di-reset!');
    }

    return { applyFilters, resetFilters };
})();
