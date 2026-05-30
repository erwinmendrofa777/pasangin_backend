<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kirim Notifikasi Massal
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

    .banner-placeholder {
        width: 100%;
        height: 200px;
        border-radius: 14px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #8996fa, #6777ef);
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
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
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
        .edit-hero {
            padding: 20px 20px 60px;
        }

        .edit-body {
            padding: 0 16px 20px;
        }

        .avatar-wrapper {
            margin-top: -50px;
        }

        .banner-preview-img,
        .banner-placeholder {
            height: 160px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- BACK BUTTON -->
<div class="mb-3">
    <a href="<?= base_url('admin/notification') ?>" class="btn btn-light btn-sm px-3 shadow-sm border-0"
        style="border-radius: 8px; font-weight: 600;">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<form id="create-notification-form" method="POST" action="<?= base_url('admin/notification/send') ?>"
    enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card edit-card mb-5">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Buat Notifikasi Baru
                            </h5>
                            <p class="text-white-50 small mb-0">Kirim pemberitahuan dan info promo secara real-time</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2 d-none d-sm-inline-block"
                            style="border-radius:50px; font-size:0.75rem; font-weight:700;">
                            <i class="fas fa-paper-plane me-1 opacity-75"></i>KIRIM
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
                                    <div class="banner-placeholder" id="img-preview-placeholder">
                                        <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
                                        <span style="font-size: 0.75rem; font-weight: 700; opacity: 0.8;">GAMBAR BANNER
                                            (OPSIONAL)</span>
                                    </div>
                                    <img src="" alt="Preview" class="banner-preview-img d-none" id="img-preview">

                                    <!-- Upload Button Overlay -->
                                    <label for="image" class="btn btn-primary position-absolute rounded-circle shadow"
                                        style="bottom: 10px; right: 10px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid #fff;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="image" name="image" class="d-none" accept="image/*"
                                        onchange="previewImage()">
                                </div>
                                <div class="mt-3">
                                    <p class="small text-muted mb-0">Rekomendasi ukuran: 1280 x 720 px (Maks. 500KB)</p>
                                </div>
                            </div>

                            <!-- Form Information -->
                            <div class="card section-card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-bullseye me-2"></i>Target Pengiriman</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <label class="form-label">Tipe Pengiriman <span
                                                    class="text-danger">*</span></label>
                                            <div class="d-flex gap-3 mt-1">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="sendAll" name="send_type"
                                                        class="custom-control-input" value="all" checked>
                                                    <label class="custom-control-label" style="font-weight: 600;"
                                                        for="sendAll">Semua User</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="sendSpecific" name="send_type"
                                                        class="custom-control-input" value="specific">
                                                    <label class="custom-control-label" style="font-weight: 600;"
                                                        for="sendSpecific">Spesifik</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Role Target <span
                                                    class="text-danger">*</span></label>
                                            <select name="target" id="targetRole" class="form-control" required>
                                                <option value="client">Klien (User)</option>
                                                <option value="tukang">Tukang (Mitra)</option>
                                                <option value="supplier">Supplier (Toko)</option>
                                                <option value="admin">Admin (Internal)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-2" id="specificUserContainer" style="display: none;">
                                        <label class="form-label">Pilih User <span class="text-danger">*</span></label>
                                        <select name="target_id" id="targetId" class="form-control select2"
                                            style="width: 100%;">
                                            <option value="">Ketik nama / no HP...</option>
                                        </select>
                                        <small class="text-muted d-block mt-1">Ketik minimal 3 karakter untuk
                                            mencari</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card section-card mb-4">
                                <div class="card-header">
                                    <h6><i class="fas fa-edit me-2"></i>Konten Notifikasi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-4">
                                        <label class="form-label">Judul <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Contoh: Promo Diskon 50%!" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Isi Pesan <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control" rows="5" style="height: 120px;"
                                            placeholder="Tuliskan deskripsi lengkap pesan..." required></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row g-3 justify-content-center mt-2">
                                <div class="col-12 col-md-8">
                                    <?php if (can('notification_create')): ?>
                                        <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out">
                                            <span class="ladda-label"><i class="fas fa-paper-plane me-2"></i>Kirim
                                                Notifikasi Sekarang</span>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-secondary w-100 btn-save"
                                            style="background: #6c757d;" disabled>
                                            <i class="fas fa-lock me-2"></i>Akses Ditolak
                                        </button>
                                    <?php endif; ?>
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
    $(document).ready(function () {
        // Toggle Specific User Container
        $('input[name="send_type"]').change(function () {
            if ($(this).val() === 'specific') {
                $('#specificUserContainer').slideDown();
                $('#targetId').prop('required', true);
                initSelect2();
            } else {
                $('#specificUserContainer').slideUp();
                $('#targetId').prop('required', false);
            }
        });

        // Re-init Select2 if role changes
        $('#targetRole').change(function () {
            if ($('input[name="send_type"]:checked').val() === 'specific') {
                $('#targetId').val(null).trigger('change');
                initSelect2();
            }
        });

        function initSelect2() {
            var role = $('#targetRole').val();
            $('#targetId').select2({
                ajax: {
                    url: '<?= base_url('admin/notification/searchUsers') ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term, role: role };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                },
                placeholder: 'Ketik nama / no HP...',
                minimumInputLength: 3,
                allowClear: true,
                width: '100%'
            });
        }

        /* ===== Loading on Submit ===== */
        document.getElementById('create-notification-form').addEventListener('submit', function () {
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
        const placeholder = document.querySelector('#img-preview-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
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