/**
 * ============================================================
 * USER PROFILE – Tab Navigation JS
 * public/js/profile/tabs.js
 * ============================================================
 */

const ProfileTabs = (() => {
    'use strict';

    function switchTab(tabId, targetBtn) {
        // Cari semua lembar konten tab
        const sheets = document.querySelectorAll('.settings-tab-sheet');
        sheets.forEach(sheet => {
            sheet.style.display = 'none';
        });

        // Tampilkan lembar yang dicari
        const activeSheet = document.getElementById(`sheet-${tabId}`);
        if (activeSheet) {
            activeSheet.style.display = 'block';
            activeSheet.classList.add('fade-in-up');
        }

        // Hapus class 'active' dari semua tautan nav
        const links = document.querySelectorAll('.profile-nav-pills .nav-link, .settings-menu-item');
        links.forEach(link => {
            link.classList.remove('active');
        });

        // Set tautan/tombol yang diklik menjadi aktif
        if (targetBtn) {
            targetBtn.classList.add('active');
        }

        // Sinkronisasi navigasi versi mobile jika ada
        const mobileMenuButtons = document.querySelectorAll('.settings-menu-item');
        mobileMenuButtons.forEach(btn => {
            const btnText = btn.textContent.trim().toLowerCase();
            const targetText = targetBtn ? targetBtn.textContent.trim().toLowerCase() : '';
            if (btnText && targetText && btnText.includes(targetText)) {
                btn.classList.add('active');
            }
        });
    }

    // Mengontrol penutupan menu akordeon versi mobile setelah diklik
    function collapseMobileAccordion() {
        const accordionMenu = document.getElementById('collapseSettingsMenu');
        if (accordionMenu && typeof bootstrap !== 'undefined') {
            const bsCollapse = bootstrap.Collapse.getInstance(accordionMenu);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
    }

    return { switchTab, collapseMobileAccordion };
})();
