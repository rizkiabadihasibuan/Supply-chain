/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Export JS
 * public/js/admin/articles/export.js
 * ============================================================
 */

const ArticlesExport = (() => {
    'use strict';

    function triggerExport(format) {
        if (typeof ArticlesCore !== 'undefined') {
            ArticlesCore.showToast(`Mengekspor artikel ke format ${format.toUpperCase()} (Simulasi)...`);
        }
    }

    return { triggerExport };
})();
