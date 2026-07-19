/**
 * ============================================================
 * ADMIN USER MANAGEMENT – Header Search JS
 * resources/js/admin/users/search.js
 * ============================================================
 */

const UsersSearch = (() => {
    'use strict';

    function initHeaderSearch() {
        const searchInput = document.getElementById('header-user-search');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#user-tbody tr');

            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                
                if (name.includes(query) || email.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initHeaderSearch();
    });

    return {};
})();
