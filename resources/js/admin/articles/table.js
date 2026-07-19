/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Table JS
 * resources/js/admin/articles/table.js
 * ============================================================
 */

const ArticlesTable = (() => {
    'use strict';

    let currentSortCol = -1;
    let isAscending = true;

    // Menyortir data tabel artikel
    function sortTable(columnIndex) {
        const table = document.getElementById('article-data-table');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        if (currentSortCol === columnIndex) {
            isAscending = !isAscending;
        } else {
            currentSortCol = columnIndex;
            isAscending = true;
        }

        const headers = table.querySelectorAll('th.sortable');
        headers.forEach((th, idx) => {
            const icon = th.querySelector('.sort-icon');
            if (icon) {
                if (idx === columnIndex - 2) { // offsets by 2 because No and Action are surrounding
                    icon.className = isAscending ? 'bi bi-sort-up sort-icon ms-1 text-primary' : 'bi bi-sort-down sort-icon ms-1 text-primary';
                } else {
                    icon.className = 'bi bi-arrow-down-up sort-icon ms-1 text-secondary';
                }
            }
        });

        rows.sort((rowA, rowB) => {
            let valA = rowA.cells[columnIndex].textContent.trim();
            let valB = rowB.cells[columnIndex].textContent.trim();
            return isAscending ? valA.localeCompare(valB) : valB.localeCompare(valA);
        });

        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }

    return { sortTable };
})();
