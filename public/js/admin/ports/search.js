/**
 * ============================================================
 * ADMIN PORT DATASET – Search JS
 * public/js/admin/ports/search.js
 * ============================================================
 */

const PortsSearch = (() => {
    'use strict';

    function initHeaderSearch() {
        const searchInput = document.getElementById('header-port-search');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#port-tbody tr');

            rows.forEach(row => {
                const code = row.cells[1].textContent.toLowerCase();
                const name = row.cells[2].textContent.toLowerCase();
                const country = row.cells[3].textContent.toLowerCase();
                
                if (name.includes(query) || code.includes(query) || country.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            if (typeof PortsFilter !== 'undefined') {
                PortsFilter.updateRowNumbers();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initHeaderSearch();
    });

    return {};
})();
