/**
 * ============================================================
 * ADMIN USER MANAGEMENT – Modals Interactions JS
 * resources/js/admin/users/modal.js
 * ============================================================
 */

const UsersModal = (() => {
    'use strict';

    // Memuat info lengkap ke modal detail
    function showDetail(name, email, role, status, joined, lastLogin, phone, company, country, avatar) {
        document.getElementById('detail-avatar').src = avatar;
        document.getElementById('detail-fullname').textContent = name;
        document.getElementById('detail-email').textContent = email;
        document.getElementById('detail-role').textContent = role;
        document.getElementById('detail-status').textContent = status;
        document.getElementById('detail-joined').textContent = joined;
        document.getElementById('detail-lastlogin').textContent = lastLogin;
        document.getElementById('detail-phone').textContent = phone || '—';
        document.getElementById('detail-company').textContent = company || '—';
        document.getElementById('detail-country').textContent = country || '—';

        // Styling badge status
        const statusBadge = document.getElementById('detail-status');
        statusBadge.className = 'badge';
        if (status === 'Active') statusBadge.classList.add('badge-status-active');
        else if (status === 'Inactive') statusBadge.classList.add('badge-status-inactive');
        else statusBadge.classList.add('badge-status-suspended');

        // Styling badge role
        const roleBadge = document.getElementById('detail-role');
        roleBadge.className = 'badge';
        if (role === 'Admin') roleBadge.classList.add('bg-primary');
        else roleBadge.classList.add('bg-secondary');
    }

    // Memuat data terpilih ke modal edit
    function showEdit(name, email, role, status) {
        document.getElementById('edit-fullname').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-role').value = role === 'Admin' ? 'Admin' : 'User';
        document.getElementById('edit-status').value = status;
    }

    // Simulasi simpan data tambah user baru
    function saveCreate() {
        const form = document.getElementById('form-create-user');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const pwd = document.getElementById('create-password').value;
        const confirmPwd = document.getElementById('create-password-confirm').value;

        if (pwd !== confirmPwd) {
            alert('Konfirmasi kata sandi tidak cocok!');
            return;
        }

        const btn = document.getElementById('btn-save-create');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menyimpan...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Simpan';
            }
            // Tutup modal
            const modalEl = document.getElementById('userCreateModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');
            UsersCore.showToast('Pengguna baru berhasil ditambahkan!');
        }, 1000);
    }

    // Simulasi update data edit user
    function saveEdit() {
        const form = document.getElementById('form-edit-user');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const btn = document.getElementById('btn-save-edit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Mengubah...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Update';
            }
            // Tutup modal
            const modalEl = document.getElementById('userEditModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');
            UsersCore.showToast('Informasi pengguna berhasil diperbarui!');
        }, 1000);
    }

    // Simulasi reset password
    function saveResetPassword() {
        const form = document.getElementById('form-reset-password');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const pwd = document.getElementById('reset-password-val').value;
        const confirmPwd = document.getElementById('reset-password-confirm-val').value;

        if (pwd !== confirmPwd) {
            alert('Konfirmasi kata sandi tidak cocok!');
            return;
        }

        const btn = document.getElementById('btn-save-reset');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Mereset...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Reset Password';
            }
            const modalEl = document.getElementById('userResetPasswordModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');
            UsersCore.showToast('Kata sandi pengguna berhasil direset!');
        }, 1000);
    }

    // Simulasi suspend
    function confirmSuspend() {
        const btn = document.getElementById('btn-save-suspend');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Memproses...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Ya';
            }
            const modalEl = document.getElementById('userSuspendModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            UsersCore.showToast('Akun pengguna berhasil dinonaktifkan (Suspended)!');
        }, 800);
    }

    // Simulasi delete
    function confirmDelete() {
        const btn = document.getElementById('btn-save-delete');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menghapus...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Hapus';
            }
            const modalEl = document.getElementById('userDeleteModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            UsersCore.showToast('Data pengguna berhasil dihapus secara permanen!');
        }, 800);
    }

    return { showDetail, showEdit, saveCreate, saveEdit, saveResetPassword, confirmSuspend, confirmDelete };
})();
