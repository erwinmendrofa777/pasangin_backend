<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>Edit Role - <?= esc($role['role_name'] ?? '') ?><?= $this->endSection() ?>
<?= $this->section('page_title') ?>Edit Role<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    :root {
        --indigo: #0d6efd;
        --indigo-light: #e7f3ff;
        --indigo-mid: #90b8fd;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-700: #334155;
        --slate-900: #0f172a;
    }

    .cr-wrapper {
        max-width: 920px;
        margin: 0 auto;
    }

    .cr-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .78rem;
        font-weight: 600;
        color: var(--slate-500);
        background: #fff;
        border: 1.5px solid var(--slate-200);
        border-radius: 8px;
        padding: 6px 14px;
        text-decoration: none;
        transition: all .18s;
        margin-bottom: 18px;
    }

    .cr-back:hover {
        color: var(--indigo);
        border-color: var(--indigo-mid);
        background: var(--indigo-light);
    }

    .cr-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 20px 60px -10px rgba(13, 110, 253, 0.11);
    }

    /* Banner */
    .cr-banner {
        background: linear-gradient(130deg, #084298 0%, #0a58ca 55%, #0d6efd 100%);
        padding: 30px 36px 36px;
        position: relative;
        overflow: hidden;
    }

    .cr-banner::before,
    .cr-banner::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
    }

    .cr-banner::before {
        width: 260px;
        height: 260px;
        top: -120px;
        right: -60px;
    }

    .cr-banner::after {
        width: 180px;
        height: 180px;
        bottom: -90px;
        left: 40px;
    }

    .cr-banner-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .cr-banner-ico {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.12);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cr-banner-ico i {
        color: #bfdbfe;
        font-size: 1.2rem;
    }

    .cr-banner h4 {
        color: #fff;
        font-weight: 800;
        font-size: 1.2rem;
        margin: 0 0 2px;
    }

    .cr-banner p {
        color: #93c5fd;
        font-size: .8rem;
        margin: 0;
    }

    .cr-body {
        padding: 28px 32px 32px;
    }

    .sec-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .7rem;
        font-weight: 700;
        color: var(--indigo);
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 12px;
    }

    .sec-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, #dbeafe, transparent);
    }

    .cr-label {
        font-size: .78rem;
        font-weight: 700;
        color: var(--slate-700);
        margin-bottom: 8px;
        display: block;
    }

    .cr-label span {
        color: #ef4444;
    }

    .cr-input-wrap {
        display: flex;
        align-items: center;
        background: var(--slate-50);
        border: 1.5px solid var(--slate-200);
        border-radius: 12px;
        overflow: hidden;
        transition: all .2s;
    }

    .cr-input-wrap:focus-within {
        border-color: var(--indigo);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, .1);
    }

    .cr-input-wrap .ico {
        padding: 0 14px;
        color: var(--slate-400);
        font-size: .85rem;
    }

    .cr-input-wrap:focus-within .ico {
        color: var(--indigo);
    }

    .cr-input-wrap input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        padding: 11px 14px 11px 0;
        font-size: .88rem;
        font-weight: 500;
        color: var(--slate-900);
    }

    .cr-input-wrap input::placeholder {
        color: var(--slate-400);
        font-weight: 400;
    }

    .cr-hint {
        font-size: .72rem;
        color: var(--slate-400);
        margin-top: 6px;
    }

    .cr-divider {
        height: 1px;
        background: var(--slate-100);
        margin: 24px 0;
    }

    /* Global counter bar */
    .cr-global-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--slate-50);
        border: 1.5px solid var(--slate-200);
        border-radius: 10px;
        padding: 9px 16px;
        margin-bottom: 14px;
    }

    .cr-global-bar .gcb-label {
        font-size: .75rem;
        font-weight: 600;
        color: var(--slate-500);
    }

    .cr-global-bar .gcb-count {
        font-size: .78rem;
        font-weight: 800;
        color: var(--indigo);
    }

    /* Permission Panel */
    .perm-panel {
        border: 1.5px solid var(--slate-200);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        min-height: 380px;
    }

    /* Sidebar */
    .perm-sidebar {
        width: 195px;
        flex-shrink: 0;
        background: var(--slate-50);
        border-right: 1.5px solid var(--slate-200);
        display: flex;
        flex-direction: column;
    }

    .perm-sidebar-head {
        padding: 13px 16px 10px;
        font-size: .65rem;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: .8px;
        border-bottom: 1px solid var(--slate-200);
    }

    .perm-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: all .15s;
    }

    .perm-tab:hover {
        background: #f0effe;
    }

    .perm-tab.active {
        background: #fff;
        border-left-color: var(--indigo);
        box-shadow: inset -1px 0 0 var(--slate-200);
    }

    .perm-tab .tab-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: var(--slate-200);
        color: var(--slate-500);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        flex-shrink: 0;
        transition: all .15s;
    }

    .perm-tab.active .tab-icon {
        background: var(--indigo-light);
        color: var(--indigo);
    }

    .perm-tab .tab-info {
        flex: 1;
        min-width: 0;
    }

    .perm-tab .tab-name {
        font-size: .77rem;
        font-weight: 700;
        color: var(--slate-700);
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .perm-tab.active .tab-name {
        color: var(--indigo);
    }

    .perm-tab .tab-badge {
        font-size: .62rem;
        font-weight: 700;
        color: var(--slate-400);
        display: block;
        margin-top: 1px;
    }

    .perm-tab.active .tab-badge {
        color: var(--indigo);
    }

    .perm-sidebar-footer {
        margin-top: auto;
        padding: 12px 14px;
        border-top: 1px solid var(--slate-200);
    }

    .btn-select-all {
        width: 100%;
        font-size: .72rem;
        font-weight: 700;
        color: var(--indigo);
        background: var(--indigo-light);
        border: none;
        border-radius: 8px;
        padding: 7px 10px;
        cursor: pointer;
        transition: all .15s;
    }

    .btn-select-all:hover {
        background: var(--indigo);
        color: #fff;
    }

    /* Content panes */
    .perm-content {
        flex: 1;
        overflow-y: auto;
        padding: 20px 22px;
        max-height: 420px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .pane-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .pane-title {
        font-size: .78rem;
        font-weight: 800;
        color: var(--slate-700);
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .pane-counter {
        font-size: .68rem;
        font-weight: 700;
        color: var(--indigo);
        background: var(--indigo-light);
        padding: 2px 9px;
        border-radius: 20px;
    }

    /* Parent card */
    .perm-parent-card {
        border: 1.5px solid var(--slate-200);
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        transition: border-color .15s;
        background: #fff;
    }

    .perm-parent-card.has-checked {
        border-color: #bfdbfe;
    }

    .perm-parent-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
    }

    .perm-parent-row label {
        font-size: .82rem;
        font-weight: 700;
        color: var(--slate-700);
        cursor: pointer;
        flex: 1;
        margin: 0;
    }

    .perm-children {
        padding: 0 14px 13px 42px;
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .perm-standalone {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    /* Checkbox */
    .cr-check {
        position: relative;
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .cr-check input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .cr-check .cm {
        position: absolute;
        inset: 0;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cr-check input:checked~.cm {
        background: var(--indigo);
        border-color: var(--indigo);
    }

    .cr-check .cm::after {
        content: '';
        width: 4px;
        height: 7px;
        border: 2px solid #fff;
        border-top: none;
        border-left: none;
        transform: rotate(45deg) translate(-1px, -1px);
        opacity: 0;
        transition: opacity .12s;
    }

    .cr-check input:checked~.cm::after {
        opacity: 1;
    }

    /* Pill */
    .perm-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 11px;
        background: var(--slate-50);
        border: 1.5px solid var(--slate-200);
        border-radius: 8px;
        cursor: pointer;
        transition: all .14s;
        user-select: none;
    }

    .perm-pill:hover {
        border-color: var(--indigo-mid);
        background: #f5f3ff;
    }

    .perm-pill input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .perm-pill .pi {
        width: 13px;
        height: 13px;
        border-radius: 4px;
        border: 1.5px solid #cbd5e1;
        background: #fff;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .14s;
        position: relative;
    }

    .perm-pill .pi::after {
        content: '';
        width: 3px;
        height: 5px;
        border: 1.5px solid transparent;
        border-top: none;
        border-left: none;
        transform: rotate(45deg) translate(-1px, -1px);
        transition: all .12s;
    }

    .perm-pill.on {
        background: var(--indigo-light);
        border-color: #a78bfa;
    }

    .perm-pill.on .pi {
        background: var(--indigo);
        border-color: var(--indigo);
    }

    .perm-pill.on .pi::after {
        border-color: #fff;
    }

    .perm-pill span {
        font-size: .75rem;
        font-weight: 600;
        color: var(--slate-500);
    }

    .perm-pill.on span {
        color: #0a58ca;
    }

    .parent-count {
        font-size: .65rem;
        font-weight: 700;
        background: var(--slate-100);
        color: var(--slate-500);
        padding: 2px 8px;
        border-radius: 20px;
        flex-shrink: 0;
    }

    .perm-parent-card.has-checked .parent-count {
        background: var(--indigo-light);
        color: var(--indigo);
    }

    /* Actions */
    .cr-actions {
        display: flex;
        gap: 12px;
        padding-top: 8px;
    }

    .btn-cr-save {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, #0a58ca, #3b82f6);
        color: #fff;
        font-weight: 700;
        font-size: .88rem;
        border: none;
        border-radius: 12px;
        padding: 13px 24px;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 14px rgba(13, 110, 253, .35);
    }

    .btn-cr-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(13, 110, 253, .45);
        color: #fff;
    }

    .btn-cr-cancel {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fff;
        color: var(--slate-500);
        font-weight: 700;
        font-size: .88rem;
        border: 1.5px solid var(--slate-200);
        border-radius: 12px;
        padding: 13px 24px;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        min-width: 130px;
    }

    .btn-cr-cancel:hover {
        border-color: #fda4af;
        color: #e11d48;
        background: #fff1f2;
    }

    .perm-content::-webkit-scrollbar {
        width: 5px;
    }

    .perm-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .perm-content::-webkit-scrollbar-thumb {
        background: var(--slate-200);
        border-radius: 4px;
    }

    .perm-content::-webkit-scrollbar-thumb:hover {
        background: var(--indigo-mid);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="cr-wrapper">

    <a href="<?= base_url('admin/roles') ?>" class="cr-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Role
    </a>

    <form id="edit-role-form" method="POST" action="<?= base_url('admin/roles/update/' . $role['id']) ?>">
        <?= csrf_field() ?>

        <div class="cr-card">

            <div class="cr-banner">
                <div class="cr-banner-inner">
                    <div class="cr-banner-ico"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h4>Edit Role</h4>
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

                <?php
                // Pre-calculate total
                $grandTotal = 0;
                foreach ($available_menus as $menus) {
                    foreach ($menus as $k => $v) {
                        $grandTotal++;
                        if (is_array($v)) $grandTotal += count($v['actions']);
                    }
                }
                $groupIcons = [
                    'MANAJEMEN'  => 'fa-users-cog',
                    'PROYEK'     => 'fa-hard-hat',
                    'KONTEN'     => 'fa-layer-group',
                    'AKSES'      => 'fa-sliders-h',
                ];
                $groupKeys = array_keys($available_menus);
                $firstGroup = $groupKeys[0] ?? '';
                
                $rolePermissions = [];
                if (!empty($role['permissions'])) {
                    $rolePermissions = json_decode($role['permissions'], true);
                    if (!is_array($rolePermissions)) $rolePermissions = [];
                }
                ?>

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
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 6000,
            title: 'Gagal',
            message: '<?= strip_tags(session()->getFlashdata('error')) ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {

        /* ── Ladda ── */
        const form = document.getElementById('edit-role-form');
        if (form) {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('.ladda-button');
                if (btn) Ladda.create(btn).start();
            });
        }

        /* ── Pill visual sync ── */
        function syncPill(pill) {
            const cb = pill.querySelector('input[type="checkbox"]');
            if (cb) pill.classList.toggle('on', cb.checked);
        }

        function initPills() {
            document.querySelectorAll('.perm-pill').forEach(pill => {
                syncPill(pill);
                const cb = pill.querySelector('input');
                if (cb) cb.addEventListener('change', () => {
                    syncPill(pill);
                    updateAll();
                });
            });
        }

        /* ── Update counters ── */
        const grandTotal = <?= $grandTotal ?>;

        function updateAll() {
            let globalChecked = 0;

            document.querySelectorAll('.tab-pane').forEach(pane => {
                const id = pane.id;
                const checked = pane.querySelectorAll('.perm-cb:checked').length;
                const total = pane.querySelectorAll('.perm-cb').length;
                globalChecked += checked;

                const pc = document.getElementById('pane-counter-' + id);
                if (pc) pc.textContent = checked + ' dipilih';

                const badge = document.getElementById('badge-' + id);
                if (badge) badge.textContent = checked + ' / ' + total;
            });

            document.querySelectorAll('.parent-cb').forEach(parent => {
                const key = parent.id.replace('perm_', '');
                const children = document.querySelectorAll(`.child-cb[data-parent="${parent.id}"]`);
                const chChecked = document.querySelectorAll(`.child-cb[data-parent="${parent.id}"]:checked`).length;

                const cntEl = document.getElementById('cnt_' + key);
                if (cntEl) cntEl.textContent = chChecked + ' / ' + children.length;

                const card = document.getElementById('card_' + key);
                if (card) card.classList.toggle('has-checked', parent.checked || chChecked > 0);
            });

            document.getElementById('globalCount').textContent = globalChecked + ' dari ' + grandTotal;
        }

        /* ── Parent → children ── */
        document.querySelectorAll('.parent-cb').forEach(parent => {
            parent.addEventListener('change', function() {
                document.querySelectorAll(`.child-cb[data-parent="${this.id}"]`).forEach(child => {
                    child.checked = this.checked;
                    const pill = child.closest('.perm-pill');
                    if (pill) syncPill(pill);
                });
                updateAll();
            });
        });

        /* ── Child → parent ── */
        document.querySelectorAll('.child-cb').forEach(child => {
            child.addEventListener('change', function() {
                if (this.checked) {
                    const parent = document.getElementById(this.getAttribute('data-parent'));
                    if (parent) parent.checked = true;
                }
                updateAll();
            });
        });

        /* ── Tab switching ── */
        document.querySelectorAll('.perm-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.perm-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                const target = document.getElementById(this.dataset.target);
                if (target) target.classList.add('active');
            });
        });

        /* ── Select All / Clear All ── */
        let allOn = false;
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            allOn = !allOn;
            document.querySelectorAll('.perm-cb').forEach(cb => {
                cb.checked = allOn;
                const pill = cb.closest('.perm-pill');
                if (pill) syncPill(pill);
            });
            this.textContent = allOn ? '✕ Hapus Semua' : '☑ Pilih Semua';
            updateAll();
        });

        initPills();
        updateAll();
    });
</script>
<?= $this->endSection() ?>