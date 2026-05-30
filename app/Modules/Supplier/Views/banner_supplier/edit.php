<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Edit Banner Supplier
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .edit-hero {
        background: #6777ef;
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

    /* ===== BANNER PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -65px;
        width: 100%;
        max-width: 400px;
    }

    .banner-preview-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        object-position: center;
        border-radius: 14px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    /* ===== CARDS ===== */
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(103, 119, 239, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #fff;
    }

    .edit-body {
        padding: 0 28px 28px;
    }

    .section-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(103, 119, 239, 0.08), 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .section-card .card-header {
        background: #f8fbff;
        border-bottom: 1px solid #eef2ff;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }

    .section-card .card-header h6 {
        color: #6777ef;
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
    }

    /* ===== FORM INPUTS ===== */
    .form-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #495057;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #eef2ff;
        padding: 12px 16px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 4px rgba(103, 119, 239, 0.1);
    }

    .select2-container--default .select2-selection--single {
        border-radius: 10px !important;
        border: 1.5px solid #eef2ff !important;
        height: 48px !important;
    }

    /* ===== SUBMIT BUTTONS ===== */
    .btn-save {
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #6777ef, #4c5fd7);
        border: none;
        color: #fff;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(103, 119, 239, 0.35);
        color: #fff;
    }

    @media (max-width: 768px) {
        .edit-hero { padding: 20px 20px 60px; }
        .edit-body { padding: 0 16px 20px; }
        .avatar-wrapper { margin-top: -50px; }
        .banner-preview-img { height: 160px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/banner-supplier') ?>" class="btn btn-light btn-sm px-3 shadow-sm border-0" style="border-radius: 8px; font-weight: 600;">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<form id="edit-banner-form" method="POST" action="<?= base_url('admin/banner-supplier/update/' . $banner['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Edit Banner Supplier
                            </h5>
                            <p class="text-white-50 small mb-0">Perbarui informasi banner promosi mitra supplier</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2 d-none d-sm-inline-block" style="border-radius:50px; font-size:0.75rem; font-weight:700;">
                            <i class="fas fa-pencil-alt me-1 opacity-75"></i>EDIT DATA
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pe-0 ps-0">

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-9">
                            
                            <!-- Banner Preview Area -->
                            <div class="text-center mb-4">
                                <div class="avatar-wrapper mx-auto">
                                    <img src="<?= base_url('uploads/supplier/banner/' . $banner['image']) ?>" alt="Preview" class="banner-preview-img" id="img-preview">

                                    <!-- Upload Button Overlay -->
                                    <label for="image" class="btn btn-primary position-absolute rounded-circle shadow"
                                        style="bottom: 10px; right: 10px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid #fff;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="image" name="image" class="d-none" accept="image/*" onchange="previewImage()">
                                </div>
                                <div class="mt-3">
                                    <p class="small text-muted">Biarkan kosong jika tidak ingin mengubah gambar.<br>Rekomendasi: 1200 x 600 px (Maks. 3MB)</p>
                                    <?php if (isset(session('errors')['image'])): ?>
                                        <div class="text-danger small fw-bold mt-1"><?= session('errors')['image'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Banner Information Form -->
                            <div class="card section-card mb-2">
                                <div class="card-header">
                                    <h6><i class="fas fa-edit me-2"></i>Informasi Banner</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Pilih Supplier <span class="text-danger">*</span></label>
                                        <select name="id_supplier" class="form-control select2" required>
                                            <option value="">-- Cari Supplier --</option>
                                            <?php foreach ($suppliers as $s): ?>
                                                <option value="<?= $s['id'] ?>" <?= (old('id_supplier') ?? $banner['id_supplier']) == $s['id'] ? 'selected' : '' ?>>
                                                    <?= esc($s['name']) ?> (ID: #<?= $s['id'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset(session('errors')['id_supplier'])): ?>
                                            <small class="text-danger fw-bold"><?= session('errors')['id_supplier'] ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Judul Promo / Banner <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" 
                                               placeholder="Misal: Diskon Keramik Akhir Tahun" 
                                               value="<?= old('title') ?? $banner['title'] ?>" required>
                                        <?php if (isset(session('errors')['title'])): ?>
                                            <small class="text-danger fw-bold"><?= session('errors')['title'] ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" class="form-control" value="<?= old('start_date') ?? $banner['start_date'] ?>" required>
                                            <?php if (isset(session('errors')['start_date'])): ?>
                                                <small class="text-danger fw-bold"><?= session('errors')['start_date'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                                            <input type="date" name="end_date" class="form-control" value="<?= old('end_date') ?? $banner['end_date'] ?>" required>
                                            <?php if (isset(session('errors')['end_date'])): ?>
                                                <small class="text-danger fw-bold"><?= session('errors')['end_date'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label">Catatan (Internal)</label>
                                        <textarea name="note" class="form-control" rows="3" placeholder="Tambahkan catatan khusus jika ada..."><?= old('note') ?? $banner['note'] ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row g-3 justify-content-center">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                                        <span class="ladda-label"><i class="fas fa-save me-2"></i>Perbarui Banner</span>
                                    </button>
                                </div>
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
    $(document).ready(function() {
        // Initialize Select2
        if ($('.select2').length > 0) {
            $('.select2').select2({
                placeholder: "-- Cari Supplier --",
                allowClear: true,
                width: '100%'
            });
        }

        /* ===== Loading on Submit ===== */
        document.getElementById('edit-banner-form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('.ladda-button');
            if (submitBtn) {
                const l = Ladda.create(submitBtn);
                l.start();
            }
        });
    });

    /* ===== Image Preview ===== */
    function previewImage() {
        const input = document.querySelector('#image');
        const preview = document.querySelector('#img-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Flash Messages
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>
