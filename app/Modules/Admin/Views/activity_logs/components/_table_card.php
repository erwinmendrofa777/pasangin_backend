<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">No</th>
                        <th style="width: 18%">Waktu Kejadian</th>
                        <th style="width: 22%">Nama & Role</th>
                        <th class="text-center" style="width: 15%">Aksi</th>
                        <th style="width: 15%">Modul</th>
                        <th style="width: 25%">Deskripsi Aktivitas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $key => $log): ?>
                        <tr class="align-middle">
                            <td class="text-center">
                                <span class="fw-semibold text-muted" style="font-size: 0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="time-cell">
                                <span class="d-none"><?= strtotime($log['created_at']) ?></span>
                                <span class="date"><?= date('d M Y', strtotime($log['created_at'])) ?></span>
                                <span class="time small">
                                    <i class="far fa-clock me-1"></i><?= date('H:i:s', strtotime($log['created_at'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="admin-profile">
                                    <div class="avatar-circle">
                                        <?php
                                        $adminName = $log['admin_name'] ?? 'System';
                                        $nameParts = array_filter(explode(' ', trim($adminName)));
                                        $initials = '';
                                        if (!empty($nameParts)) {
                                            $first = array_shift($nameParts);
                                            $initials .= substr($first, 0, 1);
                                            if (!empty($nameParts)) {
                                                $second = array_shift($nameParts);
                                                $initials .= substr($second, 0, 1);
                                            }
                                        } else {
                                            $initials = 'S';
                                        }
                                        echo strtoupper(esc($initials));
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
                                $action = strtolower($log['action'] ?? '');
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
                                    <i class="fas fa-<?= $icon ?>"></i> <?= esc(strtoupper($log['action'] ?? 'UNKNOWN')) ?>
                                </span>
                            </td>
                            <td>
                                <span class="module-badge"><?= strtoupper(esc($log['module'] ?? '-')) ?></span>
                            </td>
                            <td>
                                <div class="small fw-semibold text-wrap" style="color: #475569; max-width: 320px; word-break: break-word;">
                                    <?= esc($log['description'] ?? '-') ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
