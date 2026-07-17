/**
 * table.js
 * Analytics table: sorting, search count display, export placeholders.
 * Prepared for API integration.
 */

const TableManager = (() => {
    let _sortCol = null;
    let _sortDir = 'asc';

    function _getEl(id) { return document.getElementById(id); }

    function sortByColumn(col) {
        const tbody = _getEl('analyticsTableBody');
        if (!tbody) return;

        _sortDir = (_sortCol === col && _sortDir === 'asc') ? 'desc' : 'asc';
        _sortCol = col;

        const rows = Array.from(tbody.querySelectorAll('[data-table-row]'));

        rows.sort((a, b) => {
            let va = a.dataset[col] ?? '';
            let vb = b.dataset[col] ?? '';
            const numA = parseFloat(va);
            const numB = parseFloat(vb);
            if (!isNaN(numA) && !isNaN(numB)) {
                return _sortDir === 'asc' ? numA - numB : numB - numA;
            }
            return _sortDir === 'asc' ? va.localeCompare(vb) : vb.localeCompare(va);
        });

        rows.forEach(r => tbody.appendChild(r));

        // Update sort icons
        document.querySelectorAll('[data-sort-col]').forEach(th => {
            const icon = th.querySelector('i');
            if (!icon) return;
            if (th.dataset.sortCol === col) {
                icon.className = _sortDir === 'asc' ? 'bi bi-sort-up ms-1 text-primary' : 'bi bi-sort-down ms-1 text-primary';
            } else {
                icon.className = 'bi bi-arrow-down-up ms-1 text-muted opacity-50';
            }
        });
    }

    // Export placeholders
    function exportPDF()   { _showExportToast('PDF');   }
    function exportExcel() { _showExportToast('Excel'); }
    function exportCSV()   { _showExportToast('CSV');   }
    function exportPrint() { window.print(); }

    function _showExportToast(format) {
        const toastEl = _getEl('exportToast');
        const toastMsg = _getEl('exportToastMsg');
        if (toastMsg) toastMsg.textContent = `Mempersiapkan ekspor format ${format}… (Simulasi)`;
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }
    }

    return { sortByColumn, exportPDF, exportExcel, exportCSV, exportPrint };
})();
