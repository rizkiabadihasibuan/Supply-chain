/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Preview JS
 * resources/js/admin/articles/preview.js
 * ============================================================
 */

const ArticlesPreview = (() => {
    'use strict';

    function syncPreview(title, category, summary, author, date, thumbnail) {
        document.getElementById('preview-card-title').textContent = title || 'Judul Artikel';
        document.getElementById('preview-card-category').textContent = category || 'Kategori';
        document.getElementById('preview-card-summary').textContent = summary || 'Ringkasan artikel...';
        document.getElementById('preview-card-author').textContent = author || 'Penulis';
        document.getElementById('preview-card-date').textContent = date || 'Tanggal';
        
        const previewImg = document.getElementById('preview-card-img');
        if (previewImg) {
            previewImg.src = thumbnail || 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=300&auto=format&fit=crop';
        }
    }

    return { syncPreview };
})();
