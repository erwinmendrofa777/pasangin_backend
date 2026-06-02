<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header: Search -->
    <div class="d-flex justify-content-between align-items-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-users me-2"></i>Daftar User
        </h6>
        <div class="search-wrapper" style="width: 320px; max-width: 100%;">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon...">
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Foto User</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Nomor Telepon</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $key => $row): ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td>
                                <?php
                                $avatarUrl = '';
                                if (strpos($row['avatar'], 'http') === 0) {
                                    $avatarUrl = $row['avatar'];
                                } elseif (!empty($row['avatar'])) {
                                    $avatarUrl = base_url('uploads/profile/' . $row['avatar']);
                                } else {
                                    $avatarUrl = base_url('uploads/profile/default.jpg');
                                }
                                ?>
                                <a href="<?= $avatarUrl ?>" class="glightbox" 
                                   data-gallery="avatar-<?= $row['id'] ?>" 
                                   data-title="<?= esc($row['full_name']) ?>" 
                                   data-description="Email: <?= esc($row['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($row['phone_number'] ?: '-') ?> &lt;br&gt; Status: <?= esc(ucfirst($row['status'])) ?>">
                                    <img src="<?= $avatarUrl ?>" class="user-avatar" data-toggle="tooltip" title="<?= esc($row['full_name']) ?>">
                                </a>
                            </td>
                            <td class="fw-semibold text-start ps-3"><?= esc($row['full_name'] ?: '-') ?></td>
                            <td class="text-muted"><?= esc($row['email'] ?: '-') ?></td>
                            <td class="text-muted"><?= esc($row['phone_number'] ?: '-') ?></td>
                            <td>
                                <?php
                                $status = $row['status'];
                                $statusMap = [
                                    'approved' => ['class' => 'status-approved', 'icon' => 'fas fa-check-circle', 'label' => 'Approved'],
                                    'pending' => ['class' => 'status-pending', 'icon' => 'fas fa-clock', 'label' => 'Pending'],
                                    'rejected' => ['class' => 'status-rejected', 'icon' => 'fas fa-times-circle', 'label' => 'Rejected'],
                                    'banned' => ['class' => 'status-banned', 'icon' => 'fas fa-ban', 'label' => 'Banned'],
                                ];
                                $s = $statusMap[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];
                                ?>
                                <span class="status-badge <?= $s['class'] ?>">
                                    <i class="<?= $s['icon'] ?>"></i><?= $s['label'] ?>
                                </span>
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
</div>
