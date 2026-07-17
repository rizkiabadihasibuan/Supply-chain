/**
 * comparison-charts.js
 * ChartManager for Country Comparison Engine.
 * All 4 charts: Radar, GDP Bar, Inflation Bar, Risk Bar.
 *
 * Future: Call ComparisonCharts.update(codeA, codeB) after API fetch.
 */

const ComparisonCharts = (() => {
    const _instances = {};

    const BASE_OPTIONS = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 600, easing: 'easeInOutQuart' },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: { font: { size: 11 }, boxWidth: 12, padding: 14 }
            },
            tooltip: { enabled: true }
        }
    };

    const GRID = {
        color: 'rgba(226,232,240,0.6)',
        drawBorder: false
    };

    const TICKS = { font: { size: 11 }, color: '#94A3B8' };

    function _destroy(id) {
        if (_instances[id]) { _instances[id].destroy(); delete _instances[id]; }
    }

    /* ── Radar Chart ────────────────────────────────── */
    function initRadar(cA, cB) {
        _destroy('radar');
        const canvas = document.getElementById('chartRadar');
        if (!canvas || !cA || !cB) return;

        const normalize = (val, max) => Math.min(100, Math.round((val / max) * 100));

        const dataA = RADAR_INDICATORS.map(i => normalize(cA[i.key], i.maxVal));
        const dataB = RADAR_INDICATORS.map(i => normalize(cB[i.key], i.maxVal));

        _instances['radar'] = new Chart(canvas.getContext('2d'), {
            type: 'radar',
            data: {
                labels: RADAR_INDICATORS.map(i => i.label),
                datasets: [
                    {
                        label: cA.name,
                        data: dataA,
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37,99,235,0.15)',
                        borderWidth: 2,
                        pointBackgroundColor: '#2563EB',
                        pointRadius: 4,
                    },
                    {
                        label: cB.name,
                        data: dataB,
                        borderColor: '#0891B2',
                        backgroundColor: 'rgba(8,145,178,0.15)',
                        borderWidth: 2,
                        pointBackgroundColor: '#0891B2',
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                ...BASE_OPTIONS,
                scales: {
                    r: {
                        min: 0, max: 100,
                        ticks: { stepSize: 25, font: { size: 10 }, color: '#94A3B8' },
                        grid: { color: 'rgba(226,232,240,0.5)' },
                        pointLabels: { font: { size: 11 }, color: '#374151' },
                    }
                }
            }
        });
    }

    /* ── Bar Chart helper ───────────────────────────── */
    function _initBar(canvasId, instanceId, labelA, labelB, valA, valB, unit, colorA, colorB) {
        _destroy(instanceId);
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        _instances[instanceId] = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Perbandingan'],
                datasets: [
                    {
                        label: labelA,
                        data: [valA],
                        backgroundColor: colorA,
                        borderRadius: 6,
                    },
                    {
                        label: labelB,
                        data: [valB],
                        backgroundColor: colorB,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                ...BASE_OPTIONS,
                scales: {
                    x: { grid: GRID, ticks: TICKS },
                    y: {
                        grid: GRID,
                        ticks: { ...TICKS, callback: v => `${v}${unit}` }
                    }
                },
                plugins: {
                    ...BASE_OPTIONS.plugins,
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}${unit}`
                        }
                    }
                }
            }
        });
    }

    /* ── GDP Bar ────────────────────────────────────── */
    function initGdp(cA, cB) {
        if (!cA || !cB) return;
        _initBar('chartGdpBar', 'gdp', cA.name, cB.name, cA.gdp, cB.gdp, 'T', 'rgba(37,99,235,0.75)', 'rgba(8,145,178,0.75)');
    }

    /* ── Inflation Bar ──────────────────────────────── */
    function initInflation(cA, cB) {
        if (!cA || !cB) return;
        _initBar('chartInflationBar', 'inflation', cA.name, cB.name, cA.inflation, cB.inflation, '%', 'rgba(234,88,12,0.75)', 'rgba(217,119,6,0.75)');
    }

    /* ── Risk Bar ───────────────────────────────────── */
    function initRisk(cA, cB) {
        if (!cA || !cB) return;
        _initBar('chartRiskBar', 'risk', cA.name, cB.name, cA.riskScore, cB.riskScore, '/5', 'rgba(220,38,38,0.75)', 'rgba(239,68,68,0.5)');
    }

    /* ── Public API ─────────────────────────────────── */
    function init(cA, cB) {
        initRadar(cA, cB);
        initGdp(cA, cB);
        initInflation(cA, cB);
        initRisk(cA, cB);
    }

    /**
     * update(codeA, codeB) – swap in API data
     * Call this from comparison.js once real API data arrives.
     */
    function update(cA, cB) { init(cA, cB); }

    return { init, update };
})();
