/**
 * ============================================================
 * ADMIN DASHBOARD – System Notifications JS
 * resources/js/admin/notification.js
 * ============================================================
 */

const AdminNotification = (() => {
    'use strict';

    // Menghapus notifikasi secara lokal di UI (Simulasi)
    function dismissNotification(element) {
        const item = element.closest('.list-group-item');
        if (item) {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateX(20px)';
            
            setTimeout(() => {
                item.remove();
                
                // Cek jika habis, ganti dengan text kosong
                const container = document.getElementById('admin-notification-list');
                if (container && container.children.length === 0) {
                    container.innerHTML = `<div class="text-center p-3 text-secondary small">Tidak ada notifikasi sistem baru.</div>`;
                }
                
                if (typeof AdminDashboard !== 'undefined') {
                    AdminDashboard.showToast('Pemberitahuan berhasil dihapus.');
                }
            }, 300);
        }
    }

    return { dismissNotification };
})();
