<div class="card table-card">
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-bottom: 1px solid #f0f4fa;">
        <h6 class="mb-0 fw-bold text-primary"
            style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-user-tie me-2"></i>Daftar Admin
        </h6>
        <div class="d-flex gap-3 align-items-center">
            <div class="search-wrapper" style="width: 250px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, role...">
            </div>
            <?php if (can('admin_create')): ?>
                <a href="<?= base_url('admin/admin/create') ?>"
                    class="btn btn-primary rounded-pill px-3 shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Admin
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Lengkap</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td><span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span></td>
                            <td class="fw-semibold text-start ps-3"><?= esc($row['full_name'] ?: '-') ?></td>
                            <td class="text-muted"><?= esc($row['email'] ?: '-') ?></td>
                            <td>
                                <?php if ($row['role'] === 'super_admin'): ?>
                                    <span class="status-badge status-super"><i class="fas fa-crown"></i> Super Admin</span>
                                <?php else: ?>
                                    <span class="status-badge status-default"><i class="fas fa-user-cog"></i>
                                        <?= esc(ucfirst($row['role'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('admin_edit')): ?>
                                        <a href="<?= base_url('admin/admin/edit/' . $row['id']) ?>"
                                            class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit Admin">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (can('admin_delete')): ?>
                                        <?php if ($row['id'] != session()->get('user_id') && $row['id'] != 1): ?>
                                            <a href="<?= base_url('admin/admin/delete/' . $row['id']) ?>"
                                                class="btn-action btn-action-delete"
                                                onclick="return confirm('Yakin ingin menghapus admin ini?')" data-toggle="tooltip"
                                                title="Hapus Admin">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if (!can('admin_edit') && !can('admin_delete')): ?>
                                        <span class="badge badge-light"><i class="fas fa-lock"></i></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
