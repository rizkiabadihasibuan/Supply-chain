/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Filter JS
 * public/js/admin/articles/filter.js
 * ============================================================
 */

const ArticlesFilter = (() => {
    'use strict';

    function applyFilters() {
        const keywordVal = document.getElementById('filter-article-keyword')?.value.toLowerCase();
        const categoryVal = document.getElementById('filter-article-category')?.value.toLowerCase();
        const statusVal = document.getElementById('filter-article-status')?.value.toLowerCase();
        const dateVal = document.getElementById('filter-article-date')?.value;
        const authorVal = document.getElementById('filter-article-author')?.value.toLowerCase();
        const sortByVal = document.getElementById('filter-article-sort')?.value;

        const tbody = document.getElementById('article-tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.forEach(row => {
            let matches = true;

            // Judul filter (Index 2)
            if (keywordVal) {
                const title = row.cells[2].textContent.toLowerCase();
                if (!title.includes(keywordVal)) matches = false;
            }

            // Kategori filter (Index 3)
            if (categoryVal && categoryVal !== '') {
                const cat = row.cells[3].textContent.toLowerCase().trim();
                if (cat !== categoryVal) matches = false;
            }

            // Penulis filter (Index 4)
            if (authorVal && authorVal !== '') {
                const author = row.cells[4].textContent.toLowerCase().trim();
                if (!author.includes(authorVal)) matches = false;
            }

            // Status filter (Index 6)
            if (statusVal && statusVal !== '') {
                const status = row.cells[6].textContent.toLowerCase().trim();
                if (status !== statusVal) matches = false;
            }

            // Tanggal filter (Index 5)
            if (dateVal) {
                const rowDateStr = row.cells[5].textContent.trim(); // format: "18-07-2026"
                if (rowDateStr !== '—') {
                    const parts = rowDateStr.split('-');
                    const rowDate = new Date(parts[2], parts[1] - 1, parts[0]);
                    const filterDate = new Date(dateVal);

                    if (rowDate.toDateString() !== filterDate.toDateString()) {
                        matches = false;
                    }
                } else {
                    matches = false;
                }
            }

            row.style.display = matches ? '' : 'none';
        });

        // Lakukan pengurutan (Sorting)
        if (sortByVal && sortByVal !== '') {
            rows.sort((rowA, rowB) => {
                let nameA = rowA.cells[2].textContent.trim();
                let nameB = rowB.cells[2].textContent.trim();
                let dateA = rowA.cells[5].textContent.trim();
                let dateB = rowB.cells[5].textContent.trim();
                let viewsA = parseInt(rowA.cells[7].textContent.replace(/,/g, '').trim()) || 0;
                let viewsB = parseInt(rowB.cells[7].textContent.replace(/,/g, '').trim()) || 0;

                const parseDate = (dStr) => {
                    if (dStr === '—') return new Date(0);
                    const parts = dStr.split('-');
                    return new Date(parts[2], parts[1] - 1, parts[0]);
                };

                if (sortByVal === 'title-asc') return nameA.localeCompare(nameB);
                if (sortByVal === 'title-desc') return nameB.localeCompare(nameA);
                if (sortByVal === 'date-newest') return parseDate(dateB) - parseDate(dateA);
                if (sortByVal === 'date-oldest') return parseDate(dateA) - parseDate(dateB);
                if (sortByVal === 'views-desc') return viewsB - viewsA;
                return 0;
            });

            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }

        updateRowNumbers();

        const visibleRows = rows.filter(r => r.style.display !== 'none');
        ArticlesCore.showToast(`Ditemukan ${visibleRows.length} artikel.`);
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#article-tbody tr');
        let counter = 1;
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const noCell = row.cells[0];
                if (noCell) noCell.textContent = counter++;
            }
        });
    }

    function resetFilters() {
        const filterIds = ['filter-article-keyword', 'filter-article-category', 'filter-article-status', 'filter-article-date', 'filter-article-author', 'filter-article-sort'];
        filterIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const rows = document.querySelectorAll('#article-tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });

        applyFilters();
        ArticlesCore.showToast('Filter pencarian artikel berhasil di-reset!');
    }

    // Inisialisasi live search di header
    function initHeaderSearch() {
        const searchInput = document.getElementById('header-article-search');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#article-tbody tr');

            rows.forEach(row => {
                const title = row.cells[2].textContent.toLowerCase();
                const category = row.cells[3].textContent.toLowerCase();
                
                if (title.includes(query) || category.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            updateRowNumbers();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initHeaderSearch();
    });

    return { applyFilters, resetFilters, updateRowNumbers };
})();
