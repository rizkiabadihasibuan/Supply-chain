/**
 * placeholder-data.js
 * Centralized placeholder data for all charts and table.
 * Replace PLACEHOLDER_DATA values when connecting to APIs.
 *
 * API Endpoints (Future):
 *   - World Bank API  : https://api.worldbank.org/v2/country/{code}/indicator/
 *   - Exchange Rate   : https://v6.exchangerate-api.com/v6/{key}/latest/USD
 *   - Risk Engine     : /api/risk/score
 *   - REST Countries  : https://restcountries.com/v3.1/all
 */

const PLACEHOLDER_DATA = {

    // -------------------------------------------------------
    // GDP Trend (World Bank: NY.GDP.MKTP.CD)
    // -------------------------------------------------------
    gdpTrend: {
        labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
        datasets: [
            {
                label: 'Indonesia ($T)',
                data: [1.12, 1.06, 1.19, 1.32, 1.37, 1.42],
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                fill: true,
                tension: 0.35,
                pointRadius: 5,
                pointHoverRadius: 7,
            },
            {
                label: 'Amerika Serikat ($T)',
                data: [21.4, 20.9, 23.3, 25.5, 27.4, 28.2],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.06)',
                fill: true,
                tension: 0.35,
                pointRadius: 5,
                pointHoverRadius: 7,
            }
        ]
    },

    // -------------------------------------------------------
    // Inflation Trend (World Bank: FP.CPI.TOTL.ZG)
    // -------------------------------------------------------
    inflationTrend: {
        labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
        datasets: [
            {
                label: 'Indonesia (%)',
                data: [2.8, 2.0, 1.6, 4.2, 3.7, 2.8],
                backgroundColor: 'rgba(234, 88, 12, 0.75)',
                borderColor: '#EA580C',
                borderWidth: 1.5,
                borderRadius: 6,
                hoverBackgroundColor: '#EA580C',
            },
            {
                label: 'China (%)',
                data: [2.9, 2.5, 0.9, 2.0, 0.2, 1.8],
                backgroundColor: 'rgba(37, 99, 235, 0.5)',
                borderColor: '#2563EB',
                borderWidth: 1.5,
                borderRadius: 6,
                hoverBackgroundColor: '#2563EB',
            }
        ]
    },

    // -------------------------------------------------------
    // Currency Trend (Exchange Rate API: USD/IDR)
    // -------------------------------------------------------
    currencyTrend: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'USD/IDR',
                data: [15780, 15890, 15950, 16100, 16180, 16220, 16250],
                borderColor: '#0284C7',
                backgroundColor: 'rgba(2, 132, 199, 0.12)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 7,
                borderWidth: 2,
            }
        ]
    },

    // -------------------------------------------------------
    // Risk Trend (Risk Engine API)
    // -------------------------------------------------------
    riskTrend: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
        datasets: [
            {
                label: 'Risk Score',
                data: [2.1, 2.4, 2.6, 2.5, 2.7, 2.8, 2.8],
                borderColor: '#DC2626',
                backgroundColor: 'rgba(220, 38, 38, 0.08)',
                fill: true,
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
            }
        ]
    },

    // -------------------------------------------------------
    // Analytics Table Rows (REST Countries + World Bank)
    // -------------------------------------------------------
    tableRows: [
        {
            flag: '🇮🇩', name: 'Indonesia', region: 'asia',
            gdp: '$1.42T', gdpRaw: 1.42,
            inflation: '2.8%', inflationRaw: 2.8,
            currency: 'Rp 16.250', currencyCode: 'IDR',
            riskScore: 1.25, riskLevel: 'low',
            population: '279.1 Juta',
            lastUpdate: 'Baru saja',
        },
        {
            flag: '🇨🇳', name: 'China', region: 'asia',
            gdp: '$17.90T', gdpRaw: 17.90,
            inflation: '1.8%', inflationRaw: 1.8,
            currency: '¥ 7.24', currencyCode: 'CNY',
            riskScore: 4.25, riskLevel: 'high',
            population: '1.41 Miliar',
            lastUpdate: '5 Menit lalu',
        },
        {
            flag: '🇺🇸', name: 'Amerika Serikat', region: 'america',
            gdp: '$28.20T', gdpRaw: 28.20,
            inflation: '3.4%', inflationRaw: 3.4,
            currency: 'USD (Base)', currencyCode: 'USD',
            riskScore: 3.48, riskLevel: 'medium',
            population: '335.9 Juta',
            lastUpdate: '1 Jam lalu',
        },
        {
            flag: '🇳🇱', name: 'Belanda', region: 'europe',
            gdp: '$1.01T', gdpRaw: 1.01,
            inflation: '4.1%', inflationRaw: 4.1,
            currency: '€ 0.92', currencyCode: 'EUR',
            riskScore: 1.85, riskLevel: 'low',
            population: '17.9 Juta',
            lastUpdate: '2 Jam lalu',
        },
        {
            flag: '🇸🇩', name: 'Sudan', region: 'africa',
            gdp: '$0.05T', gdpRaw: 0.05,
            inflation: '75.0%', inflationRaw: 75.0,
            currency: 'SDG 601.5', currencyCode: 'SDG',
            riskScore: 4.80, riskLevel: 'high',
            population: '47.9 Juta',
            lastUpdate: 'Kemarin',
        },
        {
            flag: '🇸🇬', name: 'Singapura', region: 'asia',
            gdp: '$0.50T', gdpRaw: 0.50,
            inflation: '2.4%', inflationRaw: 2.4,
            currency: 'S$ 1.34', currencyCode: 'SGD',
            riskScore: 1.10, riskLevel: 'low',
            population: '5.9 Juta',
            lastUpdate: '30 Mnt lalu',
        },
    ]
};
