{{-- ═══════════════════════════════════════════════════
     USER ACTION DROPDOWN COMPONENT – Milestone 3.15B
     resources/views/components/user-action-dropdown.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'name' => '',
    'email' => '',
    'role' => '',
    'status' => '',
    'joined' => '',
    'lastLogin' => '',
    'avatar' => '',
    'phone' => '',
    'company' => '',
    'country' => ''
])

<div class="dropdown">
    <button class="btn btn-light border btn-sm btn-action-trigger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Aksi
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle">
        {{-- Detail --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#userDetailModal"
                onclick="UsersModal.showDetail('{{ $name }}', '{{ $email }}', '{{ $role }}', '{{ $status }}', '{{ $joined }}', '{{ $lastLogin }}', '{{ $phone }}', '{{ $company }}', '{{ $country }}', '{{ $avatar }}')">
                <i class="bi bi-eye text-primary"></i> Detail
            </button>
        </li>
        {{-- Edit --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#userEditModal"
                onclick="UsersModal.showEdit('{{ $name }}', '{{ $email }}', '{{ $role }}', '{{ $status }}')">
                <i class="bi bi-pencil text-warning"></i> Edit
            </button>
        </li>
        {{-- Reset Password --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#userResetPasswordModal">
                <i class="bi bi-key text-info"></i> Reset Password
            </button>
        </li>
        {{-- Suspend --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#userSuspendModal">
                <i class="bi bi-slash-circle text-danger"></i> Suspend
            </button>
        </li>
        <li><hr class="dropdown-divider"></li>
        {{-- Delete --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2 text-danger" type="button" data-bs-toggle="modal" data-bs-target="#userDeleteModal">
                <i class="bi bi-trash-fill"></i> Delete
            </button>
        </li>
    </ul>
</div>
