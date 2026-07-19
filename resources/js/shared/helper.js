/**
 * HELPER – Global JS Utility Functions
 * Digunakan oleh semua module Admin dan User
 */

const Helper = {
    formatNumber: (n) => new Intl.NumberFormat('id-ID').format(n),
    formatDate:   (d) => new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' }),
    truncate:     (s, n = 100) => s.length > n ? s.slice(0, n) + '...' : s,
    debounce:     (fn, ms = 300) => { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); }; },
};
window.Helper = Helper;
