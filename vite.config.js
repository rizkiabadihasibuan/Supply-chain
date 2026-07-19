import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

/**
 * ═══════════════════════════════════════════════════════════
 * VITE CONFIG – Milestone 3.16A (Enterprise Bundle Architecture)
 * ═══════════════════════════════════════════════════════════
 *
 * Bundle Strategy:
 *   - shared  → CSS/JS yang dipakai oleh SEMUA halaman (variables, helper, api)
 *   - admin   → CSS/JS khusus panel Admin (/admin/*)
 *   - user    → CSS/JS khusus panel User (/dashboard, /countries, dst.)
 *
 * Usage dalam Blade:
 *   @vite(['resources/css/shared/variables.css', 'resources/js/shared/helper.js'])
 *   @vite(['resources/css/admin/dashboard.css',  'resources/js/admin/dashboard.js'])
 *   @vite(['resources/css/user/dashboard.css',   'resources/js/user/dashboard.js'])
 *
 * NOTE: Bootstrap 5 tetap di-load via CDN di layout blade.
 *       Vite hanya mengelola custom CSS/JS milik project.
 * ═══════════════════════════════════════════════════════════
 */

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // ─── SHARED BUNDLE ───────────────────────────────
                'resources/css/shared/variables.css',
                'resources/css/shared/animation.css',
                'resources/css/shared/utility.css',
                'resources/css/shared/bootstrap-custom.css',
                'resources/css/shared/typography.css',
                'resources/js/shared/helper.js',
                'resources/js/shared/api.js',
                'resources/js/shared/alert.js',
                'resources/js/shared/toast.js',
                'resources/js/shared/loading.js',

                // ─── ADMIN BUNDLE ────────────────────────────────
                'resources/css/admin/dashboard.css',
                'resources/css/admin/users.css',
                'resources/css/admin/ports.css',
                'resources/css/admin/articles.css',
                'resources/css/admin/tables.css',
                'resources/css/admin/cards.css',
                'resources/css/admin/forms.css',
                'resources/css/admin/modal.css',
                'resources/css/admin/responsive.css',
                'resources/js/admin/dashboard.js',
                'resources/js/admin/users.js',
                'resources/js/admin/ports.js',
                'resources/js/admin/articles.js',
                'resources/js/admin/chart.js',
                'resources/js/admin/table.js',
                'resources/js/admin/modal.js',
                'resources/js/admin/filter.js',

                // ─── USER BUNDLE ─────────────────────────────────
                'resources/css/user/dashboard.css',
                'resources/css/user/country.css',
                'resources/css/user/weather.css',
                'resources/css/user/currency.css',
                'resources/css/user/news.css',
                'resources/css/user/risk.css',
                'resources/css/user/favorite.css',
                'resources/css/user/comparison.css',
                'resources/css/user/profile.css',
                'resources/css/user/responsive.css',
                'resources/js/user/dashboard.js',
                'resources/js/user/country.js',
                'resources/js/user/weather.js',
                'resources/js/user/currency.js',
                'resources/js/user/news.js',
                'resources/js/user/risk.js',
                'resources/js/user/favorite.js',
                'resources/js/user/comparison.js',
                'resources/js/user/profile.js',

                // ─── LEGACY (root entry — dipertahankan untuk backward compat) ──
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
    build: {
        // Pisahkan chunk per bundle untuk efisiensi caching browser
        rollupOptions: {
            output: {
                manualChunks: {
                    shared: [
                        'resources/js/shared/helper.js',
                        'resources/js/shared/api.js',
                        'resources/js/shared/alert.js',
                        'resources/js/shared/toast.js',
                        'resources/js/shared/loading.js',
                    ],
                },
            },
        },
    },
});
