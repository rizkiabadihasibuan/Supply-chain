@extends('layouts.app')

@section('title', 'Admin Control Panel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary border-opacity-25">
    <h1 class="h2 text-white"><i class="bi bi-shield-lock me-2 text-glow-purple"></i> Admin Control Panel</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <span class="badge bg-secondary glass-card text-white py-2 px-3">
            <i class="bi bi-person-badge me-1 text-glow-cyan"></i> Mode Administrator
        </span>
    </div>
</div>

<!-- Tab Navigation Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-2">
            <ul class="nav nav-pills nav-fill gap-2" id="adminTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-white py-2.5" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="true">
                        <i class="bi bi-cpu me-1.5 text-glow-cyan"></i> Ringkasan Sistem
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-white py-2.5" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="users" aria-selected="false">
                        <i class="bi bi-people me-1.5 text-glow-purple"></i> Kelola User (RBAC)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-white py-2.5" id="ports-tab" data-bs-toggle="tab" data-bs-target="#ports" type="button" role="tab" aria-controls="ports" aria-selected="false">
                        <i class="bi bi-water me-1.5 text-glow-cyan"></i> Dataset Pelabuhan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-white py-2.5" id="articles-tab" data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab" aria-controls="articles" aria-selected="false">
                        <i class="bi bi-newspaper me-1.5 text-glow-purple"></i> Artikel Analisis
                    </button>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Tab Contents -->
<div class="tab-content" id="adminTabContent">
    
    <!-- Tab 1: Overview -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
        <div class="row g-4">
            <div class="col-md-7">
                <div class="glass-card p-4 h-100">
                    <h5 class="text-white mb-3"><i class="bi bi-shield-check me-2 text-glow-cyan"></i> Otoritas Khusus Admin</h5>
                    <p class="text-secondary">Sebagai administrator, Anda memiliki akses penuh untuk mengatur integritas sistem supply chain. Pastikan data koordinat pelabuhan, role akun analitis, dan artikel analisis logistik diperbarui secara berkala.</p>
                    <hr class="border-secondary border-opacity-25 my-4">
                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <h4 class="text-glow-cyan fw-bold">{{ $users->count() }}</h4>
                            <small class="text-secondary">Total User</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-glow-purple fw-bold">{{ $ports->count() }}</h4>
                            <small class="text-secondary">Pelabuhan</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-glow-cyan fw-bold">{{ $articles->count() }}</h4>
                            <small class="text-secondary">Artikel Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5">
                <div class="glass-card p-4 h-100">
                    <h5 class="text-white mb-3"><i class="bi bi-cpu me-2 text-glow-purple"></i> Status Server & Integrasi</h5>
                    <div class="text-secondary small">
                        <p class="mb-2"><strong>Status Database:</strong> <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Terhubung (MariaDB/MySQL)</span></p>
                        <p class="mb-2"><strong>Sistem Operasi:</strong> Windows Server / Localhost</p>
                        <p class="mb-2"><strong>Kerangka Kerja:</strong> Laravel 11.0+ / PHP 8.3</p>
                        <p class="mb-2"><strong>Driver Cache:</strong> Database Driver (Aktif)</p>
                        <p class="mb-0"><strong>Logging Keamanan:</strong> Berjalan & Mengaudit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab 2: User Management (RBAC) -->
    <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
        <div class="glass-card p-4">
            <h5 class="text-white mb-4"><i class="bi bi-people me-2 text-glow-purple"></i> Manajemen Akun & Hak Akses (RBAC)</h5>
            <div class="table-responsive">
                <table class="table table-dark table-borderless bg-transparent align-middle">
                    <thead>
                        <tr class="border-bottom border-secondary border-opacity-25 text-secondary">
                            <th class="py-3">Nama</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Peran Saat Ini</th>
                            <th class="py-3 text-end" style="width: 250px;">Aksi Ubah Peran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="text-white fw-medium py-3">{{ $u->name }}</td>
                            <td class="text-secondary py-3">{{ $u->email }}</td>
                            <td class="py-3">
                                <span class="badge {{ $u->role->name === 'Admin' ? 'badge-high' : 'badge-low' }} py-1.5 px-3">
                                    {{ $u->role->name }}
                                </span>
                            </td>
                            <td class="py-3 text-end">
                                <form action="{{ route('admin.users.role', $u->id) }}" method="POST" class="d-inline-block w-100">
                                    @csrf
                                    <div class="input-group input-group-sm">
                                        <select name="role_id" class="form-select bg-dark text-white border-secondary border-opacity-25">
                                            @foreach($roles as $r)
                                                <option value="{{ $r->id }}" {{ $u->role_id === $r->id ? 'selected' : '' }}>
                                                    {{ $r->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Tab 3: Port Dataset Management -->
    <div class="tab-pane fade" id="ports" role="tabpanel" aria-labelledby="ports-tab">
        <div class="row g-4">
            <!-- Add Port Form -->
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <h5 class="text-white mb-3"><i class="bi bi-plus-circle me-2 text-glow-cyan"></i> Tambah Pelabuhan</h5>
                    <form action="{{ route('admin.ports.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="country_id" class="form-label">Negara Asal</label>
                            <select name="country_id" id="country_id" class="form-select" required>
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="port_code" class="form-label">Kode Pelabuhan (Port Code)</label>
                            <input type="text" name="port_code" id="port_code" class="form-control" placeholder="Contoh: IDTPP" required>
                        </div>
                        <div class="mb-3">
                            <label for="port_name" class="form-label">Nama Pelabuhan</label>
                            <input type="text" name="name" id="port_name" class="form-control" placeholder="Contoh: Port of Tanjung Priok" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="number" step="any" name="latitude" id="latitude" class="form-control" placeholder="-6.10" required>
                            </div>
                            <div class="col-6">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="number" step="any" name="longitude" id="longitude" class="form-control" placeholder="106.88" required>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label for="waiting_time_hours" class="form-label">Waktu Tunggu (Jam)</label>
                                <input type="number" name="waiting_time_hours" id="waiting_time_hours" class="form-control" value="0" required>
                            </div>
                            <div class="col-6">
                                <label for="congestion_rate" class="form-label">Kemacetan (%)</label>
                                <input type="number" step="any" name="congestion_rate" id="congestion_rate" class="form-control" value="0.00" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle me-1"></i> Simpan Pelabuhan
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Ports List Table -->
            <div class="col-md-8">
                <div class="glass-card p-4">
                    <h5 class="text-white mb-3"><i class="bi bi-water me-2 text-glow-cyan"></i> Dataset Pelabuhan Global</h5>
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-dark table-borderless bg-transparent align-middle">
                            <thead>
                                <tr class="border-bottom border-secondary border-opacity-25 text-secondary">
                                    <th class="py-2">Kode</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Negara</th>
                                    <th class="py-2 text-end">Rasio Kemacetan</th>
                                    <th class="py-2 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ports as $port)
                                <tr class="border-bottom border-secondary border-opacity-10">
                                    <td class="text-glow-cyan fw-semibold py-2.5">{{ $port->port_code }}</td>
                                    <td class="text-white py-2.5">{{ $port->name }}</td>
                                    <td class="text-secondary py-2.5">{{ $port->country->name }}</td>
                                    <td class="text-end py-2.5 fw-bold">
                                        <span class="text-{{ $port->congestion_rate > 50 ? 'danger' : ($port->congestion_rate > 25 ? 'warning' : 'success') }}">
                                            {{ $port->congestion_rate }}%
                                        </span>
                                    </td>
                                    <td class="text-end py-2.5">
                                        <form action="{{ route('admin.ports.destroy', $port->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelabuhan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tab 4: Geopolitical Analysis Articles -->
    <div class="tab-pane fade" id="articles" role="tabpanel" aria-labelledby="articles-tab">
        <div class="row g-4">
            <!-- Add Article Form -->
            <div class="col-md-5">
                <div class="glass-card p-4">
                    <h5 class="text-white mb-3"><i class="bi bi-file-earmark-plus me-2 text-glow-purple"></i> Tulis Artikel Analisis</h5>
                    <form action="{{ route('admin.articles.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="article_title" class="form-label">Judul Artikel</label>
                            <input type="text" name="title" id="article_title" class="form-control" placeholder="Masukkan judul analisis geopolitik..." required>
                        </div>
                        <div class="mb-4">
                            <label for="article_content" class="form-label">Konten Analisis</label>
                            <textarea name="content" id="article_content" rows="8" class="form-control" placeholder="Tulis analisis mendalam mengenai rantai pasok global..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-1"></i> Publikasikan Artikel
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Articles List -->
            <div class="col-md-7">
                <div class="glass-card p-4">
                    <h5 class="text-white mb-3"><i class="bi bi-newspaper me-2 text-glow-purple"></i> Daftar Artikel Publikasi</h5>
                    <div class="list-group list-group-flush" style="max-height: 480px; overflow-y: auto;">
                        @if($articles->isEmpty())
                            <p class="text-secondary text-center py-4">Belum ada artikel yang ditulis.</p>
                        @endif
                        @foreach($articles as $art)
                        <div class="list-group-item bg-transparent text-white border-secondary border-opacity-25 px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 text-glow-cyan">{{ $art->title }}</h6>
                                    <p class="text-secondary small mb-2">{{ Str::limit($art->content, 120) }}</p>
                                    <small class="text-muted">
                                        Oleh: {{ $art->author->name }} &bull; {{ $art->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 ms-2">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
