/* Custom JS for Data Visualization Dashboard */

let gdpChart, inflationChart, currencyChart, riskChart;

// Export files placeholder triggers
function exportData(format) {
    alert(`Mengekspor data ke format ${format.toUpperCase()} (Simulasi)...`);
}

function triggerChartsSync() {
    const btn = document.getElementById('btn-refresh-charts');
    const refreshIcon = btn.querySelector('.bi-arrow-clockwise');
    
    // Add spin class
    if (refreshIcon) refreshIcon.classList.add('animate-spin');
    btn.disabled = true;

    setTimeout(() => {
        if (refreshIcon) refreshIcon.classList.remove('animate-spin');
        btn.disabled = false;
        
        // Randomize chart data points simulation
        if (gdpChart) {
            gdpChart.data.datasets[0].data = [24.7, 25.0, 25.2, 25.4].map(x => x + (Math.random() - 0.5) * 0.3);
            gdpChart.update();
        }
        if (inflationChart) {
            inflationChart.data.datasets[0].data = [2.8, 1.8, 3.4, 4.1].map(x => Math.max(0.5, x + (Math.random() - 0.5) * 0.6));
            inflationChart.update();
        }
        if (currencyChart) {
            currencyChart.data.datasets[0].data = [16210, 16225, 16240, 16250, 16245].map(x => x + Math.round((Math.random() - 0.5) * 60));
            currencyChart.update();
        }
        if (riskChart) {
            riskChart.data.datasets[0].data = [2.6, 2.75, 2.8].map(x => x + (Math.random() - 0.5) * 0.25);
            riskChart.update();
        }

        alert('Data satelit makroekonomi berhasil diperbarui dan diselaraskan!');
    }, 1200);
}

function initializeAnalyticsCharts() {
    // Destroy previous charts if exist to prevent memory leaks
    if (gdpChart) gdpChart.destroy();
    if (inflationChart) inflationChart.destroy();
    if (currencyChart) currencyChart.destroy();
    if (riskChart) riskChart.destroy();

    const canvasGdp = document.getElementById('gdpChartCanvas');
    const canvasInflation = document.getElementById('inflationChartCanvas');
    const canvasCurrency = document.getElementById('currencyChartCanvas');
    const canvasRisk = document.getElementById('riskChartCanvas');

    if (!canvasGdp || !canvasInflation || !canvasCurrency || !canvasRisk) return;

    const ctxGdp = canvasGdp.getContext('2d');
    const ctxInflation = canvasInflation.getContext('2d');
    const ctxCurrency = canvasCurrency.getContext('2d');
    const ctxRisk = canvasRisk.getContext('2d');

    // Chart 1: GDP Trend (Line)
    gdpChart = new Chart(ctxGdp, {
        type: 'line',
        data: {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'GDP Amerika Serikat ($T)',
                data: [24.8, 25.1, 25.3, 25.4],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: true, position: 'top' },
                tooltip: { enabled: true }
            }
        }
    });

    // Chart 2: Inflation Trend (Bar)
    inflationChart = new Chart(ctxInflation, {
        type: 'bar',
        data: {
            labels: ['ID', 'CN', 'US', 'NL'],
            datasets: [{
                label: 'Inflasi (%)',
                data: [2.8, 1.8, 3.4, 4.1],
                backgroundColor: ['#22C55E', '#22C55E', '#F59E0B', '#F59E0B'],
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: true, position: 'top' },
                tooltip: { enabled: true }
            }
        }
    });

    // Chart 3: Currency Trend (Area)
    currencyChart = new Chart(ctxCurrency, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
            datasets: [{
                label: 'USD/IDR',
                data: [16210, 16225, 16240, 16250, 16245],
                borderColor: '#06B6D4',
                backgroundColor: 'rgba(6, 182, 212, 0.15)',
                fill: true,
                tension: 0.2,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: true, position: 'top' },
                tooltip: { enabled: true }
            }
        }
    });

    // Chart 4: Risk Trend (Line)
    riskChart = new Chart(ctxRisk, {
        type: 'line',
        data: {
            labels: ['Mei', 'Jun', 'Jul'],
            datasets: [{
                label: 'Risk Score',
                data: [2.6, 2.75, 2.8],
                borderColor: '#EF4444',
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: true, position: 'top' },
                tooltip: { enabled: true }
            }
        }
    });
}

function toggleChartsDisplay() {
    const val = document.getElementById('filter-chart-category').value;
    const gdpWrap = document.getElementById('chart-wrap-gdp');
    const infWrap = document.getElementById('chart-wrap-inflation');
    const curWrap = document.getElementById('chart-wrap-currency');
    const riskWrap = document.getElementById('chart-wrap-risk');

    gdpWrap.style.display = 'block';
    infWrap.style.display = 'block';
    curWrap.style.display = 'block';
    riskWrap.style.display = 'block';

    if (val === 'gdp') {
        infWrap.style.display = 'none';
        curWrap.style.display = 'none';
        riskWrap.style.display = 'none';
    } else if (val === 'inflation') {
        gdpWrap.style.display = 'none';
        curWrap.style.display = 'none';
        riskWrap.style.display = 'none';
    } else if (val === 'currency') {
        gdpWrap.style.display = 'none';
        infWrap.style.display = 'none';
        riskWrap.style.display = 'none';
    } else if (val === 'risk') {
        gdpWrap.style.display = 'none';
        infWrap.style.display = 'none';
        curWrap.style.display = 'none';
    }
}

function applyChartFilters() {
    const query = document.getElementById('search-chart-country').value.toLowerCase();
    const region = document.getElementById('filter-chart-region').value;
    const sortVal = document.getElementById('filter-chart-sort').value;

    const tableBody = document.getElementById('macro-table-body');
    const rows = Array.from(document.querySelectorAll('.macro-table-row'));
    
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const rowRegion = row.getAttribute('data-region');

        const matchesSearch = name.includes(query);
        const matchesRegion = (region === 'all' || rowRegion === region);

        if (matchesSearch && matchesRegion) {
            row.style.display = 'table-row';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Sorting
    if (visibleCount > 0) {
        rows.sort((a, b) => {
            if (sortVal === 'name') {
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            } else if (sortVal === 'gdp-desc') {
                return parseFloat(b.getAttribute('data-gdp')) - parseFloat(a.getAttribute('data-gdp'));
            } else if (sortVal === 'inflation-desc') {
                return parseFloat(b.getAttribute('data-inflation')) - parseFloat(a.getAttribute('data-inflation'));
            } else if (sortVal === 'risk-desc') {
                return parseFloat(b.getAttribute('data-risk')) - parseFloat(a.getAttribute('data-risk'));
            }
            return 0;
        });
        rows.forEach(row => tableBody.appendChild(row));
    }

    const grid = document.getElementById('main-content-grid');
    const emptyState = document.getElementById('empty-state-container');
    const errorState = document.getElementById('error-state-container');

    errorState.style.display = 'none';

    if (visibleCount === 0) {
        grid.style.display = 'none';
        emptyState.style.display = 'flex';
    } else {
        grid.style.display = 'flex';
        emptyState.style.display = 'none';
    }
}

function simulateSkeletonLoading() {
    document.getElementById('main-content-grid').style.display = 'none';
    document.getElementById('empty-state-container').style.display = 'none';
    document.getElementById('error-state-container').style.display = 'none';
    document.getElementById('skeleton-container').style.display = 'block';

    setTimeout(() => {
        document.getElementById('skeleton-container').style.display = 'none';
        document.getElementById('main-content-grid').style.display = 'flex';
        applyChartFilters();
        initializeAnalyticsCharts();
    }, 800);
}

function simulateEmptyState() {
    document.getElementById('search-chart-country').value = 'NegaraKhayalan';
    applyChartFilters();
}

function simulateErrorState() {
    document.getElementById('main-content-grid').style.display = 'none';
    document.getElementById('empty-state-container').style.display = 'none';
    document.getElementById('skeleton-container').style.display = 'none';
    document.getElementById('error-state-container').style.display = 'flex';
}

function retryFromError() {
    simulateSkeletonLoading();
}

function resetFilters() {
    document.getElementById('search-chart-country').value = '';
    document.getElementById('filter-chart-region').value = 'all';
    document.getElementById('filter-chart-year').value = '2026';
    document.getElementById('filter-chart-category').value = 'all';
    document.getElementById('filter-chart-sort').value = 'name';
    toggleChartsDisplay();
    applyChartFilters();
}
