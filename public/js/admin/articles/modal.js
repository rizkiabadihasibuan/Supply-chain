/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Modal JS
 * public/js/admin/articles/modal.js
 * ============================================================
 */

const ArticlesModal = (() => {
    'use strict';

    // Memuat detail artikel ke modal detail
    function showDetail(title, category, status, author, publishedAt, content, views, thumbnail) {
        document.getElementById('detail-article-title').textContent = title;
        document.getElementById('detail-article-category').textContent = category;
        document.getElementById('detail-article-author').textContent = author;
        document.getElementById('detail-article-published-at').textContent = publishedAt || '—';
        document.getElementById('detail-article-content-preview').textContent = content || 'Tidak ada isi.';
        document.getElementById('detail-article-views').textContent = views || '0';

        // Set status badge
        const statusBadge = document.getElementById('detail-article-status');
        statusBadge.className = 'badge';
        if (status === 'Published') statusBadge.classList.add('badge-status-published');
        else if (status === 'Draft') statusBadge.classList.add('badge-status-draft');
        else statusBadge.classList.add('badge-status-archived');
        statusBadge.textContent = status;

        // Set thumbnail preview
        const thumbnailEl = document.getElementById('detail-article-thumbnail');
        if (thumbnailEl) {
            thumbnailEl.src = thumbnail || 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=150&auto=format&fit=crop';
        }

        // Set Preview Card info
        document.getElementById('preview-card-title').textContent = title;
        document.getElementById('preview-card-category').textContent = category;
        document.getElementById('preview-card-author').textContent = author;
        document.getElementById('preview-card-date').textContent = publishedAt || 'Belum dipublikasikan';
        document.getElementById('preview-card-summary').textContent = content.substring(0, 100) + '...';
        
        const previewImg = document.getElementById('preview-card-img');
        if (previewImg) {
            previewImg.src = thumbnail || 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=300&auto=format&fit=crop';
        }
    }

    // Memuat data terpilih ke modal edit
    function showEdit(title, category, status, content, publishedAt, thumbnail) {
        document.getElementById('edit-article-title').value = title;
        document.getElementById('edit-article-slug').value = generateSlugString(title);
        document.getElementById('edit-article-category').value = category;
        document.getElementById('edit-article-status').value = status;
        document.getElementById('edit-article-content').value = content || '';
        document.getElementById('edit-article-published-date').value = publishedAt === '—' ? '' : publishedAt;
        
        // Reset preview edit thumbnail
        const previewEdit = document.getElementById('edit-thumbnail-preview');
        if (previewEdit) {
            previewEdit.src = thumbnail || 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=150&auto=format&fit=crop';
            previewEdit.style.display = 'block';
        }
    }

    // Generate slug string helper
    function generateSlugString(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Ganti spasi dengan -
            .replace(/[^\w\-]+/g, '')       // Hapus karakter non-word
            .replace(/\-\-+/g, '-')         // Ganti ganda -- dengan -
            .replace(/^-+/, '')             // Trim - di depan
            .replace(/-+$/, '');            // Trim - di belakang
    }

    // Inisialisasi otomatisasi slug
    function initSlugAutogen() {
        const createTitle = document.getElementById('create-article-title');
        const createSlug = document.getElementById('create-article-slug');
        if (createTitle && createSlug) {
            createTitle.addEventListener('input', function() {
                createSlug.value = generateSlugString(this.value);
            });
        }

        const editTitle = document.getElementById('edit-article-title');
        const editSlug = document.getElementById('edit-article-slug');
        if (editTitle && editSlug) {
            editTitle.addEventListener('input', function() {
                editSlug.value = generateSlugString(this.value);
            });
        }
    }

    // Penanganan upload preview gambar
    function handleThumbnailSelect(event, type) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewEl = document.getElementById(`${type}-thumbnail-preview`);
                if (previewEl) {
                    previewEl.src = e.target.result;
                    previewEl.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Simulasi simpan data tambah artikel
    function saveCreate(statusOverride = null) {
        const form = document.getElementById('form-create-article');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const statusSelect = document.getElementById('create-article-status');
        const activeStatus = statusOverride || statusSelect.value;

        const btn = document.getElementById('btn-save-create-article');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menyimpan...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Simpan';
            }
            const modalEl = document.getElementById('articleCreateModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');

            const previewImg = document.getElementById('create-thumbnail-preview');
            if (previewImg) previewImg.style.display = 'none';

            if (activeStatus === 'Published') {
                ArticlesCore.showToast('Artikel berhasil dipublikasikan.');
            } else {
                ArticlesCore.showToast('Artikel berhasil dibuat.');
            }
        }, 1000);
    }

    // Simulasi update data edit artikel
    function saveEdit() {
        const form = document.getElementById('form-edit-article');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const btn = document.getElementById('btn-save-edit-article');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Mengubah...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Update';
            }
            const modalEl = document.getElementById('articleEditModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');
            ArticlesCore.showToast('Artikel berhasil diperbarui.');
        }, 1000);
    }

    // Simulasi delete
    function confirmDelete() {
        const btn = document.getElementById('btn-save-delete-article');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menghapus...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Hapus';
            }
            const modalEl = document.getElementById('articleDeleteModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            ArticlesCore.showToast('Artikel berhasil dihapus.');
        }, 800);
    }

    // Simulasi duplikat
    function duplicateArticle(title) {
        ArticlesCore.showToast(`Menduplikasi artikel "${title}" (Simulasi)...`);
        setTimeout(() => {
            ArticlesCore.showToast('Artikel berhasil diduplikasi.');
        }, 1000);
    }

    // Simulasi archive
    function archiveArticle(title) {
        ArticlesCore.showToast(`Mengarsipkan artikel "${title}" (Simulasi)...`);
        setTimeout(() => {
            ArticlesCore.showToast('Artikel berhasil diarsipkan.');
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSlugAutogen();
    });

    return { showDetail, showEdit, saveCreate, saveEdit, confirmDelete, duplicateArticle, archiveArticle, handleThumbnailSelect };
})();
