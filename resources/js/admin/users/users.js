/**
 * ============================================================
 * ADMIN USER MANAGEMENT – Main Core JS
 * resources/js/admin/users/users.js
 * ============================================================
 */

const UsersCore = (() => {
    'use strict';

    // Inisialisasi skeleton loading
    function initSkeletonLoader() {
        const skeleton = document.getElementById('users-skeleton-container');
        const content = document.getElementById('users-main-content');
        
        if (skeleton && content) {
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                content.classList.add('fade-in-up');
            }, 1000);
        }
    }

    // Refresh halaman simulasi
    function refreshData() {
        const skeleton = document.getElementById('users-skeleton-container');
        const content = document.getElementById('users-main-content');
        
        if (skeleton && content) {
            content.style.display = 'none';
            skeleton.style.display = 'block';
            
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                showToast('Data pengguna berhasil diperbarui!');
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
    }

    // Simulasi Error
    function simulateError() {
        const content = document.getElementById('users-main-content');
        const errorAlert = document.getElementById('users-error-container');

        if (content && errorAlert) {
            content.style.display = 'none';
            errorAlert.style.display = 'block';
        }
    }

    function retryFromError() {
        const errorAlert = document.getElementById('users-error-container');
        if (errorAlert) errorAlert.style.display = 'none';
        refreshData();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSkeletonLoader();
    });

    return { refreshData, showToast, simulateError, retryFromError };
})();
