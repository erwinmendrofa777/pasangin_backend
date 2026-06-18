<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Tukang Skill
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Skill Tukang
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .header-card {
        border: 1px solid rgba(255, 92, 92, 0.08) !important;
        border-left: 4px solid var(--palette-primary) !important;
        border-radius: 16px !important;
        box-shadow: 0 16px 36px rgba(255, 92, 92, 0.04), 0 2px 8px rgba(0,0,0,0.02) !important;
        background: #fff !important;
    }

    .skill-card {
        border-radius: 14px;
        border: 1.5px solid #f0f4fa;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }

    .skill-card:hover {
        box-shadow: 0 8px 24px rgba(255,92,92,0.08);
        border-color: rgba(255,92,92,0.15);
        transform: translateY(-2px);
    }

    .skill-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 50px;
        padding: 7px 18px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #334155;
        transition: all 0.18s ease;
        cursor: default;
    }

    .skill-badge:hover {
        background: #fff5f5;
        border-color: rgba(255,92,92,0.3);
        color: var(--palette-primary);
    }

    .skill-badge .badge-icon {
        width: 26px;
        height: 26px;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.7rem;
        flex-shrink: 0;
    }

    .btn-skill-edit {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #e0f2fe;
        color: #0284c7;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .btn-skill-edit:hover {
        background: #0284c7;
        color: #fff;
        transform: scale(1.1);
    }

    .btn-skill-delete {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #fee2e2;
        color: #dc2626;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .btn-skill-delete:hover {
        background: #dc2626;
        color: #fff;
        transform: scale(1.1);
    }

    .skill-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff;
        border-radius: 50px;
        padding: 4px 12px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 16px;
        opacity: 0.4;
    }

    .add-skill-input {
        border-radius: 12px !important;
        border: 1.5px solid #e2e8f0 !important;
        font-size: 0.9rem !important;
        transition: all 0.2s ease !important;
    }

    .add-skill-input:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 3px rgba(255,92,92,0.1) !important;
    }

    #search-skill {
        border-radius: 10px !important;
        border: 1.5px solid #e2e8f0 !important;
        font-size: 0.85rem !important;
        background: #f8fafc !important;
    }

    #search-skill:focus {
        border-color: var(--palette-primary) !important;
        box-shadow: 0 0 0 3px rgba(255,92,92,0.1) !important;
        background: #fff !important;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Header Card -->
<div class="card header-card mb-4">
    <div class="card-body py-3 px-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,var(--palette-primary),var(--palette-primary-hover));display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;flex-shrink:0;box-shadow:0 4px 12px rgba(255,92,92,0.25);">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark" style="letter-spacing:-0.3px;">Tukang Skill</h5>
                    <p class="text-muted mb-0 small">Kelola daftar skill / keahlian yang tersedia untuk tukang.</p>
                </div>
            </div>
            <span class="skill-count-badge">
                <i class="fas fa-layer-group"></i>
                <span id="skill-count"><?= count($skills) ?></span> Skill
            </span>
        </div>
    </div>
</div>

<!-- Alert messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-3" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-warning alert-dismissible fade show rounded-3 border-0 shadow-sm mb-3" role="alert">
        <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
            <div><i class="fas fa-exclamation-triangle me-1"></i> <?= esc($err) ?></div>
        <?php endforeach; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">

    <!-- LEFT: Form Tambah -->
    <div class="col-lg-4">
        <div class="skill-card p-4">
            <h6 class="fw-bold text-dark mb-1" style="font-size:0.95rem;">
                <i class="fas fa-plus-circle me-2 text-danger"></i> Tambah Skill Baru
            </h6>
            <p class="text-muted small mb-3">Ketik nama skill dan klik Simpan.</p>
            <form action="<?= base_url('admin/tukang-skill/store') ?>" method="POST" id="form-add-skill">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-bold text-secondary mb-1" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="fas fa-tag me-1 text-primary"></i> Nama Skill
                    </label>
                    <input type="text" name="skill_name" id="inp-skill-name"
                        class="form-control add-skill-input"
                        value="<?= old('skill_name') ?>"
                        placeholder="Contoh: Tukang Las, Tukang Kayu..."
                        required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary w-100" style="border-radius:10px;font-weight:600;">
                    <i class="fas fa-save me-1"></i> Simpan Skill
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT: Daftar Skill -->
    <div class="col-lg-8">
        <div class="skill-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="fw-bold text-dark mb-0" style="font-size:0.95rem;">
                    <i class="fas fa-list me-2 text-primary"></i> Daftar Skill
                </h6>
                <div style="width:220px;">
                    <input type="text" id="search-skill" class="form-control form-control-sm"
                        placeholder="&#xf002; Cari skill..." style="font-family:'Font Awesome 5 Free', sans-serif; padding-left:12px;">
                </div>
            </div>

            <?php if (empty($skills)): ?>
                <div class="empty-state">
                    <i class="fas fa-tools d-block"></i>
                    <p class="fw-bold mb-1">Belum ada skill</p>
                    <p class="small">Tambahkan skill pertama menggunakan form di sebelah kiri.</p>
                </div>
            <?php else: ?>
                <div class="d-flex flex-wrap gap-2" id="skill-list">
                    <?php foreach ($skills as $skill): ?>
                        <div class="skill-item d-flex align-items-center gap-1" data-name="<?= strtolower(esc($skill['skill_name'])) ?>">
                            <span class="skill-badge">
                                <span class="badge-icon"><i class="fas fa-wrench"></i></span>
                                <?= esc($skill['skill_name']) ?>
                            </span>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn-skill-edit" title="Edit"
                                onclick="openEditModal(<?= $skill['id'] ?>, '<?= esc($skill['skill_name'], 'js') ?>')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <!-- Tombol Hapus -->
                            <button type="button" class="btn-skill-delete" title="Hapus"
                                onclick="confirmDelete(<?= $skill['id'] ?>, '<?= esc($skill['skill_name'], 'js') ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-muted small mt-3 mb-0" id="empty-search-msg" style="display:none;">
                    <i class="fas fa-search me-1"></i> Tidak ditemukan skill yang cocok.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Edit Skill -->
<div class="modal fade" id="modalEditSkill" tabindex="-1" aria-labelledby="modalEditSkillLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header text-white" style="background:linear-gradient(135deg,var(--palette-primary),var(--palette-primary-hover));border-bottom:none;padding:18px 24px;">
                <div>
                    <h5 class="modal-title fw-bold mb-0" id="modalEditSkillLabel" style="font-size:1.05rem;font-family:'Outfit',sans-serif;">
                        <i class="fas fa-pencil-alt me-2"></i> Edit Skill
                    </h5>
                    <span class="small opacity-75 d-block mt-1" id="edit-skill-subtitle" style="font-size:0.82rem;"></span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/tukang-skill/update') ?>" method="POST" id="form-edit-skill">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit-skill-id">
                <div class="modal-body" style="padding:24px;">
                    <label class="form-label fw-bold text-secondary mb-1" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">
                        <i class="fas fa-tag me-1 text-primary"></i> Nama Skill Baru
                    </label>
                    <input type="text" name="skill_name" id="edit-skill-name"
                        class="form-control add-skill-input"
                        placeholder="Nama skill..." required>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:16px 24px;background:#fafbfc;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Batal</button>
                    <button type="submit" class="btn btn-primary" style="border-radius:8px;font-weight:600;">
                        <i class="fas fa-save me-1"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalDeleteSkill" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header text-white" style="background:linear-gradient(135deg,#dc2626,#ef4444);border-bottom:none;padding:18px 24px;">
                <h5 class="modal-title fw-bold mb-0" style="font-size:1.05rem;font-family:'Outfit',sans-serif;">
                    <i class="fas fa-trash me-2"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" style="padding:28px 24px;">
                <i class="fas fa-exclamation-triangle text-danger" style="font-size:2.5rem;margin-bottom:14px;display:block;"></i>
                <p class="fw-bold mb-1" style="font-size:1rem;">Hapus Skill?</p>
                <p class="text-muted mb-0 small">Skill "<strong id="delete-skill-name"></strong>" akan dihapus permanen dan tidak bisa dikembalikan.</p>
            </div>
            <div class="modal-footer border-0" style="padding:16px 24px;background:#fafbfc;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Batal</button>
                <a href="#" id="btn-confirm-delete" class="btn btn-danger" style="border-radius:8px;font-weight:600;">
                    <i class="fas fa-trash me-1"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Buka modal edit
    function openEditModal(id, name) {
        document.getElementById('edit-skill-id').value = id;
        document.getElementById('edit-skill-name').value = name;
        document.getElementById('edit-skill-subtitle').textContent = 'Mengubah: ' + name;
        new bootstrap.Modal(document.getElementById('modalEditSkill')).show();
    }

    // Konfirmasi hapus
    function confirmDelete(id, name) {
        document.getElementById('delete-skill-name').textContent = name;
        document.getElementById('btn-confirm-delete').href = '<?= base_url('admin/tukang-skill/delete/') ?>' + id;
        new bootstrap.Modal(document.getElementById('modalDeleteSkill')).show();
    }

    // Live search
    document.getElementById('search-skill')?.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('.skill-item').forEach(function (el) {
            const name = el.getAttribute('data-name') || '';
            const show = name.includes(q);
            el.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const emptyMsg = document.getElementById('empty-search-msg');
        if (emptyMsg) emptyMsg.style.display = visible === 0 ? 'block' : 'none';
    });
</script>
<?= $this->endSection() ?>
