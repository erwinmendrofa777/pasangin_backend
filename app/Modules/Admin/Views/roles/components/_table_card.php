<div class="card table-card">

    <!-- Card Header -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary"
            style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-user-shield me-2"></i>Daftar Role
        </h6>
        <div class="d-flex gap-3 align-items-center">
            <div class="search-wrapper" style="width: 260px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama role...">
            </div>
            <?php if (can('roles_create')): ?>
                <a href="<?= base_url('admin/roles/create') ?>"
                    class="btn btn-primary rounded-pill px-3 mx-3 shadow-sm d-flex align-items-center gap-2"
                    style="font-size:0.85rem; white-space:nowrap;">
                    <i class="fas fa-plus"></i> Tambah Role
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">No</th>
                        <th style="width:22%">Nama Role</th>
                        <th style="width:53%">Hak Akses</th>
                        <th class="text-center" style="width:10%">Jumlah</th>
                        <th class="text-center" style="width:10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $key => $row):
                        $isSuper = strtolower($row['role_name']) === 'super_admin';
                        $perms = json_decode($row['permissions'], true) ?? [];
                        $shown = array_slice($perms, 0, 4);
                        $more = count($perms) - count($shown);
                        ?>
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="role-icon-wrap <?= $isSuper ? 'role-icon-super' : 'role-icon-custom' ?>">
                                        <i class="fas <?= $isSuper ? 'fa-crown' : 'fa-user-tag' ?>"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size:0.9rem;">
                                            <?= esc(ucwords(str_replace('_', ' ', $row['role_name']))) ?>
                                        </div>
                                        <span
                                            class="role-type-badge <?= $isSuper ? 'role-type-super' : 'role-type-custom' ?>">
                                            <?= $isSuper ? 'Sistem Utama' : 'Custom Role' ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($isSuper): ?>
                                    <span class="perm-pill pill-full">
                                        <i class="fas fa-infinity"></i> Full Access — Semua izin aktif
                                    </span>
                                <?php elseif (!empty($shown)): ?>
                                    <?php foreach ($shown as $p):
                                        $isParent = !str_contains($p, '_');
                                        ?>
                                        <span class="perm-pill <?= $isParent ? 'pill-parent' : 'pill-action' ?>">
                                            <i class="fas <?= $isParent ? 'fa-folder' : 'fa-check' ?>"
                                                style="font-size:0.55rem;"></i>
                                            <?= esc($p) ?>
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if ($more > 0): ?>
                                        <span class="pill-more">+<?= $more ?> lagi</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <em class="text-muted" style="font-size:0.8rem;">Tidak ada izin</em>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($isSuper): ?>
                                    <span class="badge rounded-pill"
                                        style="background:#d1fae5; color:#065f46; font-size:0.75rem; padding:5px 12px;">
                                        ∞ Semua
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill"
                                        style="background:#e7f3ff; color:var(--palette-primary-hover); font-size:0.75rem; padding:5px 12px;">
                                        <?= count($perms) ?> izin
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('roles_edit')): ?>
                                        <a href="<?= base_url('admin/roles/edit/' . $row['id']) ?>"
                                            class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit Role">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($isSuper): ?>
                                        <div class="btn-action btn-action-lock" data-toggle="tooltip"
                                            title="Role Sistem (Terkunci)">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    <?php elseif (can('roles_delete')): ?>
                                        <a href="<?= base_url('admin/roles/delete/' . $row['id']) ?>"
                                            class="btn-action btn-action-delete"
                                            onclick="return confirm('Yakin hapus role \'<?= esc($row['role_name']) ?>\'?\nAdmin yang menggunakan role ini akan kehilangan akses.')"
                                            data-toggle="tooltip" title="Hapus Role">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="dt-footer px-4 py-3 d-flex justify-content-between align-items-center border-top">
            <div id="table-info-custom" class="text-muted" style="font-size: 0.85rem;"></div>
            <div id="table-pagination-custom"></div>
        </div>
    </div>
</div>
