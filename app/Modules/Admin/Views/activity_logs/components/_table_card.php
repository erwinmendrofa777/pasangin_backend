<!-- Main Content -->
<div class="card main-table-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom w-100" id="activityTable">
                <thead>
                    <tr>
                        <th class="text-center" width="5">No</th>
                        <th>Waktu Kejadian</th>
                        <th>Nama & Role</th>
                        <th class="text-center">Aksi</th>
                        <th>Modul</th>
                        <th>Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?= $no++ ?></td>
                            <td class="time-cell">
                                <span class="d-none"><?= strtotime($log['created_at']) ?></span>
                                <span class="date"><?= date('d M Y', strtotime($log['created_at'])) ?></span>
                                <span class="time small"><i
                                        class="far fa-clock me-1"></i><?= date('H:i:s', strtotime($log['created_at'])) ?></span>
                            </td>
                            <td>
                                <div class="admin-profile">
                                    <div class="avatar-circle">
                                        <?php
                                        $nameParts = explode(' ', $log['admin_name'] ?? 'Admin');
                                        echo strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                        ?>
                                    </div>
                                    <div class="admin-info">
                                        <span class="name"><?= esc($log['admin_name'] ?? 'System') ?></span>
                                        <span class="role"><?= esc($log['role_name'] ?? 'Administrator') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php
                                $action = strtolower($log['action']);
                                $badgeClass = 'action-default';
                                $icon = 'info-circle';

                                switch ($action) {
                                    case 'login':
                                        $badgeClass = 'action-login';
                                        $icon = 'sign-in-alt';
                                        break;
                                    case 'logout':
                                        $badgeClass = 'action-logout';
                                        $icon = 'sign-out-alt';
                                        break;
                                    case 'create':
                                        $badgeClass = 'action-create';
                                        $icon = 'plus-circle';
                                        break;
                                    case 'update':
                                        $badgeClass = 'action-update';
                                        $icon = 'edit';
                                        break;
                                    case 'delete':
                                        $badgeClass = 'action-delete';
                                        $icon = 'trash-alt';
                                        break;
                                    case 'update_status':
                                        $badgeClass = 'action-update_status';
                                        $icon = 'toggle-on';
                                        break;
                                }
                                ?>
                                <span class="action-badge <?= $badgeClass ?>">
                                    <i class="fas fa-<?= $icon ?>"></i> <?= esc(strtoupper($log['action'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="module-badge"><?= strtoupper(esc($log['module'])) ?></span>
                            </td>
                            <td class="text-muted" style="max-width: 300px;">
                                <div class="small fw-600"><?= esc($log['description']) ?></div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
