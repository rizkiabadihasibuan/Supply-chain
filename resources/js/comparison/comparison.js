/**
 * comparison.js
 * Main orchestrator for Country Comparison Engine.
 *
 * STATE MACHINE: idle → loading → ready | error
 *
 * FUTURE API INTEGRATION:
 *   Replace _fetchCountry(code) with real API calls:
 *     World Bank : /api/worldbank/country/{code}
 *     Risk Engine: /api/risk/score/{code}
 *     Forex API  : /api/forex/rate/{code}
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ── Elements ───────────────────────────────────── */
    const selA      = document.getElementById('selectCountryA');
    const selB      = document.getElementById('selectCountryB');
    const btnCmp    = document.getElementById('btnCompare');
    const btnReset  = document.getElementById('btnReset');

    const secEmpty  = document.getElementById('secEmpty');
    const secSkel   = document.getElementById('secSkeleton');
    const secResult = document.getElementById('secResult');

    /* ── State ──────────────────────────────────────── */
    let activeA = null;
    let activeB = null;

    /* ── Show / hide sections ───────────────────────── */
    function showSection(name) {
        [secEmpty, secSkel, secResult].forEach(el => {
            if (el) el.style.display = 'none';
        });
        const target = { empty: secEmpty, skeleton: secSkel, result: secResult }[name];
        if (target) target.style.display = '';
    }

    /* ── Render KPI cards ───────────────────────────── */
    function _renderKpi(country, side) {
        const s = side; // 'A' or 'B'
        const set = (id, val) => {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        };

        set(`kpi${s}Flag`,       country.flag);
        set(`kpi${s}Name`,       country.name);
        set(`kpi${s}Region`,     country.region);
        set(`kpi${s}Capital`,    country.capital);
        set(`kpi${s}Gdp`,        country.gdpLabel);
        set(`kpi${s}Inflation`,  country.inflationLabel);
        set(`kpi${s}Population`, country.populationLabel);
        set(`kpi${s}Currency`,   country.currencyRate);
        set(`kpi${s}Weather`,    country.weatherLabel);
        set(`kpi${s}Risk`,       `${country.riskScore} / 5`);
        set(`kpi${s}Export`,     country.export);
        set(`kpi${s}Import`,     country.import);
        set(`kpi${s}Growth`,     country.growth);

        // Risk badge colour
        const riskEl = document.getElementById(`kpi${s}RiskBadge`);
        if (riskEl) {
            const cls = { low: 'success', medium: 'warning', high: 'danger' }[country.riskLevel] ?? 'secondary';
            riskEl.className = `badge bg-${cls} bg-opacity-10 text-${cls} border border-${cls} border-opacity-25 rounded-pill`;
            riskEl.textContent = country.riskLevel.charAt(0).toUpperCase() + country.riskLevel.slice(1);
        }
    }

    /* ── Render comparison table ────────────────────── */
    function _renderTable(cA, cB) {
        const rows = [
            { indicator: 'GDP ($T)',    a: cA.gdp,        b: cB.gdp,        fmt: v => `$${v}T`,   unit: '' },
            { indicator: 'Inflasi (%)', a: cA.inflation,  b: cB.inflation,  fmt: v => `${v}%`,    unit: '' },
            { indicator: 'Populasi',   a: cA.population,  b: cB.population, fmt: v => `${v}Jt`,   unit: '' },
            { indicator: 'Risk Score', a: cA.riskScore,   b: cB.riskScore,  fmt: v => `${v}/5`,   unit: '' },
        ];

        const tbody = document.getElementById('cmpTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        rows.forEach(r => {
            const diff    = (r.a - r.b).toFixed(2);
            const diffAbs = Math.abs(diff);
            const diffCls = parseFloat(diff) === 0 ? 'text-secondary' :
                            parseFloat(diff) > 0    ? 'text-primary fw-semibold' : 'text-info fw-semibold';
            const diffPfx = parseFloat(diff) > 0 ? '+' : '';

            // "better" = lower is better for inflation, risk; higher is better for GDP, population
            const lowerBetter = ['Inflasi (%)', 'Risk Score'].includes(r.indicator);
            let statusBadge = '';
            if (parseFloat(diff) === 0) {
                statusBadge = `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill" style="font-size:.7rem;">Sama</span>`;
            } else if (lowerBetter) {
                const winner = parseFloat(diff) < 0 ? cA.name : cB.name;
                const cls    = parseFloat(diff) < 0 ? 'success' : 'danger';
                statusBadge  = `<span class="badge bg-${cls} bg-opacity-10 text-${cls} border border-${cls} border-opacity-25 rounded-pill" style="font-size:.7rem;">${winner} Lebih Baik</span>`;
            } else {
                const winner = parseFloat(diff) > 0 ? cA.name : cB.name;
                statusBadge  = `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill" style="font-size:.7rem;">${winner} Lebih Tinggi</span>`;
            }

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td class="fw-semibold text-dark">${r.indicator}</td>
                    <td class="text-primary fw-bold">${r.fmt(r.a)}</td>
                    <td style="color:#0891B2;" class="fw-bold">${r.fmt(r.b)}</td>
                    <td class="${diffCls}">${diffPfx}${diffAbs}</td>
                    <td>${statusBadge}</td>
                </tr>
            `);
        });
    }

    /* ── Render summary panel ───────────────────────── */
    function _renderSummary(cA, cB) {
        const container = document.getElementById('summaryList');
        if (!container) return;

        const items = [];

        // GDP
        if (cA.gdp > cB.gdp)  items.push({ icon: 'bi-cash-coin text-primary',   text: `<strong>${cA.name}</strong> memiliki GDP lebih tinggi ($${cA.gdp}T vs $${cB.gdp}T).` });
        else                   items.push({ icon: 'bi-cash-coin text-info',       text: `<strong>${cB.name}</strong> memiliki GDP lebih tinggi ($${cB.gdp}T vs $${cA.gdp}T).` });

        // Inflation (lower = better)
        if (cA.inflation < cB.inflation) items.push({ icon: 'bi-percent text-success',    text: `<strong>${cA.name}</strong> memiliki inflasi lebih rendah (${cA.inflation}% vs ${cB.inflation}%).` });
        else                             items.push({ icon: 'bi-percent text-warning',    text: `<strong>${cB.name}</strong> memiliki inflasi lebih rendah (${cB.inflation}% vs ${cA.inflation}%).` });

        // Risk Score (lower = better)
        if (cA.riskScore < cB.riskScore) items.push({ icon: 'bi-shield-check text-success',  text: `<strong>${cA.name}</strong> memiliki tingkat risiko lebih rendah (${cA.riskScore} vs ${cB.riskScore}).` });
        else                             items.push({ icon: 'bi-shield-exclamation text-danger', text: `<strong>${cB.name}</strong> memiliki tingkat risiko lebih rendah (${cB.riskScore} vs ${cA.riskScore}).` });

        // Population
        if (cA.population > cB.population) items.push({ icon: 'bi-people text-primary',    text: `<strong>${cA.name}</strong> memiliki populasi lebih besar (${cA.populationLabel} vs ${cB.populationLabel}).` });
        else                               items.push({ icon: 'bi-people text-info',        text: `<strong>${cB.name}</strong> memiliki populasi lebih besar (${cB.populationLabel} vs ${cA.populationLabel}).` });

        // Growth
        items.push({ icon: 'bi-graph-up-arrow text-success', text: `Pertumbuhan Ekonomi — ${cA.name}: <strong>${cA.growth}</strong> | ${cB.name}: <strong>${cB.growth}</strong>.` });

        container.innerHTML = items.map((item, i) => `
            <div class="summary-item d-flex align-items-start gap-3 cmp-fade" style="animation-delay:${i * .06}s;">
                <i class="bi ${item.icon} fs-5 flex-shrink-0 mt-0.5"></i>
                <span>${item.text}</span>
            </div>
        `).join('');
    }

    /* ── Fetch (placeholder — replace with real API) ── */
    function _fetchCountry(code) {
        // TODO: Replace with real API call, e.g.:
        // return fetch(`/api/countries/${code}`).then(r => r.json());
        return new Promise(resolve => {
            setTimeout(() => resolve(COUNTRY_DATA[code] ?? null), 400);
        });
    }

    /* ── Main compare action ────────────────────────── */
    async function doCompare() {
        const codeA = selA?.value;
        const codeB = selB?.value;

        if (!codeA || !codeB) {
            showSection('empty');
            return;
        }
        if (codeA === codeB) {
            alert('Silakan pilih dua negara yang berbeda untuk dibandingkan.');
            return;
        }

        showSection('skeleton');
        btnCmp.disabled = true;
        const icon = btnCmp.querySelector('i');
        if (icon) icon.classList.add('is-spinning');

        try {
            const [cA, cB] = await Promise.all([_fetchCountry(codeA), _fetchCountry(codeB)]);
            if (!cA || !cB) throw new Error('Data negara tidak ditemukan');

            activeA = cA;
            activeB = cB;

            _renderKpi(cA, 'A');
            _renderKpi(cB, 'B');
            _renderTable(cA, cB);
            _renderSummary(cA, cB);
            ComparisonCharts.init(cA, cB);

            // Update page labels
            const la = document.getElementById('labelCountryA');
            const lb = document.getElementById('labelCountryB');
            if (la) la.textContent = `${cA.flag} ${cA.name}`;
            if (lb) lb.textContent = `${cB.flag} ${cB.name}`;

            showSection('result');
        } catch (err) {
            console.error('[Comparison] Error:', err);
            showSection('empty');
        } finally {
            btnCmp.disabled = false;
            if (icon) icon.classList.remove('is-spinning');
        }
    }

    /* ── Reset ──────────────────────────────────────── */
    function doReset() {
        if (selA) selA.value = '';
        if (selB) selB.value = '';
        activeA = null;
        activeB = null;
        showSection('empty');
    }

    /* ── Event listeners ────────────────────────────── */
    if (btnCmp)   btnCmp.addEventListener('click',  doCompare);
    if (btnReset) btnReset.addEventListener('click', doReset);

    // Bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover focus', placement: 'top' });
    });

    // Auto-compare if both are pre-selected (demo mode)
    if (selA?.value && selB?.value) doCompare();
    else showSection('empty');
});
