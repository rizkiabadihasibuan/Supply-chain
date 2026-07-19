/**
 * ============================================================
 * USER PROFILE – Avatar Upload & Crop JS
 * public/js/profile/avatar.js
 * ============================================================
 */

const ProfileAvatar = (() => {
    'use strict';

    let selectedFile = null;

    // Membaca berkas gambar yang dipilih pengguna dan menampilkannya di pratinjau
    function handleFileSelect(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        
        if (!file) return;
        if (!file.type.match('image.*')) {
            alert('Silakan pilih berkas gambar yang valid (JPG, PNG, atau WEBP).');
            return;
        }

        selectedFile = file;

        const reader = new FileReader();
        reader.onload = function (e) {
            const previewBox = document.getElementById('crop-placeholder-box');
            const previewCircle = document.getElementById('avatar-preview-circle-el');
            
            if (previewBox) {
                previewBox.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;" id="raw-crop-image">`;
            }
            if (previewCircle) {
                previewCircle.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }

    // Simulasi penyimpanan foto profil
    function saveAvatar() {
        if (!selectedFile) {
            alert('Pilih gambar terlebih dahulu sebelum menyimpan.');
            return;
        }

        const saveBtn = document.getElementById('btn-save-avatar');
        const originalText = saveBtn ? saveBtn.innerHTML : '';
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1.5" role="status" aria-hidden="true"></span>Mengunggah...`;
        }

        setTimeout(() => {
            // Perbarui gambar di summary profile card
            const rawImageSrc = document.getElementById('raw-crop-image')?.src;
            const mainAvatars = document.querySelectorAll('.profile-avatar-img, img[alt="Profil User"]');
            
            if (rawImageSrc) {
                mainAvatars.forEach(avatar => {
                    avatar.src = rawImageSrc;
                });
            }

            if (saveBtn) {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalText;
            }

            // Tutup modal
            const modalEl = document.getElementById('avatarUploadModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                if (modalInstance) modalInstance.hide();
            }

            UserProfile.showFeedback('success', 'Foto profil Anda berhasil diperbarui!');
        }, 1200);
    }

    // Reset isi modal saat modal ditutup
    function resetModal() {
        selectedFile = null;
        const fileInput = document.getElementById('avatar-file-input');
        if (fileInput) fileInput.value = '';

        const previewBox = document.getElementById('crop-placeholder-box');
        const previewCircle = document.getElementById('avatar-preview-circle-el');
        
        if (previewBox) {
            previewBox.innerHTML = `
                <div class="text-center p-4">
                    <i class="bi bi-cloud-arrow-up fs-2 text-secondary d-block mb-1.5"></i>
                    <span>Pilih file gambar untuk disimulasikan crop</span>
                </div>
            `;
        }
        if (previewCircle) {
            previewCircle.src = 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop';
        }
    }

    return { handleFileSelect, saveAvatar, resetModal };
})();
