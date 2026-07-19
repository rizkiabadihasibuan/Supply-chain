/**
 * ============================================================
 * ADMIN DASHBOARD – Recent Activities JS
 * resources/js/admin/activity.js
 * ============================================================
 */

const AdminActivity = (() => {
    'use strict';

    function initActivitySearch() {
        const searchInput = document.getElementById('admin-activity-search');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#admin-activity-tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }

    // Simulasi data kosong pada log aktivitas
    function toggleEmptyState() {
        const tableArea = document.getElementById('admin-activity-table-wrapper');
        const emptyState = document.getElementById('admin-activity-empty-state');
        const toggleBtn = document.getElementById('btn-toggle-activity-empty');

        if (tableArea && emptyState) {
            if (tableArea.style.display !== 'none') {
                tableArea.style.display = 'none';
                emptyState.style.display = 'block';
                if (toggleBtn) toggleBtn.textContent = '⚡ Pulihkan Log';
            } else {
                tableArea.style.display = 'block';
                emptyState.style.display = 'none';
                if (toggleBtn) toggleBtn.textContent = '⚡ Simulasikan Kosong';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initActivitySearch();
    });

    return { toggleEmptyState };
})();
