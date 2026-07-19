{{-- ═══════════════════════════════════════════════════
     USER TABLE COMPONENT – Milestone 3.15B
     resources/views/components/user-table.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card p-4 border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle user-responsive-table mb-0 text-start" id="user-data-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Avatar</th>
                    <th class="sortable" onclick="UsersTable.sortTable(1)" style="cursor: pointer;">
                        Nama <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="UsersTable.sortTable(2)" style="cursor: pointer;">
                        Email <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="UsersTable.sortTable(3)" style="cursor: pointer;">
                        Peran <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th>Status</th>
                    <th class="sortable" onclick="UsersTable.sortTable(5)" style="cursor: pointer;">
                        Bergabung <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th>Login Terakhir</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="user-tbody">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
