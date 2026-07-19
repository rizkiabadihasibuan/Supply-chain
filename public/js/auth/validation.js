/**
 * ============================================================
 * AUTH MODULE – Form Validation
 * resources/js/auth/validation.js
 * ============================================================
 * Client-side validation using Bootstrap 5 validation classes.
 */

const AuthValidation = (() => {
    'use strict';

    /**
     * Validate a form using Bootstrap-style validation.
     * Adds 'was-validated' class and checks HTML5 validity.
     * @param {HTMLFormElement} form
     * @returns {boolean} true if valid
     */
    function validateForm(form) {
        // Remove previous custom states
        form.querySelectorAll('.is-invalid').forEach(el => {
            // Only remove if it doesn't have a custom match error
            if (!el.dataset.customInvalid) {
                el.classList.remove('is-invalid');
            }
        });

        if (!form.checkValidity()) {
            form.classList.add('was-validated');

            // Focus first invalid field
            const firstInvalid = form.querySelector(':invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.classList.add('is-invalid');
            }
            return false;
        }

        form.classList.add('was-validated');
        return true;
    }

    /**
     * Validate email format
     * @param {string} email
     * @returns {boolean}
     */
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    /**
     * Password strength checker
     * Updates the password strength meter bars and text.
     * @param {string} password
     */
    function checkPasswordStrength(password) {
        const meter = document.getElementById('pwd-strength-meter');
        if (!meter) return;

        const bars = meter.querySelectorAll('.auth-pwd-bar');
        const textEl = meter.querySelector('.auth-pwd-strength-text');

        // Reset all bars
        bars.forEach(bar => {
            bar.className = 'auth-pwd-bar';
        });

        if (!password || password.length === 0) {
            meter.style.display = 'none';
            return;
        }

        meter.style.display = 'block';

        let score = 0;
        let label = '';
        let colorClass = '';

        // Length check
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;

        // Complexity checks
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) score++;
        if (/\d/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;

        // Map score to strength level
        if (score <= 1) {
            label = 'Lemah';
            colorClass = 'weak';
        } else if (score <= 2) {
            label = 'Cukup';
            colorClass = 'fair';
        } else if (score <= 3) {
            label = 'Baik';
            colorClass = 'good';
        } else {
            label = 'Kuat';
            colorClass = 'strong';
        }

        // Fill bars up to score level (max 4)
        const fillCount = Math.min(score, 4);
        for (let i = 0; i < fillCount; i++) {
            if (bars[i]) bars[i].classList.add(colorClass);
        }

        if (textEl) {
            textEl.textContent = `Kekuatan: ${label}`;
            // Color the text
            const colorMap = { weak: '#EF4444', fair: '#F59E0B', good: '#3B82F6', strong: '#22C55E' };
            textEl.style.color = colorMap[colorClass] || '#64748B';
        }
    }

    /**
     * Initialize real-time validation on input fields
     */
    function initRealtimeValidation() {
        // Email fields
        document.querySelectorAll('input[type="email"]').forEach(input => {
            input.addEventListener('blur', function () {
                if (this.value && !isValidEmail(this.value)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else if (this.value) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Required text fields
        document.querySelectorAll('input[required]:not([type="email"]):not([type="checkbox"])').forEach(input => {
            input.addEventListener('blur', function () {
                const min = parseInt(this.getAttribute('minlength')) || 0;
                if (this.value && this.value.length >= min) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else if (this.value && this.value.length < min) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                }
            });
        });

        // Clear validation state on input
        document.querySelectorAll('.auth-input-group .form-control').forEach(input => {
            input.addEventListener('input', function () {
                if (this.classList.contains('is-invalid') && this.value) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    }

    // Auto-init on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        initRealtimeValidation();
    });

    return { validateForm, isValidEmail, checkPasswordStrength, initRealtimeValidation };
})();
