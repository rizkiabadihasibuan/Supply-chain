/**
 * favorite-modal.js
 * Add-to-Favorite modal logic.
 * Search within ALL_COUNTRIES list and simulate adding a favorite.
 * Replace addFavorite() with real API POST /api/favorites when backend is ready.
 */

const FavoriteModal = (() => {
    let _modal = null;
    let _filteredCountries = [...ALL_COUNTRIES];

    function _el(id) { return document.getElementById(id); }

    function init() {
        const modalEl = _el('addFavoriteModal');
        if (!modalEl) return;

        _modal = new bootstrap.Modal(modalEl);

        // Populate dropdown on init
        _populateDropdown(ALL_COUNTRIES);

        // Search within modal
        const searchEl = _el('modalCountrySearch');
        if (searchEl) {
            searchEl.addEventListener('input', () => {
                const q = searchEl.value.trim().toLowerCase();
                _filteredCountries = ALL_COUNTRIES.filter(c =>
                    c.name.toLowerCase().includes(q) || c.code.toLowerCase().includes(q)
                );
                _populateDropdown(_filteredCountries);
            });
        }

        // Save button
        const saveBtn = _el('btnModalSave');
        if (saveBtn) saveBtn.addEventListener('click', _handleSave);
    }

    function open() {
        if (_modal) _modal.show();
    }

    function _populateDropdown(countries) {
        const sel = _el('modalCountrySelect');
        if (!sel) return;

        sel.innerHTML = '<option value="">-- Pilih Negara --</option>';
        countries.forEach(c => {
            const opt = document.createElement('option');
            opt.value       = c.code;
            opt.textContent = `${c.flag} ${c.name} (${c.region})`;
            sel.appendChild(opt);
        });
    }

    function _handleSave() {
        const sel = _el('modalCountrySelect');
        const code = sel?.value;

        if (!code) {
            _el('modalValidation').style.display = '';
            return;
        }
        _el('modalValidation').style.display = 'none';

        // TODO: Replace with real API call
        // await fetch('/api/favorites', { method:'POST', body: JSON.stringify({ code }) })

        // Simulate: show toast and close
        if (_modal) _modal.hide();

        const toastEl = _el('favToast');
        const toastMsg = _el('favToastMsg');
        const country = ALL_COUNTRIES.find(c => c.code === code);
        if (toastMsg && country) toastMsg.textContent = `${country.flag} ${country.name} ditambahkan ke Favorite!`;
        if (toastEl) {
            const t = new bootstrap.Toast(toastEl, { delay: 3500 });
            t.show();
        }

        // Reset modal
        if (sel) sel.value = '';
        const searchEl = _el('modalCountrySearch');
        if (searchEl) searchEl.value = '';
        _populateDropdown(ALL_COUNTRIES);
    }

    return { init, open };
})();
