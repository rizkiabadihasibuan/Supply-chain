/**
 * favorite.js
 * Main orchestrator for Favorite Monitoring List.
 *
 * FUTURE API INTEGRATION:
 *   GET  /api/favorites        → load user's saved countries
 *   POST /api/favorites        → add a new favorite
 *   DELETE /api/favorites/{id} → remove a favorite
 *
 * Replace _loadData() Promise.resolve() with real fetch() calls.
 */

document.addEventListener('DOMContentLoaded', () => {

    const STATE = { LOADING:'loading', READY:'ready', ERROR:'error' };

    function _el(id) { return document.getElementById(id); }

    // ── Section toggler ────────────────────────────────
    function showState(state) {
        const skel    = _el('favSkeleton');
        const content = _el('favContent');
        const errEl   = _el('favError');

        [skel, content, errEl].forEach(el => { if (el) el.style.display = 'none'; });

        if (state === STATE.LOADING && skel)  skel.style.display  = '';
        if (state === STATE.READY  && content) content.style.display = '';
        if (state === STATE.ERROR  && errEl)   errEl.style.display = '';
    }

    // ── Compute & render KPI stats ──────────────────────
    function _renderStats(data) {
        const total   = data.length;
        const avgRisk = (data.reduce((s, c) => s + c.riskScore, 0) / total).toFixed(2);
        const maxRisk = Math.max(...data.map(c => c.riskScore)).toFixed(2);
        const safe    = data.filter(c => c.riskLevel === 'low').length;

        const set = (id, val) => { const el = _el(id); if (el) el.textContent = val; };
        set('statTotal',   total);
        set('statAvgRisk', avgRisk);
        set('statMaxRisk', maxRisk);
        set('statSafe',    safe);
    }

    // ── Remove card (simulate) ─────────────────────────
    function removeFavorite(code, btnEl) {
        if (!confirm('Hapus negara ini dari Favorite?')) return;

        const card = document.querySelector(`[data-fav-card][data-code="${code}"]`);
        if (card) {
            card.closest('.fav-grid-col')?.remove();
        }
        const row = document.querySelector(`[data-fav-row][data-code="${code}"]`);
        if (row) row.remove();

        // TODO: DELETE /api/favorites/{code}
        FavoriteToolbar.apply();

        const toastEl  = _el('favToast');
        const toastMsg = _el('favToastMsg');
        if (toastMsg) toastMsg.textContent = `Negara dihapus dari Favorite.`;
        if (toastEl) new bootstrap.Toast(toastEl, { delay:3000 }).show();
    }

    // ── Refresh simulation ─────────────────────────────
    function refreshFavorites(btnEl) {
        const icon = btnEl?.querySelector('i');
        if (icon) icon.classList.add('is-spinning');
        if (btnEl) btnEl.disabled = true;

        showState(STATE.LOADING);

        // TODO: Replace with GET /api/favorites
        setTimeout(() => {
            showState(STATE.READY);
            if (icon) icon.classList.remove('is-spinning');
            if (btnEl) btnEl.disabled = false;

            const toastEl = _el('favToast');
            const toastMsg = _el('favToastMsg');
            if (toastMsg) toastMsg.textContent = 'Data Favorite berhasil diperbarui!';
            if (toastEl) new bootstrap.Toast(toastEl, { delay: 2500 }).show();
        }, 900);
    }

    // ── Expose to inline handlers ──────────────────────
    window.removeFavorite     = removeFavorite;
    window.refreshFavorites   = refreshFavorites;
    window.openAddModal       = () => FavoriteModal.open();

    // ── Toolbar events ─────────────────────────────────
    ['favSearch','favFilterRegion','favFilterRisk','favSort'].forEach(id => {
        const el = _el(id);
        if (el) el.addEventListener('input', () => FavoriteToolbar.apply());
    });

    const btnReset = _el('btnResetFilters');
    if (btnReset) btnReset.addEventListener('click', () => FavoriteToolbar.reset());

    // ── Retry button ───────────────────────────────────
    const retryBtn = _el('btnRetry');
    if (retryBtn) retryBtn.addEventListener('click', () => _loadData());

    // ── Bootstrap tooltips ─────────────────────────────
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el =>
        new bootstrap.Tooltip(el, { trigger: 'hover focus', placement: 'top' })
    );

    // ── Load data (simulate) ───────────────────────────
    function _loadData() {
        showState(STATE.LOADING);

        // TODO: fetch('/api/favorites').then(r => r.json()).then(data => { ... })
        setTimeout(() => {
            try {
                _renderStats(FAVORITE_DATA);
                FavoriteModal.init();
                FavoriteToolbar.apply();
                showState(STATE.READY);
            } catch(err) {
                console.error('[Favorite] Error:', err);
                showState(STATE.ERROR);
            }
        }, 700);
    }

    _loadData();
});
