/**
 * ============================================================
 * USER PROFILE – Security & Password Control JS
 * public/js/profile/security.js
 * ============================================================
 */

const ProfileSecurity = (() => {
    'use strict';

    function initPasswordValidation() {
        const form = document.getElementById('form-security');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const currentPwd = document.getElementById('current_password').value;
            const newPwd = document.getElementById('new_password').value;
            const confirmPwd = document.getElementById('confirm_password').value;

            // Validasi kata sandi baru minimal 8 karakter
            if (newPwd.length < 8) {
                UserProfile.showFeedback('danger', 'Kata sandi baru harus minimal 8 karakter!');
                document.getElementById('new_password').classList.add('is-invalid');
                return;
            } else {
                document.getElementById('new_password').classList.remove('is-invalid');
            }

            // Validasi kecocokan sandi
            if (newPwd !== confirmPwd) {
                UserProfile.showFeedback('danger', 'Konfirmasi kata sandi baru tidak cocok!');
                document.getElementById('confirm_password').classList.add('is-invalid');
                return;
            } else {
                document.getElementById('confirm_password').classList.remove('is-invalid');
            }

            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Memperbarui...`;
            }

            setTimeout(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = 'Perbarui Sandi';
                }
                form.reset();
                UserProfile.showFeedback('success', 'Kata sandi akun Anda berhasil diperbarui!');
            }, 1200);
        });
    }

    // Pemutusan sesi login perangkat
    function terminateSession(sessionId, element) {
        if (!confirm('Apakah Anda yakin ingin mengeluarkan sesi perangkat ini?')) return;

        const sessionRow = element.closest('.p-3.border');
        if (sessionRow) {
            sessionRow.style.opacity = '0.5';
            const btn = sessionRow.querySelector('button');
            if (btn) btn.disabled = true;

            setTimeout(() => {
                sessionRow.remove();
                UserProfile.showFeedback('success', 'Sesi perangkat berhasil diakhiri!');
            }, 800);
        }
    }

    function terminateAllSessions() {
        if (!confirm('Apakah Anda yakin ingin mengeluarkan seluruh sesi perangkat lain?')) return;

        const sessions = document.querySelectorAll('.other-device-session');
        sessions.forEach(session => {
            session.style.opacity = '0.5';
        });

        setTimeout(() => {
            sessions.forEach(session => session.remove());
            UserProfile.showFeedback('success', 'Seluruh sesi perangkat lain berhasil diputuskan!');
        }, 1000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        initPasswordValidation();
    });

    return { terminateSession, terminateAllSessions };
})();
