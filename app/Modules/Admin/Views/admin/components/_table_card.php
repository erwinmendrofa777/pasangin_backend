<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th class="text-start">Nama Lengkap</th>
                        <th class="text-start">Email</th>
                        <th class="text-center" style="width: 180px;">Role</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $key => $row): ?>
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-3">
                                    <?php
                                    $avatarUrl = '';
                                    if (strpos($row['photo'] ?? '', 'http') === 0) {
                                        $avatarUrl = $row['photo'];
                                    } elseif (!empty($row['photo'])) {
                                        $avatarUrl = base_url('uploads/profile/' . $row['photo']);
                                    } else {
                                        $avatarUrl = base_url('uploads/profile/default.jpg');
                                    }
                                    ?>
                                    <a href="<?= $avatarUrl ?>" class="glightbox" data-gallery="avatar-<?= $row['id'] ?>"
                                        data-title="<?= esc($row['full_name']) ?>"
                                        data-description="Email: <?= esc($row['email'] ?: '-') ?> &lt;br&gt; Role: <?= esc($row['role'] === 'super_admin' ? 'Super Admin' : ucfirst($row['role'])) ?>">
                                        <img src="<?= $avatarUrl ?>" class="user-avatar" data-toggle="tooltip"
                                            title="<?= esc($row['full_name']) ?>">
                                    </a>
                                    <div>
                                        <span class="d-block fw-bold text-dark" style="font-size:0.9rem;"><?= esc($row['full_name'] ?: '-') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted text-start"><?= esc($row['email'] ?: '-') ?></td>
                            <td class="text-center">
                                <?php if ($row['role'] === 'super_admin'): ?>
                                    <span class="status-badge status-super"><i class="fas fa-crown me-1"></i> Super Admin</span>
                                <?php else: ?>
                                    <span class="status-badge status-default"><i class="fas fa-user-cog me-1"></i> <?= esc(ucfirst($row['role'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
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
                                                <i class="fas fa-trash-alt"></i>
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
