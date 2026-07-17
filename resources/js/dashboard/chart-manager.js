/**
 * chart-manager.js
 * Manages lazy initialization, destroy-before-render, and refresh for Chart.js instances.
 * Structure prepared for API integration.
 *
 * Usage:
 *   ChartManager.init()          // initialize all charts
 *   ChartManager.refresh(id)     // refresh single chart with animation
 *   ChartManager.refreshAll()    // refresh all
 *   ChartManager.updateData(id, newData) // swap in API data
 */

const ChartManager = (() => {
    const _instances = {};

    // -------------------------------------------------------
    // Common chart defaults
    // -------------------------------------------------------
    const DEFAULTS = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 600, easing: 'easeInOutQuart' },
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: { font: { size: 11 }, boxWidth: 12, padding: 14 }
            },
            tooltip: { enabled: true, mode: 'index', intersect: false }
        },
        scales: {
            x: {
                grid: { color: 'rgba(226,232,240,0.6)', drawBorder: false },
                ticks: { font: { size: 11 }, color: '#94A3B8' }
            },
            y: {
                grid: { color: 'rgba(226,232,240,0.6)', drawBorder: false },
                ticks: { font: { size: 11 }, color: '#94A3B8' }
            }
        }
    };

    // -------------------------------------------------------
    // Destroy before render (prevents memory leak)
    // -------------------------------------------------------
    function _destroy(id) {
        if (_instances[id]) {
            _instances[id].destroy();
            delete _instances[id];
        }
    }

    // -------------------------------------------------------
    // GDP Trend – Line Chart
    // -------------------------------------------------------
    function _initGdp() {
        const canvas = document.getElementById('chartGdp');
        if (!canvas) return;
        _destroy('gdp');

        const config = {
            type: 'line',
            data: PLACEHOLDER_DATA.gdpTrend,
            options: {
                ...DEFAULTS,
                plugins: {
                    ...DEFAULTS.plugins,
                    tooltip: {
                        ...DEFAULTS.plugins.tooltip,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: $${ctx.parsed.y.toFixed(2)}T`
                        }
                    }
                }
            }
        };
        _instances['gdp'] = new Chart(canvas.getContext('2d'), config);
    }

    // -------------------------------------------------------
    // Inflation Trend – Bar Chart
    // -------------------------------------------------------
    function _initInflation() {
        const canvas = document.getElementById('chartInflation');
        if (!canvas) return;
        _destroy('inflation');

        const config = {
            type: 'bar',
            data: PLACEHOLDER_DATA.inflationTrend,
            options: {
                ...DEFAULTS,
                plugins: {
                    ...DEFAULTS.plugins,
                    tooltip: {
                        ...DEFAULTS.plugins.tooltip,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}%`
                        }
                    }
                }
            }
        };
        _instances['inflation'] = new Chart(canvas.getContext('2d'), config);
    }

    // -------------------------------------------------------
    // Currency Trend – Area Chart
    // -------------------------------------------------------
    function _initCurrency() {
        const canvas = document.getElementById('chartCurrency');
        if (!canvas) return;
        _destroy('currency');

        const config = {
            type: 'line',
            data: PLACEHOLDER_DATA.currencyTrend,
            options: {
                ...DEFAULTS,
                plugins: {
                    ...DEFAULTS.plugins,
                    tooltip: {
                        ...DEFAULTS.plugins.tooltip,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString()}`
                        }
                    }
                }
            }
        };
        _instances['currency'] = new Chart(canvas.getContext('2d'), config);
    }

    // -------------------------------------------------------
    // Risk Trend – Line Chart (red)
    // -------------------------------------------------------
    function _initRisk() {
        const canvas = document.getElementById('chartRisk');
        if (!canvas) return;
        _destroy('risk');

        const config = {
            type: 'line',
            data: PLACEHOLDER_DATA.riskTrend,
            options: {
                ...DEFAULTS,
                scales: {
                    ...DEFAULTS.scales,
                    y: { ...DEFAULTS.scales.y, min: 0, max: 5 }
                },
                plugins: {
                    ...DEFAULTS.plugins,
                    tooltip: {
                        ...DEFAULTS.plugins.tooltip,
                        callbacks: {
                            label: ctx => ` Risk Score: ${ctx.parsed.y.toFixed(2)} / 5`
                        }
                    }
                }
            }
        };
        _instances['risk'] = new Chart(canvas.getContext('2d'), config);
    }

    // -------------------------------------------------------
    // Public API
    // -------------------------------------------------------
    function init() {
        _initGdp();
        _initInflation();
        _initCurrency();
        _initRisk();
    }

    /**
     * refresh(id) – spin the icon, wait 800ms, re-init
     * @param {string} id  'gdp' | 'inflation' | 'currency' | 'risk'
     * @param {string} btnId  ID of the refresh button element
     */
    function refresh(id, btnId) {
        const btn = document.getElementById(btnId);
        const icon = btn ? btn.querySelector('i') : null;
        if (icon) icon.classList.add('is-spinning');
        if (btn)  btn.disabled = true;

        setTimeout(() => {
            // Randomise data slightly to simulate live refresh
            const ds = PLACEHOLDER_DATA[`${id}Trend`]?.datasets;
            if (ds) {
                ds.forEach(d => {
                    d.data = d.data.map(v => parseFloat((v * (0.98 + Math.random() * 0.04)).toFixed(2)));
                });
            }

            switch (id) {
                case 'gdp':        _initGdp();        break;
                case 'inflation':  _initInflation();  break;
                case 'currency':   _initCurrency();   break;
                case 'risk':       _initRisk();       break;
            }

            if (icon) icon.classList.remove('is-spinning');
            if (btn)  btn.disabled = false;
        }, 800);
    }

    function refreshAll(btnId) {
        const btn = document.getElementById(btnId);
        const icon = btn ? btn.querySelector('i') : null;
        if (icon) icon.classList.add('is-spinning');
        if (btn)  btn.disabled = true;

        setTimeout(() => {
            init();
            if (icon) icon.classList.remove('is-spinning');
            if (btn)  btn.disabled = false;
        }, 1000);
    }

    /**
     * updateData(id, newChartJsData) – swap placeholder with real API data
     * Call this from visualization.js when API response arrives.
     */
    function updateData(id, newData) {
        if (!_instances[id]) return;
        _instances[id].data = newData;
        _instances[id].update();
    }

    return { init, refresh, refreshAll, updateData };
})();
