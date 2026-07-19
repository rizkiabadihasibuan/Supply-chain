/**
 * ============================================================
 * ADMIN DASHBOARD – Main Core JS
 * resources/js/admin/dashboard.js
 * ============================================================
 */

const AdminDashboard = (() => {
    'use strict';

    // Inisialisasi transisi loading skeleton
    function initSkeletonLoader() {
        const skeleton = document.getElementById('admin-skeleton-container');
        const content = document.getElementById('admin-main-content');
        
        if (skeleton && content) {
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                content.classList.add('fade-in-up');
                
                // Inisialisasi Chart.js setelah konten utama dirender
                if (typeof AdminChart !== 'undefined') {
                    AdminChart.initCharts();
                }
            }, 1000);
        }
    }

    // Simulasi refresh seluruh statistik dashboard
    function refreshDashboard() {
        const content = document.getElementById('admin-main-content');
        const skeleton = document.getElementById('admin-skeleton-container');

        if (content && skeleton) {
            content.style.display = 'none';
            skeleton.style.display = 'block';
            
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'block';
                
                // Regenerasi data chart acak
                if (typeof AdminChart !== 'undefined') {
                    AdminChart.destroyCharts();
                    AdminChart.initCharts();
                }
                
                showToast('Data berhasil diperbarui secara real-time!');
            }, 800);
        }
    }

    // Menampilkan toast notifikasi kustom
    function showToast(message) {
        // Buat elemen toast dinamis
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
        
        // Animasi fade in & fade out
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

    // Menangani simulasi error alert
    function simulateError() {
        const content = document.getElementById('admin-main-content');
        const errorAlert = document.getElementById('admin-error-container');

        if (content && errorAlert) {
            content.style.display = 'none';
            errorAlert.style.display = 'block';
        }
    }

    function retryFromError() {
        const errorAlert = document.getElementById('admin-error-container');
        if (errorAlert) errorAlert.style.display = 'none';
        refreshDashboard();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSkeletonLoader();
    });

    return { refreshDashboard, showToast, simulateError, retryFromError };
})();
