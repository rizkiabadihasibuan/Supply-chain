/**
 * ============================================================
 * ADMIN ARTICLE MANAGEMENT – Main Core JS
 * public/js/admin/articles/articles.js
 * ============================================================
 */

const ArticlesCore = (() => {
    'use strict';

    // Inisialisasi skeleton loading
    function initSkeletonLoader() {
        const skeleton = document.getElementById('articles-skeleton-container');
        const content = document.getElementById('articles-main-content');
        
        if (skeleton && content) {
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                content.classList.add('fade-in-up');
            }, 1000);
        }
    }

    // Refresh halaman
    function refreshData() {
        const skeleton = document.getElementById('articles-skeleton-container');
        const content = document.getElementById('articles-main-content');
        
        if (skeleton && content) {
            content.style.display = 'none';
            skeleton.style.display = 'block';
            
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                showToast('Daftar artikel berhasil diperbarui dari server!');
            }, 800);
        }
    }

    // Toast Alert Notifikasi Kustom
    function showToast(message) {
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.bottom = '24px';
        toast.style.right = '24px';
        toast.style.background = '#1E293B';
        toast.style.color = '#FFFFFF';
        toast.style.padding = '0.75rem 1.25rem';
        toast.style.borderRadius = '10px';
        toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        toast.style.zIndex = '9999';
        toast.style.fontSize = '0.875rem';
        toast.style.fontWeight = '500';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '0.5rem';
        toast.innerHTML = `<i class="bi bi-info-circle text-primary"></i> ${message}`;
        
        document.body.appendChild(toast);
        
        toast.animate([
            { opacity: 0, transform: 'translateY(10px)' },
            { opacity: 1, transform: 'translateY(0)' }
        ], { duration: 300, fill: 'forwards' });

        setTimeout(() => {
            toast.animate([
                { opacity: 1 },
                { opacity: 0 }
            ], { duration: 300, fill: 'forwards' }).onfinish = () => toast.remove();
        }, 3000);

        // Menampilkan Banner Alert Sukses Bootstrap (Success Alert)
        if (message.includes('berhasil') || message.includes('impor') || message.includes('Import') || message.includes('ditambahkan') || message.includes('diperbarui') || message.includes('dihapus')) {
            const successAlertContainer = document.getElementById('articles-success-alert-container');
            const successAlertText = document.getElementById('ports-success-alert-text'); // Fallback ke element ports jika di layout
            const localSuccessText = document.getElementById('articles-success-alert-text');
            const activeText = localSuccessText || successAlertText;
            const activeContainer = document.getElementById('articles-success-alert-container') || document.getElementById('ports-success-alert-container');

            if (activeContainer && activeText) {
                activeText.textContent = message;
                activeContainer.style.display = 'block';
                
                setTimeout(() => {
                    activeContainer.style.display = 'none';
                }, 5000);
            }
        }
    }

    // Simulasi Error
    function simulateError() {
        const content = document.getElementById('articles-main-content');
        const errorAlert = document.getElementById('articles-error-container');

        if (content && errorAlert) {
            content.style.display = 'none';
            errorAlert.style.display = 'block';
        }
    }

    function retryFromError() {
        const errorAlert = document.getElementById('articles-error-container');
        if (errorAlert) errorAlert.style.display = 'none';
        refreshData();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSkeletonLoader();
    });

    return { refreshData, showToast, simulateError, retryFromError };
})();
