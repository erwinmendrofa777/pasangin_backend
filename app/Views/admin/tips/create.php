<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Konten
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .edit-hero {
        background: #0d6efd;
        border-radius: 16px 16px 0 0;
        padding: 28px 28px 72px;
        position: relative;
        overflow: hidden;
    }

    .edit-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .edit-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== IMAGE PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -65px;
    }

    .tips-preview-img {
        width: 220px;
        height: 130px;
        object-fit: cover;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    .tips-placeholder {
        width: 220px;
        height: 130px;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #6ea8fe, #0d6efd);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    /* ===== CARDS ===== */
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(13, 110, 253, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .edit-body {
        padding: 0 28px 28px;
    }

    .section-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(13, 110, 253, 0.08), 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .section-card .card-header {
        background: #f0f6ff;
        border-bottom: 1px solid #dce8ff;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }

    .section-card .card-header h6 {
        color: #0d6efd;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
    }

    /* ===== FORM INPUTS ===== */
    .form-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #495057;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #dee2e6;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1.5px solid #dee2e6;
        border-right: none;
        background: #f8f9fa;
        color: #0d6efd;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    .btn-save {
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 700;
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        border: none;
        color: #fff;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        color: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/tips') ?>" class="btn btn-secondary btn-sm px-3 shadow-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<form id="create-tips-form" method="POST" action="<?= base_url('admin/tips/store') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">Tambah Tips & Tricks Baru</h5>
                            <p class="text-white-50 small mb-0">Bagikan ilmu dan tips menarik untuk pengguna</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2" style="border-radius:50px; font-size:0.78rem; font-weight:700;">
                            <i class="fas fa-plus me-1 opacity-75"></i>DATA BARU
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar/Image -->
                    <div class="avatar-wrapper">
                        <div class="tips-placeholder" id="img-preview-placeholder">
                            <i class="fas fa-lightbulb fa-2x mb-1"></i>
                            <span style="font-size: 0.7rem; font-weight: 700; opacity: 0.8;">PRATINJAU GAMBAR</span>
                        </div>
                        <img src="" alt="Preview" class="tips-preview-img d-none" id="img-preview">

                        <!-- Upload Button Overlay -->
                        <label for="image" class="btn btn-sm btn-primary position-absolute rounded-circle shadow"
                            style="bottom: 5px; right: -5px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff;">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewImage()" required>
                    </div>

                    <!-- ===== Formulir Tips ===== -->
                    <div class="card section-card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-edit me-2"></i>Konten Tips & Tricks</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Judul Tips -->
                                <div class="col-12">
                                    <label for="title" class="form-label">Judul Tips <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="<?= old('title') ?>"
                                            placeholder="Masukkan judul tips yang menarik" required>
                                    </div>
                                </div>

                                <!-- Target App -->
                                <div class="col-md-6">
                                    <label for="target_app" class="form-label">Ditujukan Untuk <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-bullseye"></i></span>
                                        <select class="form-select" id="target_app" name="target_app" required>
                                            <option value="Client" <?= old('target_app') == 'Client' ? 'selected' : '' ?>>Client (Pemilik Proyek)</option>
                                            <option value="Tukang" <?= old('target_app') == 'Tukang' ? 'selected' : '' ?>>Tukang (Penyedia Jasa)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Is Active -->
                                <div class="col-md-6">
                                    <label for="is_active" class="form-label">Status Awal</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                        <select class="form-select" id="is_active" name="is_active">
                                            <option value="1">Aktif (Langsung Tampil)</option>
                                            <option value="0">Draft (Sembunyikan)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Isi Konten -->
                                <div class="col-12">
                                    <label for="content" class="form-label">Isi Konten Tips <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="content" name="content" rows="6" placeholder="Tuliskan isi tips secara lengkap di sini..." required><?= old('content') ?></textarea>
                                </div>

                            </div>
                        </div>

                        <!-- Submit Area -->
                        <div class="row g-3 mb-4 px-4 mt-1">
                            <div class="col-6">
                                <?php if (can('tips_create')): ?>
                                <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                                    <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Tips</span>
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-secondary w-100 fw-bold" style="border-radius: 10px; height: 45px;" disabled>
                                    <i class="fas fa-lock me-2"></i>Akses Ditolak
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <a href="<?= base_url('admin/tips') ?>" class="btn btn-outline-secondary w-100 py-2 fw-bold" style="border-radius: 10px;">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    /* ===== Loading on Submit ===== */
    document.getElementById('create-tips-form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('.ladda-button');
        if (submitBtn) {
            const l = Ladda.create(submitBtn);
            l.start();
        }
    });

    /* ===== Image Preview ===== */
    function previewImage() {
        const input = document.querySelector('#image');
        const preview = document.querySelector('#img-preview');
        const placeholder = document.querySelector('#img-preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>
