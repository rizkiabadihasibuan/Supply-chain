{{-- ═══════════════════════════════════════════════════
     PORT TABLE COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/port-table.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="port-table-container border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle port-responsive-table mb-0 text-start" id="port-data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 130px;">Kode Pelabuhan</th>
                    <th class="sortable" onclick="PortsTable.sortTable(2)" style="cursor: pointer;">
                        Nama Pelabuhan <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="PortsTable.sortTable(3)" style="cursor: pointer;">
                        Negara <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="PortsTable.sortTable(4)" style="cursor: pointer;">
                        Wilayah (Region) <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th class="sortable" onclick="PortsTable.sortTable(7)" style="cursor: pointer;">
                        Status <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th>Last Updated</th>
                    <th style="width: 100px;">Action</th>
                </tr>
            </thead>
            <tbody id="port-tbody">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
