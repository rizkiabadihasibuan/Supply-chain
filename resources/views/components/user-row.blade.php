{{-- ═══════════════════════════════════════════════════
     USER ROW COMPONENT – Milestone 3.15B
     resources/views/components/user-row.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'name' => '',
    'email' => '',
    'role' => 'User',
    'status' => 'Active',
    'joined' => '',
    'lastLogin' => '',
    'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100&auto=format&fit=crop',
    'phone' => '',
    'company' => '',
    'country' => ''
])

<tr>
    <td data-label="Avatar" class="td-avatar">
        <img src="{{ $avatar }}" alt="User Avatar" class="user-table-avatar">
    </td>
    <td data-label="Nama" class="fw-bold text-dark text-start">
        {{ $name }}
    </td>
    <td data-label="Email" class="text-secondary small text-start">
        {{ $email }}
    </td>
    <td data-label="Peran" class="text-start">
        @if($role === 'Admin')
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 0.65rem;">Admin</span>
        @else
            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25" style="font-size: 0.65rem;">User</span>
        @endif
    </td>
    <td data-label="Status">
        @if($status === 'Active')
            <span class="badge badge-status-active">Active</span>
        @elseif($status === 'Inactive')
            <span class="badge badge-status-inactive">Inactive</span>
        @else
            <span class="badge badge-status-suspended">Suspended</span>
        @endif
    </td>
    <td data-label="Bergabung" class="text-secondary small">
        {{ $joined }}
    </td>
    <td data-label="Login Terakhir" class="text-secondary small">
        {{ $lastLogin }}
    </td>
    <td data-label="Aksi">
        <x-user-action-dropdown 
            :name="$name" 
            :email="$email" 
            :role="$role" 
            :status="$status" 
            :joined="$joined" 
            :lastLogin="$lastLogin" 
            :avatar="$avatar"
            :phone="$phone"
            :company="$company"
            :country="$country"
        />
    </td>
</tr>
