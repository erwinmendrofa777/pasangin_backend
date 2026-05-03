<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Voucher Baru
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Voucher
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

    /* ===== VOUCHER PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -65px;
    }

    .voucher-preview-img {
        width: 200px;
        height: 120px;
        object-fit: cover;
        object-position: center;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    .voucher-placeholder {
        width: 200px;
        height: 120px;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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

    .section-card .card-body {
        padding: 20px;
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
        color: #212529;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
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
        color: #6c757d;
        font-size: 0.88rem;
    }

    .input-group .form-control {
        border-radius: 0 10px 10px 0;
    }

    /* ===== SUBMIT BUTTONS ===== */
    .btn-save {
        border-radius: 10px;
        padding: 10px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        border: none;
        color: #fff;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.35);
        color: #fff;
    }

    .field-hint {
        font-size: 0.74rem;
        color: #6c757d;
        margin-top: 4px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-secondary btn-sm px-3 shadow-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<form id="create-voucher-form" method="POST" action="<?= base_url('admin/vouchers/store') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-9 col-xl-8">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Tambah Voucher Baru
                            </h5>
                            <p class="text-white-50 small mb-0">Lengkapi formulir untuk membuat promo baru</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2" style="border-radius:50px; font-size:0.78rem; font-weight:700;">
                            <i class="fas fa-plus me-1 opacity-75"></i>VOUCHER BARU
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Image Preview -->
                    <div class="avatar-wrapper">
                        <div class="voucher-placeholder" id="img-preview-placeholder">
                            <i class="fas fa-ticket-alt fa-2x mb-1"></i>
                            <span style="font-size: 0.7rem; font-weight: 600; opacity: 0.8;">PRATINJAU GAMBAR</span>
                        </div>
                        <img src="" alt="Preview" class="voucher-preview-img d-none" id="img-preview">

                        <!-- Upload Button Overlay -->
                        <label for="image" class="btn btn-sm btn-primary position-absolute rounded-circle shadow"
                            style="bottom: 5px; right: -5px; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff;">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewImage()" required>
                    </div>

                    <!-- ===== Formulir Voucher ===== -->
                    <div class="card section-card mt-3">
                        <div class="card-header">
                            <h6><i class="fas fa-edit me-2"></i>Detail Informasi Voucher</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <!-- Kode Voucher -->
                                <div class="col-md-6">
                                    <label for="code" class="form-label">Kode Voucher <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="<?= old('code') ?>"
                                            placeholder="Misal: PROMO50" required style="text-transform: uppercase;">
                                    </div>
                                    <div class="field-hint">Gunakan huruf kapital dan tanpa spasi.</div>
                                </div>

                                <!-- Nama Voucher -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nama Promo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ticket-alt"></i></span>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?= old('name') ?>"
                                            placeholder="Misal: Diskon Gajian" required>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="col-12">
                                    <label for="description" class="form-label">Deskripsi Promo</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Jelaskan syarat dan ketentuan promo ini..."><?= old('description') ?></textarea>
                                </div>

                                <!-- Nominal Diskon -->
                                <div class="col-md-6">
                                    <label for="discount_nominal" class="form-label">Nominal Potongan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="discount_nominal" name="discount_nominal"
                                            value="<?= old('discount_nominal') ?>"
                                            placeholder="0" required>
                                    </div>
                                </div>

                                <!-- Berlaku Sampai -->
                                <div class="col-md-6">
                                    <label for="valid_until" class="form-label">Berlaku Hingga <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="valid_until" name="valid_until"
                                            value="<?= old('valid_until') ?>" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-3 mb-4 px-4 mt-1">
                            <div class="col-6">
                                <?php if (can('vouchers_create')): ?>
                                <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                                    <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan Voucher</span>
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-secondary w-100 fw-bold" style="border-radius: 10px; height: 45px;" disabled>
                                    <i class="fas fa-lock me-2"></i>Akses Ditolak
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="col-6">
                                <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-outline-secondary w-100 py-2 fw-bold" style="border-radius: 10px;">
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
    /* ===== Flash Messages ===== */
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({
            timeout: 5000,
            title: 'Berhasil!',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({
            timeout: 5000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    /* ===== Loading on Submit ===== */
    document.getElementById('create-voucher-form').addEventListener('submit', function() {
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
</script>
<?= $this->endSection() ?>