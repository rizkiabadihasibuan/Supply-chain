/**
 * ============================================================
 * ADMIN PORT DATASET – Modal JS
 * resources/js/admin/ports/modal.js
 * ============================================================
 */

const PortsModal = (() => {
    'use strict';

    // Memuat info lengkap ke modal detail
    function showDetail(name, code, country, region, latitude, longitude, status, capacity, lastSync) {
        document.getElementById('detail-port-name').textContent = name;
        document.getElementById('detail-port-code').textContent = code || '—';
        document.getElementById('detail-port-country').textContent = country;
        document.getElementById('detail-port-region').textContent = region;
        document.getElementById('detail-port-coords').textContent = latitude;
        document.getElementById('detail-port-capacity').textContent = longitude;
        document.getElementById('detail-port-status').textContent = status;
        document.getElementById('detail-port-lastsync').textContent = lastSync || 'Today 08:30 UTC';

        // Styling badge status
        const statusBadge = document.getElementById('detail-port-status');
        statusBadge.className = 'badge';
        if (status === 'Aktif') statusBadge.classList.add('badge-status-aktif');
        else if (status === 'Tidak Aktif') statusBadge.classList.add('badge-status-tidak-aktif');
        else statusBadge.classList.add('badge-status-maintenance');
    }

    // Memuat data terpilih ke modal edit
    function showEdit(name, code, country, region, latitude, longitude, status, capacity) {
        document.getElementById('edit-port-name').value = name;
        document.getElementById('edit-port-code').value = code || '';
        document.getElementById('edit-port-country').value = country;
        document.getElementById('edit-port-region').value = region;
        document.getElementById('edit-port-lat').value = latitude || '';
        document.getElementById('edit-port-lng').value = longitude || '';
        document.getElementById('edit-port-status').value = status;
    }

    // Simulasi simpan data tambah pelabuhan baru
    function saveCreate() {
        const form = document.getElementById('form-create-port');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const btn = document.getElementById('btn-save-create-port');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menyimpan...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Simpan';
            }
            const modalEl = document.getElementById('portCreateModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');
            PortsCore.showToast('Dataset berhasil ditambahkan.');
        }, 1000);
    }

    // Simulasi update data edit pelabuhan
    function saveEdit() {
        const form = document.getElementById('form-edit-port');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const btn = document.getElementById('btn-save-edit-port');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Mengubah...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Update';
            }
            const modalEl = document.getElementById('portEditModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            form.reset();
            form.classList.remove('was-validated');
            PortsCore.showToast('Dataset berhasil diperbarui.');
        }, 1000);
    }

    // Simulasi delete
    function confirmDelete() {
        const btn = document.getElementById('btn-save-delete-port');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Menghapus...`;
        }

        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = 'Hapus';
            }
            const modalEl = document.getElementById('portDeleteModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            PortsCore.showToast('Dataset berhasil dihapus.');
        }, 800);
    }

    return { showDetail, showEdit, saveCreate, saveEdit, confirmDelete };
})();
