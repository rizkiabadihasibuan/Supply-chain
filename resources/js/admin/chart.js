/**
 * ============================================================
 * ADMIN DASHBOARD – Chart.js Config
 * resources/js/admin/chart.js
 * ============================================================
 */

const AdminChart = (() => {
    'use strict';

    let userGrowthChartInstance = null;
    let riskDistChartInstance = null;
    let articleStatsChartInstance = null;
    let watchlistActChartInstance = null;

    // Helper untuk membuat gradien area chart
    function getGradient(ctx, colorStart, colorEnd) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    }

    function initCharts() {
        // --- 1. User Growth (Line Chart) ---
        const userGrowthCtx = document.getElementById('userGrowthChart')?.getContext('2d');
        if (userGrowthCtx) {
            userGrowthChartInstance = new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Pertumbuhan User',
                        data: [45, 78, 112, 145, 189, 210, 245],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#E2E8F0' }, ticks: { color: '#64748B' } },
                        x: { grid: { display: false }, ticks: { color: '#64748B' } }
                    }
                }
            });
        }

        // --- 2. Risk Distribution (Doughnut Chart) ---
        const riskDistCtx = document.getElementById('riskDistChart')?.getContext('2d');
        if (riskDistCtx) {
            riskDistChartInstance = new Chart(riskDistCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Low Risk', 'Medium Risk', 'High Risk'],
                    datasets: [{
                        data: [55, 30, 15],
                        backgroundColor: ['#22C55E', '#F59E0B', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    cutout: '70%'
                }
            });
        }

        // --- 3. Article Statistics (Bar Chart) ---
        const articleStatsCtx = document.getElementById('articleStatsChart')?.getContext('2d');
        if (articleStatsCtx) {
            articleStatsChartInstance = new Chart(articleStatsCtx, {
                type: 'bar',
                data: {
                    labels: ['Draft', 'Published', 'Archived'],
                    datasets: [{
                        label: 'Artikel',
                        data: [45, 230, 40],
                        backgroundColor: ['#94A3B8', '#2563EB', '#64748B'],
                        borderRadius: 6,
                        maxBarThickness: 32
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#E2E8F0' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // --- 4. Watchlist Activity (Area Chart) ---
        const watchlistActCtx = document.getElementById('watchlistActChart')?.getContext('2d');
        if (watchlistActCtx) {
            const gradient = getGradient(watchlistActCtx, 'rgba(37, 99, 235, 0.25)', 'rgba(37, 99, 235, 0)');
            watchlistActChartInstance = new Chart(watchlistActCtx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Aktivitas',
                        data: [15, 32, 45, 28, 56, 75, 92],
                        borderColor: '#2563EB',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#E2E8F0' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    function destroyCharts() {
        if (userGrowthChartInstance) userGrowthChartInstance.destroy();
        if (riskDistChartInstance) riskDistChartInstance.destroy();
        if (articleStatsChartInstance) articleStatsChartInstance.destroy();
        if (watchlistActChartInstance) watchlistActChartInstance.destroy();
    }

    return { initCharts, destroyCharts };
})();
