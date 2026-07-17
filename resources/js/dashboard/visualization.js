/**
 * visualization.js
 * Main orchestrator for the Data Visualization Dashboard.
 * Coordinates ChartManager, FilterManager, and TableManager.
 *
 * FUTURE API INTEGRATION POINTS:
 *   - fetchGdpData()       => World Bank API
 *   - fetchInflationData() => World Bank API
 *   - fetchCurrencyData()  => Exchange Rate API
 *   - fetchRiskData()      => Risk Engine API
 *   - fetchCountriesData() => REST Countries API
 *
 * Replace _loadData() with real fetch calls when APIs are ready.
 */

document.addEventListener('DOMContentLoaded', () => {

    // -------------------------------------------------------
    // State management
    // -------------------------------------------------------
    const STATE = {
        LOADING : 'loading',
        READY   : 'ready',
        EMPTY   : 'empty',
        ERROR   : 'error',
    };

    let currentState = STATE.LOADING;

    function _getEl(id) { return document.getElementById(id); }

    // -------------------------------------------------------
    // State UI switcher
    // -------------------------------------------------------
    function showState(state) {
        currentState = state;
        const skeletonEl  = _getEl('skeletonSection');
        const mainEl      = _getEl('mainContent');
        const emptyEl     = _getEl('emptyState');
        const errorEl     = _getEl('errorState');

        [skeletonEl, mainEl, emptyEl, errorEl].forEach(el => {
            if (el) el.style.display = 'none';
        });

        switch (state) {
            case STATE.LOADING:
                if (skeletonEl) skeletonEl.style.display = '';
                break;
            case STATE.READY:
                if (mainEl) mainEl.style.display = '';
                break;
            case STATE.EMPTY:
                if (emptyEl) emptyEl.style.display = '';
                break;
            case STATE.ERROR:
                if (errorEl) errorEl.style.display = '';
                break;
        }
    }

    // -------------------------------------------------------
    // Simulate loading then show data (replace with real API)
    // -------------------------------------------------------
    function _loadData() {
        showState(STATE.LOADING);

        // TODO: Replace setTimeout with real API fetch calls
        // e.g.: Promise.all([fetchGdpData(), fetchCurrencyData(), ...]).then(...)
        setTimeout(() => {
            try {
                ChartManager.init();
                FilterManager.apply();
                showState(STATE.READY);
            } catch (err) {
                console.error('[Visualization] Error during init:', err);
                showState(STATE.ERROR);
            }
        }, 800);
    }

    // -------------------------------------------------------
    // Page-level refresh button
    // -------------------------------------------------------
    const refreshPageBtn = _getEl('btnRefreshPage');
    if (refreshPageBtn) {
        refreshPageBtn.addEventListener('click', () => {
            ChartManager.refreshAll('btnRefreshPage');
        });
    }

    // -------------------------------------------------------
    // Toolbar filter events
    // -------------------------------------------------------
    ['filterSearch','filterRegion','filterYear','filterCurrency','filterRiskLevel','filterChartType']
        .forEach(id => {
            const el = _getEl(id);
            if (el) el.addEventListener('input', () => FilterManager.apply());
        });

    // -------------------------------------------------------
    // Reset filters
    // -------------------------------------------------------
    const resetBtn = _getEl('btnResetFilters');
    if (resetBtn) resetBtn.addEventListener('click', () => FilterManager.reset());

    // -------------------------------------------------------
    // Simulate states (for demo/testing purposes)
    // -------------------------------------------------------
    const btnSimEmpty = _getEl('btnSimEmpty');
    const btnSimError = _getEl('btnSimError');
    const btnSimLoad  = _getEl('btnSimLoading');

    if (btnSimEmpty)  btnSimEmpty.addEventListener('click',  () => showState(STATE.EMPTY));
    if (btnSimError)  btnSimError.addEventListener('click',  () => showState(STATE.ERROR));
    if (btnSimLoad)   btnSimLoad.addEventListener('click',   () => _loadData());

    // -------------------------------------------------------
    // Retry from error
    // -------------------------------------------------------
    const retryBtn = _getEl('btnRetry');
    if (retryBtn) retryBtn.addEventListener('click', _loadData);

    // -------------------------------------------------------
    // Bootstrap tooltips
    // -------------------------------------------------------
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover focus', placement: 'top' });
    });

    // -------------------------------------------------------
    // Kick off
    // -------------------------------------------------------
    _loadData();
});
