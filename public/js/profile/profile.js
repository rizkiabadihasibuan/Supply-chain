/**
 * ============================================================
 * USER PROFILE – Core JS
 * public/js/profile/profile.js
 * ============================================================
 */

const UserProfile = (() => {
    'use strict';

    // Menyembunyikan skeleton loading dan menampilkan grid konten utama
    function initSkeletonLoader() {
        const skeleton = document.getElementById('skeleton-container');
        const content = document.getElementById('main-content-grid');
        
        if (skeleton && content) {
            setTimeout(() => {
                skeleton.style.display = 'none';
                content.style.display = 'flex';
                content.classList.add('fade-in-up');
            }, 800);
        }
    }

    // Menampilkan pesan sukses/error
    function showFeedback(type, message) {
        const feedbackArea = document.getElementById('settings-feedback-area');
        if (!feedbackArea) return;

        // Kosongkan dan ganti isinya dengan alert bootstrap
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';

        feedbackArea.innerHTML = `
            <div class="alert ${alertClass} d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi ${iconClass}"></i>
                <div>${message}</div>
            </div>
        `;
        feedbackArea.style.display = 'block';

        // Scroll halus ke atas agar alert terlihat oleh user
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Sembunyikan setelah 3 detik
        setTimeout(() => {
            feedbackArea.style.display = 'none';
        }, 3000);
    }

    // Simulasi pengisian data profil
    function handleFormSubmissions() {
        const forms = [
            { id: 'form-edit-profile', msg: 'Informasi profil Anda berhasil diperbarui!' },
            { id: 'form-language', msg: 'Bahasa antarmuka berhasil diubah!' },
            { id: 'form-regional', msg: 'Pengaturan regional dan format desimal berhasil disimpan!' },
            { id: 'form-privacy', msg: 'Pengaturan privasi data berhasil diperbarui!' }
        ];

        forms.forEach(item => {
            const form = document.getElementById(item.id);
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    
                    // Validasi HTML5 bawaan browser
                    if (!this.checkValidity()) {
                        this.classList.add('was-validated');
                        return;
                    }

                    // Tampilkan loader tombol jika ada
                    const btn = this.querySelector('button[type="submit"]');
                    const originalText = btn ? btn.innerHTML : '';
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menyimpan...`;
                    }

                    setTimeout(() => {
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        }
                        showFeedback('success', item.msg);
                    }, 1000);
                });
            });
        });
    }

    // Simulasi penanganan error
    function simulateError() {
        const skeleton = document.getElementById('skeleton-container');
        const content = document.getElementById('main-content-grid');
        const errorContainer = document.getElementById('error-state-container');

        if (skeleton) skeleton.style.display = 'none';
        if (content) content.style.display = 'none';
        if (errorContainer) {
            errorContainer.style.display = 'flex';
            errorContainer.classList.add('fade-in-up');
        }
    }

    function retryLoad() {
        const skeleton = document.getElementById('skeleton-container');
        const errorContainer = document.getElementById('error-state-container');
        const content = document.getElementById('main-content-grid');

        if (errorContainer) errorContainer.style.display = 'none';
        if (skeleton) skeleton.style.display = 'block';

        setTimeout(() => {
            if (skeleton) skeleton.style.display = 'none';
            if (content) {
                content.style.display = 'flex';
                content.classList.add('fade-in-up');
            }
        }, 800);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initSkeletonLoader();
        handleFormSubmissions();
    });

    return { showFeedback, simulateError, retryLoad };
})();
