/**
 * ============================================================
 * AUTH MODULE – Main JavaScript
 * resources/js/auth/auth.js
 * ============================================================
 * Shared utilities: feedback alerts, loading state, password toggle.
 */

const Auth = (() => {
    'use strict';

    /**
     * Show alert feedback inside a container element
     * @param {string} containerId - DOM id of feedback container
     * @param {string} type - 'success' | 'danger' | 'info'
     * @param {string} message - Alert message text
     */
    function showFeedback(containerId, type, message) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const typeMap = {
            success: 'auth-alert-success',
            danger:  'auth-alert-danger',
            info:    'auth-alert-info',
        };
        const iconMap = {
            success: 'bi-check-circle-fill',
            danger:  'bi-exclamation-octagon-fill',
            info:    'bi-info-circle-fill',
        };

        container.innerHTML = `
            <div class="auth-alert ${typeMap[type] || 'auth-alert-info'}">
                <i class="bi ${iconMap[type] || 'bi-info-circle-fill'}"></i>
                <span>${message}</span>
            </div>`;
        container.style.display = '';
    }

    /**
     * Hide feedback container
     */
    function hideFeedback(containerId) {
        const el = document.getElementById(containerId);
        if (el) {
            el.innerHTML = '';
            el.style.display = 'none';
        }
    }

    /**
     * Start loading state on an auth-btn
     */
    function startLoading(btnId, loadingText) {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        const spinner = btn.querySelector('.auth-spinner');
        const labelEl = btn.querySelector('.auth-btn-label');
        btn.disabled = true;
        if (spinner) spinner.classList.add('spinning');
        if (labelEl) labelEl.textContent = loadingText || 'Memproses...';
    }

    /**
     * Stop loading state
     */
    function stopLoading(btnId, originalText) {
        const btn = document.getElementById(btnId);
        if (!btn) return;
        const spinner = btn.querySelector('.auth-spinner');
        const labelEl = btn.querySelector('.auth-btn-label');
        btn.disabled = false;
        if (spinner) spinner.classList.remove('spinning');
        if (labelEl) labelEl.textContent = originalText;
    }

    /**
     * Initialize password toggles
     */
    function initPasswordToggles() {
        document.querySelectorAll('.auth-pwd-toggle').forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                if (!input || !icon) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'bi bi-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'bi bi-eye';
                }
            });
        });
    }

    // Auto-init on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        initPasswordToggles();
    });

    return { showFeedback, hideFeedback, startLoading, stopLoading, initPasswordToggles };
})();
