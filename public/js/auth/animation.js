/**
 * ============================================================
 * AUTH MODULE – Animations
 * resources/js/auth/animation.js
 * ============================================================
 * Micro-interactions: fade-in, hover effects, input focus glow.
 */

const AuthAnimation = (() => {
    'use strict';

    /**
     * Initialize form field stagger animation
     */
    function initStaggerAnimation() {
        const inputs = document.querySelectorAll('.auth-input-group, .form-check, .auth-btn, .auth-divider, .auth-social-btn');
        inputs.forEach((el, i) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(12px)';
            el.style.transition = `opacity .4s ease ${i * 0.06}s, transform .4s ease ${i * 0.06}s`;

            // Trigger after a short delay
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                });
            });
        });
    }

    /**
     * Input focus glow animation
     */
    function initFocusEffects() {
        document.querySelectorAll('.auth-input-group .form-control').forEach(input => {
            input.addEventListener('focus', function () {
                this.closest('.auth-input-group')?.classList.add('auth-input-focused');
            });
            input.addEventListener('blur', function () {
                this.closest('.auth-input-group')?.classList.remove('auth-input-focused');
            });
        });
    }

    /**
     * Button hover ripple effect
     */
    function initButtonEffects() {
        document.querySelectorAll('.auth-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function () {
                this.style.transition = 'background .2s ease, transform .15s ease, box-shadow .2s ease';
            });
        });
    }

    /**
     * Smooth fade-in for feedback alerts
     */
    function initAlertAnimation() {
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1 && node.classList?.contains('auth-alert')) {
                        node.style.animation = 'authFadeSlide .35s ease forwards';
                    }
                });
            });
        });

        // Observe feedback containers
        document.querySelectorAll('[id$="-feedback"]').forEach(container => {
            observer.observe(container, { childList: true, subtree: true });
        });
    }

    // Auto-init on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        initStaggerAnimation();
        initFocusEffects();
        initButtonEffects();
        initAlertAnimation();
    });

    return { initStaggerAnimation, initFocusEffects, initButtonEffects, initAlertAnimation };
})();
