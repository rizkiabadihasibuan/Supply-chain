/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Upload JS
 * public/js/admin/articles/upload.js
 * ============================================================
 */

const ArticlesUpload = (() => {
    'use strict';

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.add('bg-light');
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.remove('bg-light');
    }

    function handleDrop(e, type) {
        e.preventDefault();
        e.stopPropagation();
        e.currentTarget.classList.remove('bg-light');

        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            const input = document.getElementById(`${type}-thumbnail-file`);
            if (input) {
                input.files = files;
                const event = new Event('change');
                input.dispatchEvent(event);
            }
        }
    }

    return { handleDragOver, handleDragLeave, handleDrop };
})();
