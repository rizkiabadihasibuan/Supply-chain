@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary border-opacity-25">
    <h1 class="h2 text-white">Pengaturan Profil</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-4 mb-4">
            <h5 class="text-white mb-4"><i class="bi bi-person-gear me-2 text-glow-cyan"></i> Informasi Akun</h5>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <!-- Name Input -->
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="border-secondary opacity-25 mb-4">
                <h5 class="text-white mb-3"><i class="bi bi-shield-key me-2 text-glow-purple"></i> Ganti Password <small class="text-secondary" style="font-size: 0.8rem;">(Biarkan kosong jika tidak ingin diubah)</small></h5>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Ketik password saat ini">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-2"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Role & Scope Card -->
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-shield-lock me-2 text-glow-cyan"></i> Detail Hak Akses</h5>
            
            <div class="text-center py-4 mb-3" style="background: rgba(17, 24, 39, 0.4); border-radius: 12px; border: 1px solid var(--card-border);">
                <i class="bi bi-person-badge display-4 text-glow-cyan"></i>
                <h4 class="text-white mt-3 mb-1">{{ $user->role->name }}</h4>
                <span class="badge bg-secondary glass-card text-glow-cyan px-3 py-1">Active User Role</span>
            </div>
            
            <p class="text-secondary small mb-0"><strong>Deskripsi Peran:</strong></p>
            <p class="text-secondary small mb-3">{{ $user->role->description }}</p>
            
            <p class="text-secondary small mb-0"><strong>Izin Fitur Anda:</strong></p>
            <ul class="text-secondary small ps-3">
                @foreach($user->role->permissions as $perm)
                    <li>{{ $perm->description }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
