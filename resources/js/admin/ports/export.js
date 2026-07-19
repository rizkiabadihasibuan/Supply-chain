/**
 * ============================================================
 * ADMIN PORT DATASET – Export JS
 * resources/js/admin/ports/export.js
 * ============================================================
 */

const PortsExport = (() => {
    'use strict';

    function triggerExport(format) {
        if (typeof PortsCore !== 'undefined') {
            PortsCore.showToast(`Mengekspor dataset pelabuhan ke format ${format.toUpperCase()} (Simulasi)...`);
        }
    }

    return { triggerExport };
})();
