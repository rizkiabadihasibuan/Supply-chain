/**
 * favorite-data.js
 * Centralized placeholder data for Favorite Monitoring List.
 *
 * FUTURE API ENDPOINTS:
 *   - REST Countries   : https://restcountries.com/v3.1/alpha/{code}
 *   - World Bank API   : https://api.worldbank.org/v2/country/{code}/indicator/
 *   - Exchange Rate    : https://v6.exchangerate-api.com/v6/{key}/latest/USD
 *   - Open-Meteo       : https://api.open-meteo.com/v1/forecast?latitude=...
 *   - Risk Engine      : /api/risk/score/{code}
 *
 * Replace FAVORITE_DATA array with API responses when ready.
 */

const FAVORITE_DATA = [
    {
        code: 'ID', flag: '🇮🇩', name: 'Indonesia',
        region: 'Asia Tenggara', capital: 'Jakarta',
        currency: 'IDR', currencyRate: 'Rp 16.250',
        gdp: '$1.42T', gdpRaw: 1.42,
        inflation: '2.8%', inflationRaw: 2.8,
        weather: '32°C ☀️',
        riskScore: 1.25, riskLevel: 'low',
        population: '279.1 Juta',
        export: '$258B', import_val: '$215B',
        lastUpdate: 'Baru saja',
    },
    {
        code: 'US', flag: '🇺🇸', name: 'Amerika Serikat',
        region: 'Amerika Utara', capital: 'Washington D.C.',
        currency: 'USD', currencyRate: 'Base Currency',
        gdp: '$28.20T', gdpRaw: 28.20,
        inflation: '3.4%', inflationRaw: 3.4,
        weather: '22°C 🌥',
        riskScore: 3.48, riskLevel: 'medium',
        population: '335.9 Juta',
        export: '$2.06T', import_val: '$3.16T',
        lastUpdate: '1 Jam lalu',
    },
    {
        code: 'SG', flag: '🇸🇬', name: 'Singapura',
        region: 'Asia Tenggara', capital: 'Singapura',
        currency: 'SGD', currencyRate: 'S$ 1.34',
        gdp: '$0.50T', gdpRaw: 0.50,
        inflation: '2.4%', inflationRaw: 2.4,
        weather: '30°C 🌫',
        riskScore: 1.10, riskLevel: 'low',
        population: '5.9 Juta',
        export: '$500B', import_val: '$380B',
        lastUpdate: '30 Mnt lalu',
    },
    {
        code: 'CN', flag: '🇨🇳', name: 'China',
        region: 'Asia Timur', capital: 'Beijing',
        currency: 'CNY', currencyRate: '¥ 7.24',
        gdp: '$17.90T', gdpRaw: 17.90,
        inflation: '1.8%', inflationRaw: 1.8,
        weather: '28°C 🌤',
        riskScore: 4.25, riskLevel: 'high',
        population: '1.41 Miliar',
        export: '$3.38T', import_val: '$2.68T',
        lastUpdate: '5 Mnt lalu',
    },
    {
        code: 'DE', flag: '🇩🇪', name: 'Jerman',
        region: 'Eropa Barat', capital: 'Berlin',
        currency: 'EUR', currencyRate: '€ 0.92',
        gdp: '$4.08T', gdpRaw: 4.08,
        inflation: '2.9%', inflationRaw: 2.9,
        weather: '16°C 🌥',
        riskScore: 1.95, riskLevel: 'low',
        population: '84.3 Juta',
        export: '$1.54T', import_val: '$1.27T',
        lastUpdate: '2 Jam lalu',
    },
];

/**
 * ALL_COUNTRIES – available for add-to-favorite modal dropdown.
 * Replace with REST Countries API response.
 */
const ALL_COUNTRIES = [
    { code:'ID', flag:'🇮🇩', name:'Indonesia',         region:'Asia Tenggara' },
    { code:'CN', flag:'🇨🇳', name:'China',              region:'Asia Timur'    },
    { code:'US', flag:'🇺🇸', name:'Amerika Serikat',    region:'Amerika Utara' },
    { code:'NL', flag:'🇳🇱', name:'Belanda',            region:'Eropa Barat'   },
    { code:'SD', flag:'🇸🇩', name:'Sudan',              region:'Afrika'        },
    { code:'SG', flag:'🇸🇬', name:'Singapura',          region:'Asia Tenggara' },
    { code:'JP', flag:'🇯🇵', name:'Jepang',             region:'Asia Timur'    },
    { code:'DE', flag:'🇩🇪', name:'Jerman',             region:'Eropa Barat'   },
    { code:'AU', flag:'🇦🇺', name:'Australia',          region:'Oceania'       },
    { code:'BR', flag:'🇧🇷', name:'Brasil',             region:'Amerika Selatan'},
    { code:'IN', flag:'🇮🇳', name:'India',              region:'Asia Selatan'  },
    { code:'ZA', flag:'🇿🇦', name:'Afrika Selatan',     region:'Afrika'        },
];
