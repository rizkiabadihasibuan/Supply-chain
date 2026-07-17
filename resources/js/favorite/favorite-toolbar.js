/**
 * favorite-toolbar.js
 * Search, filter and sorting logic for Favorite Monitoring List.
 * Prepared for API integration – replace applyFilters() with real fetch.
 */

const FavoriteToolbar = (() => {
    let _filters = { search: '', region: 'all', risk: 'all', sort: 'name' };

    function _el(id) { return document.getElementById(id); }

    function readFilters() {
        _filters.search = (_el('favSearch')?.value ?? '').trim().toLowerCase();
        _filters.region = _el('favFilterRegion')?.value ?? 'all';
        _filters.risk   = _el('favFilterRisk')?.value   ?? 'all';
        _filters.sort   = _el('favSort')?.value          ?? 'name';
    }

    function apply() {
        readFilters();

        const cards = Array.from(document.querySelectorAll('[data-fav-card]'));
        const rows  = Array.from(document.querySelectorAll('[data-fav-row]'));

        let visible = 0;

        // Filter cards
        cards.forEach(card => {
            const match = _matches(card);
            card.parentElement.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        // Filter table rows
        rows.forEach(row => {
            row.style.display = _matches(row) ? '' : 'none';
        });

        // Sort cards
        _sortCards(cards);

        // Empty / content toggle
        const grid    = _el('favGrid');
        const emptyEl = _el('favEmpty');
        if (grid && emptyEl) {
            grid.style.display    = visible > 0 ? '' : 'none';
            emptyEl.style.display = visible > 0 ? 'none' : '';
        }

        // Update count badge
        const badge = _el('favCount');
        if (badge) badge.textContent = visible;
    }

    function _matches(el) {
        const name   = (el.dataset.name   ?? '').toLowerCase();
        const region = el.dataset.region  ?? 'all';
        const risk   = el.dataset.risk    ?? 'all';

        return (!_filters.search || name.includes(_filters.search))
            && (_filters.region === 'all' || region === _filters.region)
            && (_filters.risk   === 'all' || risk   === _filters.risk);
    }

    function _sortCards(cards) {
        const grid = _el('favGrid');
        if (!grid) return;

        const wrappers = cards
            .filter(c => c.parentElement?.style.display !== 'none')
            .sort((a, b) => {
                switch (_filters.sort) {
                    case 'name':      return a.dataset.name.localeCompare(b.dataset.name);
                    case 'risk-asc':  return parseFloat(a.dataset.risk) - parseFloat(b.dataset.risk);
                    case 'risk-desc': return parseFloat(b.dataset.risk) - parseFloat(a.dataset.risk);
                    case 'gdp-desc':  return parseFloat(b.dataset.gdp)  - parseFloat(a.dataset.gdp);
                    default:          return 0;
                }
            });

        wrappers.forEach(c => grid.appendChild(c.parentElement));
    }

    function reset() {
        ['favSearch','favFilterRegion','favFilterRisk','favSort'].forEach(id => {
            const el = _el(id);
            if (el) el.value = el.tagName === 'SELECT' ? el.options[0]?.value : '';
        });
        apply();
    }

    return { apply, reset };
})();
