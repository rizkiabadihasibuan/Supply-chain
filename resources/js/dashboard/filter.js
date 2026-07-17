/**
 * filter.js
 * Handles all toolbar filter logic: search, region, year, currency, risk level.
 * Prepared for API integration – replace applyFilters() fetch when API is ready.
 */

const FilterManager = (() => {
    let _currentFilters = {
        search: '',
        region: 'all',
        year: '2024',
        currency: 'all',
        riskLevel: 'all',
        chartType: 'all',
    };

    function _getEl(id) { return document.getElementById(id); }

    function readFilters() {
        _currentFilters.search    = (_getEl('filterSearch')?.value    ?? '').trim().toLowerCase();
        _currentFilters.region    = _getEl('filterRegion')?.value     ?? 'all';
        _currentFilters.year      = _getEl('filterYear')?.value       ?? '2024';
        _currentFilters.currency  = _getEl('filterCurrency')?.value   ?? 'all';
        _currentFilters.riskLevel = _getEl('filterRiskLevel')?.value  ?? 'all';
        _currentFilters.chartType = _getEl('filterChartType')?.value  ?? 'all';
    }

    function applyTableFilter() {
        const rows = document.querySelectorAll('[data-table-row]');
        let visibleCount = 0;

        rows.forEach(row => {
            const name      = (row.dataset.name      ?? '').toLowerCase();
            const region    = row.dataset.region     ?? 'all';
            const currency  = row.dataset.currency   ?? 'all';
            const riskLevel = row.dataset.risklevel  ?? 'all';

            const matchSearch   = !_currentFilters.search   || name.includes(_currentFilters.search);
            const matchRegion   = _currentFilters.region   === 'all' || region   === _currentFilters.region;
            const matchCurrency = _currentFilters.currency === 'all' || currency === _currentFilters.currency;
            const matchRisk     = _currentFilters.riskLevel === 'all' || riskLevel === _currentFilters.riskLevel;

            const visible = matchSearch && matchRegion && matchCurrency && matchRisk;
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        // Update count label
        const countEl = _getEl('tableVisibleCount');
        if (countEl) countEl.textContent = visibleCount;

        return visibleCount;
    }

    function applyChartFilter() {
        const type = _currentFilters.chartType;
        const allCards = ['gdp', 'inflation', 'currency', 'risk'];

        allCards.forEach(id => {
            const el = _getEl(`chartWrap_${id}`);
            if (!el) return;
            el.style.display = (type === 'all' || type === id) ? '' : 'none';
        });
    }

    /** Master apply – call on any filter change */
    function apply() {
        readFilters();
        const visibleCount = applyTableFilter();
        applyChartFilter();

        // Show/hide empty state
        const emptyEl   = _getEl('emptyState');
        const contentEl = _getEl('mainContent');
        if (emptyEl && contentEl) {
            const hasContent = visibleCount > 0;
            emptyEl.style.display   = hasContent ? 'none'  : 'block';
            contentEl.style.display = hasContent ? ''      : 'none';
        }
    }

    function reset() {
        const fields = ['filterSearch','filterRegion','filterYear','filterCurrency','filterRiskLevel','filterChartType'];
        fields.forEach(id => {
            const el = _getEl(id);
            if (el) { el.value = el.tagName === 'SELECT' ? el.options[0]?.value : ''; }
        });
        apply();
    }

    function getFilters() { return { ..._currentFilters }; }

    return { apply, reset, getFilters };
})();
