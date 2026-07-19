/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Editor JS
 * resources/js/admin/articles/editor.js
 * ============================================================
 */

const ArticlesEditor = (() => {
    'use strict';

    function triggerFormat(actionName) {
        if (typeof ArticlesCore !== 'undefined') {
            ArticlesCore.showToast(`Memformat teks dengan gaya: ${actionName.toUpperCase()} (Simulasi)`);
        }
    }

    return { triggerFormat };
})();
