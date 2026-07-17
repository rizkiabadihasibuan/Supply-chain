@props([
    'activeTab' => 'overview'
])

<div class="card p-3 border-0 d-flex flex-column gap-1.5" style="border-radius: var(--radius-custom) !important;">
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'overview') active @endif" onclick="switchSettingsTab('overview', this)">
        <i class="bi bi-person-fill me-2.5"></i>Ringkasan Akun
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'edit-profile') active @endif" onclick="switchSettingsTab('edit-profile', this)">
        <i class="bi bi-pencil-square me-2.5"></i>Ubah Profil
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'security') active @endif" onclick="switchSettingsTab('security', this)">
        <i class="bi bi-shield-lock-fill me-2.5"></i>Keamanan Sandi
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'notification') active @endif" onclick="switchSettingsTab('notification', this)">
        <i class="bi bi-bell-fill me-2.5"></i>Notifikasi & Alert
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'appearance') active @endif" onclick="switchSettingsTab('appearance', this)">
        <i class="bi bi-palette-fill me-2.5"></i>Tema & Tampilan
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'language') active @endif" onclick="switchSettingsTab('language', this)">
        <i class="bi bi-translate me-2.5"></i>Bahasa (Language)
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'regional') active @endif" onclick="switchSettingsTab('regional', this)">
        <i class="bi bi-globe me-2.5"></i>Regional & Format
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'session') active @endif" onclick="switchSettingsTab('session', this)">
        <i class="bi bi-pc-display-horizontal me-2.5"></i>Sesi Perangkat
    </button>
    <button class="settings-menu-item text-start border-0 py-2 px-3 rounded-3 @if($activeTab === 'privacy') active @endif" onclick="switchSettingsTab('privacy', this)">
        <i class="bi bi-eye-slash-fill me-2.5"></i>Privasi Data
    </button>
</div>

<style>
    .settings-menu-item {
        background: transparent;
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s;
        width: 100%;
        min-height: 40px;
    }
    .settings-menu-item i {
        color: #94A3B8;
        transition: color 0.2s;
    }
    .settings-menu-item:hover {
        background-color: #F1F5F9;
        color: var(--text-primary);
    }
    .settings-menu-item.active {
        background-color: var(--primary) !important;
        color: #FFFFFF !important;
        font-weight: 600;
    }
    .settings-menu-item.active i {
        color: #FFFFFF !important;
    }
</style>
