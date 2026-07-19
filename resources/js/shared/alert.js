/**
 * ALERT – Global Alert/Notification Helper
 * Wrapper untuk Bootstrap Toast dan Alert
 */

const AlertHelper = {
    show: (msg, type = 'success') => console.log('[Alert]', type, msg),
    error: (msg) => AlertHelper.show(msg, 'danger'),
    success: (msg) => AlertHelper.show(msg, 'success'),
};
window.AlertHelper = AlertHelper;
