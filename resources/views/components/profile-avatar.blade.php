@props([
    'src' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=150&auto=format&fit=crop'
])

<div class="d-flex flex-column flex-sm-row align-items-center gap-4 py-3">
    <div class="position-relative">
        <img src="{{ $src }}" alt="Avatar Preview" id="avatar-preview-img" class="rounded-circle border border-primary border-3" width="100" height="100" style="object-fit: cover;">
    </div>
    
    <div class="text-center text-sm-start">
        <h6 class="fw-bold text-dark mb-1">Foto Profil Pengguna</h6>
        <p class="text-secondary small mb-3">PNG, JPG, atau WEBP. Maksimal ukuran 2MB.</p>
        
        <div class="d-flex flex-wrap justify-content-center justify-content-sm-start gap-2">
            <!-- Simulated File Upload Button -->
            <label class="btn btn-primary btn-sm px-3 d-flex align-items-center" style="min-height: 38px; cursor: pointer;">
                <i class="bi bi-upload me-1.5 text-white"></i>Unggah Foto Baru
                <input type="file" id="avatar-file-input" style="display: none;" onchange="handleAvatarUpload(this)">
            </label>
            <button type="button" class="btn btn-outline-danger btn-sm px-3" style="min-height: 38px;" onclick="removeAvatarImage()">
                <i class="bi bi-trash me-1.5"></i>Hapus Foto
            </button>
        </div>
    </div>
</div>

<script>
    if (typeof handleAvatarUpload === 'undefined') {
        window.handleAvatarUpload = function(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview-img').src = e.target.result;
                    // also update navbar avatar if present
                    const navbarAvatar = document.querySelector('.navbar-custom img');
                    if (navbarAvatar) navbarAvatar.src = e.target.result;
                    alert('Foto profil berhasil diunggah (Simulasi)!');
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        window.removeAvatarImage = function() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                const defaultAvatar = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=150&auto=format&fit=crop';
                document.getElementById('avatar-preview-img').src = defaultAvatar;
                const navbarAvatar = document.querySelector('.navbar-custom img');
                if (navbarAvatar) navbarAvatar.src = defaultAvatar;
                alert('Foto profil dihapus (Kembali ke default).');
            }
        };
    }
</script>
