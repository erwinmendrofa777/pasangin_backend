<?php
// Variables computed here since $this->include() does not inherit inline view variables
$grandTotal = 0;
foreach ($available_menus as $menus) {
    foreach ($menus as $k => $v) {
        $grandTotal++;
        if (is_array($v)) $grandTotal += count($v['actions']);
    }
}
$groupIcons = [
    'MANAJEMEN' => 'fa-users-cog',
    'PROYEK'    => 'fa-hard-hat',
    'KONTEN'    => 'fa-layer-group',
    'AKSES'     => 'fa-sliders-h',
];
$groupKeys  = array_keys($available_menus);
$firstGroup = $groupKeys[0] ?? '';

$rolePermissions = [];
if (!empty($role['permissions'])) {
    $rolePermissions = json_decode($role['permissions'], true);
    if (!is_array($rolePermissions)) $rolePermissions = [];
}
?>
<form id="edit-role-form" method="POST" action="<?= base_url('admin/roles/update/' . $role['id']) ?>">
    <?= csrf_field() ?>

    <div class="cr-card">

        <div class="cr-banner">
            <div class="cr-banner-inner">
                <div class="cr-banner-ico"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h4 class="text-white">Edit Role</h4>
                    <p>Ubah nama role atau hak akses</p>
                </div>
            </div>
        </div>

        <div class="cr-body">

            <!-- Nama Role -->
            <div class="sec-label"><i class="fas fa-id-badge"></i> Informasi Role</div>
            <label for="role_name" class="cr-label">Nama Role <span>*</span></label>
            <div class="cr-input-wrap">
                <span class="ico"><i class="fas fa-user-tag"></i></span>
                <input type="text" id="role_name" name="role_name"
                    value="<?= old('role_name', $role['role_name'] ?? '') ?>"
                    placeholder="Contoh: admin_gudang, admin_cs" required>
            </div>
            <p class="cr-hint"><i class="fas fa-info-circle me-1"></i>Disarankan huruf kecil, tanpa spasi, pakai underscore sebagai pemisah kata.</p>

            <div class="cr-divider"></div>

            <!-- Hak Akses -->
            <div class="sec-label"><i class="fas fa-lock-open"></i> Hak Akses Menu</div>

            <!-- Global bar -->
            <div class="cr-global-bar">
                <span class="gcb-label"><i class="fas fa-check-circle me-2" style="color:var(--indigo);"></i>Total hak akses dipilih</span>
                <span class="gcb-count" id="globalCount">0 dari <?= $grandTotal ?></span>
            </div>

            <!-- Tab Panel -->
            <div class="perm-panel">

                <!-- Sidebar -->
                <div class="perm-sidebar">
                    <div class="perm-sidebar-head">Kategori Menu</div>

                    <?php foreach ($available_menus as $groupName => $menus): ?>
                        <?php
                        $groupId = 'tab-' . strtolower(preg_replace('/[^a-z0-9]/i', '-', $groupName));
                        $icon = $groupIcons[$groupName] ?? 'fa-folder';
                        $groupTotal = 0;
                        foreach ($menus as $k => $v) {
                            $groupTotal++;
                            if (is_array($v)) $groupTotal += count($v['actions']);
                        }
                        ?>
                        <div class="perm-tab <?= $groupName === $firstGroup ? 'active' : '' ?>"
                            data-target="<?= $groupId ?>">
                            <div class="tab-icon"><i class="fas <?= $icon ?>"></i></div>
                            <div class="tab-info">
                                <span class="tab-name"><?= esc($groupName) ?></span>
                                <span class="tab-badge" id="badge-<?= $groupId ?>">0 / <?= $groupTotal ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="perm-sidebar-footer">
                        <button type="button" class="btn-select-all" id="selectAllBtn">☑ Pilih Semua</button>
                    </div>
                </div>

                <!-- Content -->
                <div class="perm-content">
                    <?php foreach ($available_menus as $groupName => $menus): ?>
                        <?php
                        $groupId = 'tab-' . strtolower(preg_replace('/[^a-z0-9]/i', '-', $groupName));
                        $standalones = [];
                        $parents = [];
                        foreach ($menus as $key => $value) {
                            if (is_array($value)) $parents[$key] = $value;
                            else $standalones[$key] = $value;
                        }
                        ?>
                        <div class="tab-pane <?= $groupName === $firstGroup ? 'active' : '' ?>" id="<?= $groupId ?>">

                            <div class="pane-header">
                                <span class="pane-title"><?= esc($groupName) ?></span>
                                <span class="pane-counter" id="pane-counter-<?= $groupId ?>">0 dipilih</span>
                            </div>

                            <?php if (!empty($standalones)): ?>
                                <div class="perm-standalone">
                                    <?php foreach ($standalones as $key => $label): ?>
                                        <label class="perm-pill" for="perm_<?= $key ?>">
                                            <input type="checkbox" class="perm-cb" id="perm_<?= $key ?>"
                                                name="permissions[]" value="<?= $key ?>" data-group="<?= $groupId ?>"
                                                <?= in_array($key, $rolePermissions) ? 'checked' : '' ?>>
                                            <span class="pi"></span>
                                            <span><?= esc($label) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($parents as $key => $value): ?>
                                <div class="perm-parent-card" id="card_<?= $key ?>">
                                    <div class="perm-parent-row">
                                        <div class="cr-check">
                                            <input type="checkbox" class="perm-cb parent-cb"
                                                id="perm_<?= $key ?>" name="permissions[]" value="<?= $key ?>"
                                                data-group="<?= $groupId ?>"
                                                <?= in_array($key, $rolePermissions) ? 'checked' : '' ?>>
                                            <span class="cm"></span>
                                        </div>
                                        <label for="perm_<?= $key ?>"><?= esc($value['label']) ?></label>
                                        <span class="parent-count" id="cnt_<?= $key ?>">0 / <?= count($value['actions']) ?></span>
                                    </div>
                                    <div class="perm-children">
                                        <?php foreach ($value['actions'] as $actKey => $actLabel): ?>
                                            <label class="perm-pill" for="perm_<?= $actKey ?>">
                                                <input type="checkbox" class="perm-cb child-cb"
                                                    id="perm_<?= $actKey ?>" name="permissions[]" value="<?= $actKey ?>"
                                                    data-parent="perm_<?= $key ?>" data-group="<?= $groupId ?>"
                                                    <?= in_array($actKey, $rolePermissions) ? 'checked' : '' ?>>
                                                <span class="pi"></span>
                                                <span><?= esc($actLabel) ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php endforeach; ?>
                </div>

            </div><!-- /perm-panel -->

            <div class="cr-divider"></div>

            <div class="cr-actions">
                <?php if (can('roles_edit')): ?>
                <button type="submit" class="btn-cr-save ladda-button" data-style="zoom-out">
                    <i class="fas fa-save"></i>
                    <span class="ladda-label">Simpan Role</span>
                </button>
                <?php else: ?>
                <button type="button" class="btn-cr-save" style="background: var(--slate-400); cursor: not-allowed;" disabled>
                    <i class="fas fa-lock"></i>
                    <span>Akses Ditolak</span>
                </button>
                <?php endif; ?>
                <a href="<?= base_url('admin/roles') ?>" class="btn-cr-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

        </div>
    </div>
</form>
