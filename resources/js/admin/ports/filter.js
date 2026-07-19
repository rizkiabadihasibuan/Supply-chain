/**
 * ============================================================
 * ADMIN PORT DATASET – Filter JS
 * resources/js/admin/ports/filter.js
 * ============================================================
 */

const PortsFilter = (() => {
    'use strict';

    function applyFilters() {
        const keywordVal = document.getElementById('filter-port-keyword')?.value.toLowerCase();
        const countryVal = document.getElementById('filter-port-country')?.value.toLowerCase();
        const regionVal = document.getElementById('filter-port-region')?.value.toLowerCase();
        const statusVal = document.getElementById('filter-port-status')?.value.toLowerCase();
        const sortByVal = document.getElementById('filter-port-sort')?.value;

        const tbody = document.getElementById('port-tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.forEach(row => {
            let matches = true;

            // Live Search (Nama Pelabuhan, Negara, atau Kode)
            if (keywordVal) {
                const code = row.cells[1].textContent.toLowerCase();
                const name = row.cells[2].textContent.toLowerCase();
                const country = row.cells[3].textContent.toLowerCase();
                if (!name.includes(keywordVal) && !country.includes(keywordVal) && !code.includes(keywordVal)) {
                    matches = false;
                }
            }

            if (countryVal && countryVal !== '') {
                const country = row.cells[3].textContent.toLowerCase().trim();
                if (country !== countryVal) matches = false;
            }

            if (regionVal && regionVal !== '') {
                const region = row.cells[4].textContent.toLowerCase().trim();
                if (region !== regionVal) matches = false;
            }

            if (statusVal && statusVal !== '') {
                const status = row.cells[7].textContent.toLowerCase().trim();
                if (status !== statusVal) matches = false;
            }

            row.style.display = matches ? '' : 'none';
        });

        if (sortByVal && sortByVal !== '') {
            rows.sort((rowA, rowB) => {
                let nameA = rowA.cells[2].textContent.trim();
                let nameB = rowB.cells[2].textContent.trim();
                let dateA = rowA.cells[8].textContent.trim();
                let dateB = rowB.cells[8].textContent.trim();

                const parseDate = (dStr) => {
                    if (dStr.includes('Today')) return new Date();
                    if (dStr.includes('Yesterday')) {
                        const d = new Date();
                        d.setDate(d.getDate() - 1);
                        return d;
                    }
                    const parts = dStr.split(' ')[0].split('-');
                    return new Date(parts[2], parts[1] - 1, parts[0]);
                };

                if (sortByVal === 'name-asc') return nameA.localeCompare(nameB);
                if (sortByVal === 'name-desc') return nameB.localeCompare(nameA);
                if (sortByVal === 'date-newest') return parseDate(dateB) - parseDate(dateA);
                if (sortByVal === 'date-oldest') return parseDate(dateA) - parseDate(dateB);
                return 0;
            });

            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
            updateRowNumbers();
        }

        const visibleRows = rows.filter(r => r.style.display !== 'none');
        PortsCore.showToast(`Ditemukan ${visibleRows.length} dataset pelabuhan.`);
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#port-tbody tr');
        let counter = 1;
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const noCell = row.cells[0];
                if (noCell) noCell.textContent = counter++;
            }
        });
    }

    function resetFilters() {
        const filterIds = ['filter-port-keyword', 'filter-port-country', 'filter-port-region', 'filter-port-status', 'filter-port-sort'];
        filterIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const rows = document.querySelectorAll('#port-tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });

        applyFilters();
        PortsCore.showToast('Filter pencarian dataset berhasil di-reset!');
    }

    return { applyFilters, resetFilters, updateRowNumbers };
})();
