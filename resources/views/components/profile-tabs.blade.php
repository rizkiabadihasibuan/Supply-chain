{{-- ═══════════════════════════════════════════════════
     PROFILE TABS COMPONENT – Milestone 3.13
     resources/views/components/profile-tabs.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="profile-nav-pills mt-4">
    <button class="nav-link active" id="tab-overview" onclick="ProfileTabs.switchTab('overview', this)">
        <i class="bi bi-person-circle"></i> Overview
    </button>
    <button class="nav-link" id="tab-edit-profile" onclick="ProfileTabs.switchTab('edit-profile', this)">
        <i class="bi bi-person-vcard"></i> Personal Information
    </button>
    <button class="nav-link" id="tab-security" onclick="ProfileTabs.switchTab('security', this)">
        <i class="bi bi-shield-lock"></i> Security
    </button>
    <button class="nav-link" id="tab-notification" onclick="ProfileTabs.switchTab('notification', this)">
        <i class="bi bi-bell"></i> Notifications
    </button>
    <button class="nav-link" id="tab-preferences" onclick="ProfileTabs.switchTab('preferences', this)">
        <i class="bi bi-sliders"></i> Preferences
    </button>
    <button class="nav-link" id="tab-activity" onclick="ProfileTabs.switchTab('activity', this)">
        <i class="bi bi-clock-history"></i> Activity Log
    </button>
</div>
