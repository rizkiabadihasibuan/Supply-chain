/**
 * ============================================================
 * USER PROFILE – Preferences & Theme JS
 * public/js/profile/preferences.js
 * ============================================================
 */

const ProfilePreferences = (() => {
    'use strict';

    // Memilih mode tema visual
    function switchTheme(themeName, element) {
        // Hapus kelas aktif dari semua opsi tema
        const cards = document.querySelectorAll('.theme-card');
        cards.forEach(card => {
            card.classList.remove('active');
        });

        // Aktifkan kartu tema yang dipilih
        if (element) {
            element.classList.add('active');
        }

        // Terapkan tema secara instan pada document root (Simulasi)
        const htmlElement = document.documentElement;
        if (themeName === 'dark') {
            htmlElement.setAttribute('data-bs-theme', 'dark');
            document.body.style.backgroundColor = '#0F172A';
        } else if (themeName === 'light') {
            htmlElement.setAttribute('data-bs-theme', 'light');
            document.body.style.backgroundColor = 'var(--background)';
        } else {
            // Mode Sistem Default (Mendeteksi preferensi OS)
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            htmlElement.setAttribute('data-bs-theme', prefersDark ? 'dark' : 'light');
            document.body.style.backgroundColor = prefersDark ? '#0F172A' : 'var(--background)';
        }

        UserProfile.showFeedback('success', `Tema visual berhasil dialihkan ke mode ${themeName.toUpperCase()}!`);
    }

    return { switchTheme };
})();
