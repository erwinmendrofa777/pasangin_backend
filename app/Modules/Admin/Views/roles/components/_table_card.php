<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width:5%">No</th>
                        <th style="width:25%">Nama Role</th>
                        <th style="width:50%">Hak Akses</th>
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
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="fw-semibold text-dark" style="font-size:0.9rem;">
                                                <?= esc(ucwords(str_replace('_', ' ', $row['role_name']))) ?>
                                            </span>
                                            <span class="role-type-badge <?= $isSuper ? 'role-type-super' : 'role-type-custom' ?>" style="margin-top: 0 !important;">
                                                <?= $isSuper ? 'Sistem Utama' : 'Custom Role' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($isSuper): ?>
                                    <div class="perm-container">
                                        <span class="super-access-badge">
                                            <span class="badge-text"><i class="fas fa-shield-alt"></i> SUPER ADMIN</span>
                                            <span class="badge-detail"><i class="fas fa-infinity"></i> Full Access</span>
                                        </span>
                                    </div>
                                <?php elseif (!empty($perms)): ?>
                                    <?php
                                    $menuGroups = [
                                        'chat' => 'Pesan Masuk',
                                        'users' => 'Users',
                                        'suppliers' => 'Suppliers',
                                        'products' => 'Produk',
                                        'orders' => 'Order',
                                        'wallet' => 'Dompet',
                                        'admin_balance' => 'Saldo Admin',
                                        'tukang' => 'Tukang',
                                        'design' => 'Desain',
                                        'construction' => 'Konstruksi',
                                        'renovation' => 'Renovasi',
                                        'banner' => 'Banner',
                                        'banner_supplier' => 'Banner Supplier',
                                        'vouchers' => 'Voucher',
                                        'tips' => 'Tips',
                                        'promo' => 'Promo',
                                        'notification' => 'Notifikasi',
                                        'price-estimate' => 'Estimasi Harga',
                                        'syarat_ketentuan' => 'Syarat & Ketentuan',
                                        'about_application' => 'Tentang Aplikasi',
                                        'settings' => 'Pengaturan',
                                        'roles' => 'Role',
                                        'admin' => 'Kelola Admin',
                                        'dashboard' => 'Dashboard',
                                        'activity_log' => 'Log Aktivitas'
                                    ];

                                    $groupIcons = [
                                        'chat' => 'fa-comments',
                                        'users' => 'fa-users',
                                        'suppliers' => 'fa-truck-loading',
                                        'products' => 'fa-box',
                                        'orders' => 'fa-shopping-cart',
                                        'wallet' => 'fa-wallet',
                                        'admin_balance' => 'fa-hand-holding-usd',
                                        'tukang' => 'fa-tools',
                                        'design' => 'fa-pencil-ruler',
                                        'construction' => 'fa-building',
                                        'renovation' => 'fa-home',
                                        'banner' => 'fa-image',
                                        'banner_supplier' => 'fa-images',
                                        'vouchers' => 'fa-ticket-alt',
                                        'tips' => 'fa-lightbulb',
                                        'promo' => 'fa-percentage',
                                        'notification' => 'fa-bell',
                                        'price-estimate' => 'fa-calculator',
                                        'syarat_ketentuan' => 'fa-file-contract',
                                        'about_application' => 'fa-info-circle',
                                        'settings' => 'fa-cog',
                                        'roles' => 'fa-user-shield',
                                        'admin' => 'fa-user-cog',
                                        'dashboard' => 'fa-tachometer-alt',
                                        'activity_log' => 'fa-history',
                                    ];

                                    $categoryMap = [
                                        'settings' => 'badge-system',
                                        'roles' => 'badge-system',
                                        'admin' => 'badge-system',
                                        'dashboard' => 'badge-system',
                                        'activity_log' => 'badge-system',
                                        
                                        'design' => 'badge-project',
                                        'construction' => 'badge-project',
                                        'renovation' => 'badge-project',
                                        
                                        'banner' => 'badge-content',
                                        'banner_supplier' => 'badge-content',
                                        'vouchers' => 'badge-content',
                                        'tips' => 'badge-content',
                                        'promo' => 'badge-content',
                                        'notification' => 'badge-content',
                                        'price-estimate' => 'badge-content',
                                        'syarat_ketentuan' => 'badge-content',
                                        'about_application' => 'badge-content',
                                    ];

                                    // Sort keys by length descending to match longest prefixes first
                                    $groupKeys = array_keys($menuGroups);
                                    usort($groupKeys, function($a, $b) {
                                        return strlen($b) - strlen($a);
                                    });

                                    $groupedPerms = [];
                                    foreach ($perms as $p) {
                                        $matched = false;
                                        foreach ($groupKeys as $key) {
                                            if ($p === $key) {
                                                if (!isset($groupedPerms[$key])) {
                                                    $groupedPerms[$key] = ['menu' => true, 'actions' => []];
                                                } else {
                                                    $groupedPerms[$key]['menu'] = true;
                                                }
                                                $matched = true;
                                                break;
                                            } elseif (str_starts_with($p, $key . '_')) {
                                                $action = substr($p, strlen($key) + 1);
                                                if (!isset($groupedPerms[$key])) {
                                                    $groupedPerms[$key] = ['menu' => false, 'actions' => []];
                                                }
                                                $groupedPerms[$key]['actions'][] = $action;
                                                $matched = true;
                                                break;
                                            }
                                        }
                                        if (!$matched) {
                                            $key = 'other';
                                            if (!isset($groupedPerms[$key])) {
                                                $groupedPerms[$key] = ['menu' => false, 'actions' => []];
                                            }
                                            $groupedPerms[$key]['actions'][] = $p;
                                        }
                                    }

                                    $formattedGroups = [];
                                    foreach ($groupedPerms as $key => $info) {
                                        $groupName = $menuGroups[$key] ?? ucwords(str_replace('_', ' ', $key));
                                        $actions = [];
                                        if ($info['menu']) {
                                            $actions[] = 'Menu';
                                        }
                                        foreach ($info['actions'] as $act) {
                                            $actionLabel = ucwords(str_replace(['_', '-'], ' ', $act));
                                            $commonMap = [
                                                'Create' => 'Tambah',
                                                'Edit' => 'Ubah',
                                                'Delete' => 'Hapus',
                                                'Update' => 'Update',
                                                'View' => 'Lihat',
                                                'Show' => 'Detail',
                                                'Destroy' => 'Hapus',
                                                'Manage' => 'Kelola',
                                                'Status' => 'Status',
                                                'Verify' => 'Verifikasi',
                                            ];
                                            if (isset($commonMap[$actionLabel])) {
                                                $actionLabel = $commonMap[$actionLabel];
                                            }
                                            $actions[] = $actionLabel;
                                        }
                                        
                                        $formattedGroups[] = [
                                            'key' => $key,
                                            'name' => $groupName,
                                            'icon' => $groupIcons[$key] ?? 'fa-folder',
                                            'class' => $categoryMap[$key] ?? 'badge-management',
                                            'actions' => $actions
                                        ];
                                    }

                                    // Display max 2 groups, hide the rest under "+X lagi"
                                    $limit = 2;
                                    $shownGroups = array_slice($formattedGroups, 0, $limit);
                                    $moreGroups = array_slice($formattedGroups, $limit);
                                    ?>
                                    <div class="perm-container">
                                        <?php foreach ($shownGroups as $g): ?>
                                            <div class="perm-badge-group <?= $g['class'] ?>">
                                                <span class="badge-prefix">
                                                    <i class="fas <?= $g['icon'] ?>"></i>
                                                    <?= esc($g['name']) ?>
                                                </span>
                                                <?php if (!empty($g['actions'])): 
                                                    $shownActions = array_slice($g['actions'], 0, 1);
                                                    $moreActionsCount = count($g['actions']) - count($shownActions);
                                                    $suffixStr = implode(', ', $shownActions);
                                                    if ($moreActionsCount > 0) {
                                                        $suffixStr .= ', +' . $moreActionsCount;
                                                    }
                                                    ?>
                                                    <span class="badge-suffix"><?= esc($suffixStr) ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (!empty($moreGroups)): 
                                            $tooltipHtml = "<div class='tooltip-menu-list' style='text-align: left; padding: 4px;'>";
                                            foreach ($moreGroups as $g) {
                                                $actionsStr = implode(', ', $g['actions']);
                                                $tooltipHtml .= "<div style='margin-bottom: 6px; font-size: 0.75rem; white-space: nowrap;'>";
                                                $tooltipHtml .= "<i class='fas " . $g['icon'] . " me-1' style='color: #ff9f43;'></i> ";
                                                $tooltipHtml .= "<b>" . esc($g['name']) . "</b>";
                                                if (!empty($actionsStr)) {
                                                    $tooltipHtml .= ": <span style='opacity: 0.9;'>" . esc($actionsStr) . "</span>";
                                                }
                                                $tooltipHtml .= "</div>";
                                            }
                                            $tooltipHtml .= "</div>";
                                            ?>
                                            <span class="pill-more-interactive" data-toggle="tooltip" data-html="true" data-placement="top" title="<?= htmlspecialchars($tooltipHtml, ENT_QUOTES, 'UTF-8') ?>">
                                                +<?= count($moreGroups) ?> lagi
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="perm-container">
                                        <span class="badge badge-light text-muted" style="font-size:0.75rem; font-weight:600;">
                                            <i class="fas fa-exclamation-circle me-1"></i> Tidak ada izin
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($isSuper): ?>
                                    <span class="badge rounded-pill"
                                        style="background:#d1fae5; color:#065f46; font-size:0.75rem; padding:5px 12px; font-weight: 700;">
                                        ∞ Semua
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill"
                                        style="background:#fff5f5; color:var(--palette-primary-hover); font-size:0.75rem; padding:5px 12px; font-weight: 700; border: 1px solid #ffd3d3;">
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
    </div>
</div>
