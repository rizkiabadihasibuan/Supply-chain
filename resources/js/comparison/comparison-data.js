/**
 * comparison-data.js
 * Centralized placeholder data for Country Comparison Engine.
 * Replace COUNTRY_DATA entries with real API responses.
 *
 * Future APIs:
 *   - REST Countries API  : https://restcountries.com/v3.1/alpha/{code}
 *   - World Bank API      : https://api.worldbank.org/v2/country/{code}/indicator/
 *   - Exchange Rate API   : https://v6.exchangerate-api.com/v6/{key}/latest/USD
 *   - Open-Meteo API      : https://api.open-meteo.com/v1/forecast
 *   - Risk Engine API     : /api/risk/score/{code}
 */

const COUNTRY_DATA = {
    ID: {
        code: 'ID', flag: '🇮🇩', name: 'Indonesia',
        region: 'Asia Tenggara', capital: 'Jakarta',
        currency: 'IDR', currencyRate: 'Rp 16.250 / USD',
        gdp: 1.42, gdpLabel: '$1.42T',
        inflation: 2.8, inflationLabel: '2.8%',
        population: 279.1, populationLabel: '279.1 Juta',
        weather: '32°C', weatherLabel: 'Cerah ☀️',
        riskScore: 1.25, riskLevel: 'low',
        export: '$258B', import: '$215B',
        growth: '+5.05%',
    },
    CN: {
        code: 'CN', flag: '🇨🇳', name: 'China',
        region: 'Asia Timur', capital: 'Beijing',
        currency: 'CNY', currencyRate: '¥ 7.24 / USD',
        gdp: 17.90, gdpLabel: '$17.90T',
        inflation: 1.8, inflationLabel: '1.8%',
        population: 1411, populationLabel: '1.41 Miliar',
        weather: '28°C', weatherLabel: 'Berawan 🌤',
        riskScore: 4.25, riskLevel: 'high',
        export: '$3.38T', import: '$2.68T',
        growth: '+5.2%',
    },
    US: {
        code: 'US', flag: '🇺🇸', name: 'Amerika Serikat',
        region: 'Amerika Utara', capital: 'Washington D.C.',
        currency: 'USD', currencyRate: 'Base Currency',
        gdp: 28.20, gdpLabel: '$28.20T',
        inflation: 3.4, inflationLabel: '3.4%',
        population: 335.9, populationLabel: '335.9 Juta',
        weather: '22°C', weatherLabel: 'Sejuk 🌥',
        riskScore: 3.48, riskLevel: 'medium',
        export: '$2.06T', import: '$3.16T',
        growth: '+2.5%',
    },
    NL: {
        code: 'NL', flag: '🇳🇱', name: 'Belanda',
        region: 'Eropa Barat', capital: 'Amsterdam',
        currency: 'EUR', currencyRate: '€ 0.92 / USD',
        gdp: 1.01, gdpLabel: '$1.01T',
        inflation: 4.1, inflationLabel: '4.1%',
        population: 17.9, populationLabel: '17.9 Juta',
        weather: '18°C', weatherLabel: 'Hujan 🌧',
        riskScore: 1.85, riskLevel: 'low',
        export: '$936B', import: '$760B',
        growth: '+1.8%',
    },
    SD: {
        code: 'SD', flag: '🇸🇩', name: 'Sudan',
        region: 'Afrika Timur Laut', capital: 'Khartoum',
        currency: 'SDG', currencyRate: 'SDG 601.5 / USD',
        gdp: 0.05, gdpLabel: '$0.05T',
        inflation: 75.0, inflationLabel: '75.0%',
        population: 47.9, populationLabel: '47.9 Juta',
        weather: '41°C', weatherLabel: 'Sangat Panas 🌡',
        riskScore: 4.80, riskLevel: 'high',
        export: '$3.1B', import: '$8.9B',
        growth: '-18.3%',
    },
    SG: {
        code: 'SG', flag: '🇸🇬', name: 'Singapura',
        region: 'Asia Tenggara', capital: 'Singapura',
        currency: 'SGD', currencyRate: 'S$ 1.34 / USD',
        gdp: 0.50, gdpLabel: '$0.50T',
        inflation: 2.4, inflationLabel: '2.4%',
        population: 5.9, populationLabel: '5.9 Juta',
        weather: '30°C', weatherLabel: 'Panas Lembab 🌫',
        riskScore: 1.10, riskLevel: 'low',
        export: '$500B', import: '$380B',
        growth: '+2.9%',
    },
    JP: {
        code: 'JP', flag: '🇯🇵', name: 'Jepang',
        region: 'Asia Timur', capital: 'Tokyo',
        currency: 'JPY', currencyRate: '¥ 155.2 / USD',
        gdp: 4.23, gdpLabel: '$4.23T',
        inflation: 2.1, inflationLabel: '2.1%',
        population: 124.5, populationLabel: '124.5 Juta',
        weather: '25°C', weatherLabel: 'Cerah Berawan ⛅',
        riskScore: 2.10, riskLevel: 'medium',
        export: '$702B', import: '$779B',
        growth: '+1.9%',
    },
    DE: {
        code: 'DE', flag: '🇩🇪', name: 'Jerman',
        region: 'Eropa Barat', capital: 'Berlin',
        currency: 'EUR', currencyRate: '€ 0.92 / USD',
        gdp: 4.08, gdpLabel: '$4.08T',
        inflation: 2.9, inflationLabel: '2.9%',
        population: 84.3, populationLabel: '84.3 Juta',
        weather: '16°C', weatherLabel: 'Mendung 🌥',
        riskScore: 1.95, riskLevel: 'low',
        export: '$1.54T', import: '$1.27T',
        growth: '+0.2%',
    },
};

// Radar chart indicator keys & labels
const RADAR_INDICATORS = [
    { key: 'gdp',       label: 'GDP',        maxVal: 30    },
    { key: 'inflation', label: 'Inflasi',    maxVal: 80    },
    { key: 'population',label: 'Populasi',   maxVal: 1500  },
    { key: 'riskScore', label: 'Risk Score', maxVal: 5     },
];
