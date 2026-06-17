<!-- ===== TABLE CARD ===== -->
<div class="card table-card">
    <div class="card-body">
        <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th class="text-start" style="padding-left: 20px;">Info User</th>
                        <th class="text-center" style="width: 120px;">Status</th>
                        <th class="text-center" style="width: 140px;">NIK</th>
                        <th class="text-center" style="width: 90px;">Pesanan</th>
                        <th class="text-center" style="width: 180px;">Proyek</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="text-start ps-3">
                                <div class="d-flex align-items-center gap-3">
                                    <?php
                                    $avatarUrl = '';
                                    if (strpos($row['avatar'] ?? '', 'http') === 0) {
                                        $avatarUrl = $row['avatar'];
                                    } elseif (!empty($row['avatar'])) {
                                        $avatarUrl = base_url('uploads/profile/' . $row['avatar']);
                                    } else {
                                        $avatarUrl = base_url('uploads/profile/default.jpg');
                                    }
                                    ?>
                                    <a href="<?= $avatarUrl ?>" class="glightbox" data-gallery="avatar-<?= $row['id'] ?>"
                                        data-title="<?= esc($row['full_name']) ?>"
                                        data-description="Email: <?= esc($row['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($row['phone_number'] ?: '-') ?> &lt;br&gt; Status: <?= esc(ucfirst($row['status'])) ?>">
                                        <img src="<?= $avatarUrl ?>" class="user-avatar" data-toggle="tooltip"
                                            title="<?= esc($row['full_name']) ?>">
                                    </a>
                                    <div>
                                        <span class="d-block fw-bold text-dark"
                                            style="font-size:0.9rem;"><?= esc($row['full_name'] ?: '-') ?></span>
                                        <span class="d-block text-muted" style="font-size:0.78rem; margin-top: 1px;">
                                            <i class="far fa-envelope me-1"
                                                style="width: 12px;"></i><?= esc($row['email'] ?: '-') ?>
                                        </span>
                                        <span class="d-block text-muted" style="font-size:0.78rem; margin-top: 1px;">
                                            <i class="fas fa-phone-alt me-1"
                                                style="width: 12px;"></i><?= esc($row['phone_number'] ?: '-') ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $status = $row['status'] ?? 'pending';
                                $statusMap = [
                                    'approved' => ['class' => 'status-approved', 'icon' => 'fas fa-check-circle', 'label' => 'Approved'],
                                    'pending' => ['class' => 'status-pending', 'icon' => 'fas fa-clock', 'label' => 'Pending'],
                                    'rejected' => ['class' => 'status-rejected', 'icon' => 'fas fa-times-circle', 'label' => 'Rejected'],
                                    'banned' => ['class' => 'status-banned', 'icon' => 'fas fa-ban', 'label' => 'Banned'],
                                ];
                                $s = $statusMap[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];
                                ?>
                                <span class="status-badge <?= $s['class'] ?>"><i
                                        class="<?= $s['icon'] ?> me-1"></i><?= $s['label'] ?></span>
                            </td>
                            <td>
                                <span class="font-monospace text-secondary"
                                    style="font-size:0.8rem; letter-spacing:0.3px; max-width: 140px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; display: block;">
                                    <?= esc($row['nik'] ?: '-') ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-pill"
                                    style="font-size:0.82rem; font-weight: 600;">
                                    <?= esc($row['orders_count'] ?? 0) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <span class="badge"
                                        style="background-color: rgba(79, 70, 229, 0.08); color: #4f46e5; border: 1px solid rgba(79, 70, 229, 0.2); padding: 4px 6px; border-radius: 4px; font-size: 0.72rem; font-weight: 600;"
                                        data-toggle="tooltip"
                                        title="<?= esc($row['construction_count'] ?? 0) ?> Konstruksi">
                                        <i class="fas fa-building me-1"></i><?= esc($row['construction_count'] ?? 0) ?>
                                    </span>
                                    <span class="badge"
                                        style="background-color: rgba(13, 148, 136, 0.08); color: #0d9488; border: 1px solid rgba(13, 148, 136, 0.2); padding: 4px 6px; border-radius: 4px; font-size: 0.72rem; font-weight: 600;"
                                        data-toggle="tooltip" title="<?= esc($row['design_count'] ?? 0) ?> Desain">
                                        <i class="fas fa-drafting-compass me-1"></i><?= esc($row['design_count'] ?? 0) ?>
                                    </span>
                                    <span class="badge"
                                        style="background-color: rgba(217, 119, 6, 0.08); color: #d97706; border: 1px solid rgba(217, 119, 6, 0.2); padding: 4px 6px; border-radius: 4px; font-size: 0.72rem; font-weight: 600;"
                                        data-toggle="tooltip" title="<?= esc($row['renovation_count'] ?? 0) ?> Renovasi">
                                        <i class="fas fa-tools me-1"></i><?= esc($row['renovation_count'] ?? 0) ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('users')): ?>
                                        <a href="<?= base_url('admin/users/detail/' . $row['id']) ?>"
                                            class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (can('users_edit')): ?>
                                        <a href="<?= base_url('admin/users/edit/' . $row['id']) ?>"
                                            class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit User">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>
</div>