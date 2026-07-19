/**
 * LOADING – Global Loading State Manager
 * Mengelola skeleton loading dan spinner
 */

const LoadingHelper = {
    show: (el) => el && el.classList.remove('d-none'),
    hide: (el) => el && el.classList.add('d-none'),
};
window.LoadingHelper = LoadingHelper;
