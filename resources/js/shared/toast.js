/**
 * TOAST – Bootstrap Toast Wrapper
 * Menampilkan notifikasi singkat di sudut layar
 */

const ToastHelper = {
    show: (msg, type = 'success') => {
        console.log('[Toast]', type, msg);
        // TODO: Implement Bootstrap Toast rendering
    },
};
window.ToastHelper = ToastHelper;
