{{-- ═══════════════════════════════════════════════════
     ARTICLE TABLE COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/article-table.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="article-table-container border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle article-responsive-table mb-0 text-start" id="article-data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 100px;">Thumbnail</th>
                    <th class="sortable" onclick="ArticlesTable.sortTable(2)" style="cursor: pointer;">
                        Judul Artikel <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="ArticlesTable.sortTable(3)" style="cursor: pointer;">
                        Kategori <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="ArticlesTable.sortTable(4)" style="cursor: pointer;">
                        Penulis <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="ArticlesTable.sortTable(5)" style="cursor: pointer;">
                        Tanggal Publish <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="ArticlesTable.sortTable(6)" style="cursor: pointer;">
                        Status <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th class="sortable" onclick="ArticlesTable.sortTable(7)" style="cursor: pointer;">
                        Views <i class="bi bi-arrow-down-up sort-icon ms-1 text-secondary" style="font-size:0.75rem;"></i>
                    </th>
                    <th style="width: 100px;">Action</th>
                </tr>
            </thead>
            <tbody id="article-tbody">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
