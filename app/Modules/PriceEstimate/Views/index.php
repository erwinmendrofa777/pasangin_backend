<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HEADER CARD ===== */
    .page-header-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .page-header-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: rgba(103, 119, 239, 0.05);
        border-radius: 50%;
    }

    .page-header-card::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -30px;
        width: 260px;
        height: 260px;
        background: rgba(103, 119, 239, 0.03);
        border-radius: 50%;
    }

    /* ===== STAT PILLS ===== */
    .stat-pill {
        background: #f0f4ff;
        border-radius: 50px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #4b49ac;
        font-weight: 700;
        border: 1px solid #e0e6ff;
    }

    .stat-pill .stat-num {
        background: #6777ef;
        color: #fff;
        border-radius: 50px;
        padding: 1px 10px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* ===== CONCEPT CARDS ===== */
    .concept-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.05);
        background: #fff;
        margin-bottom: 30px;
        overflow: hidden;
    }

    .concept-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f8f9fa;
        padding: 20px 25px;
    }

    /* ===== TABLE STYLING ===== */
    .quality-table {
        margin-bottom: 0;
    }

    .quality-table thead th {
        background: #fcfcff;
        color: #8e94a9;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        padding: 15px 20px;
    }

    .quality-table tbody td {
        border-bottom: 1px solid #f8f9fa;
        padding: 18px 20px;
        font-size: 0.9rem;
        vertical-align: middle;
        color: #495057;
    }

    .quality-table tbody tr:last-child td {
        border: none;
    }

    /* ===== PRICE TAGS ===== */
    .price-pill {
        background: #f3f6ff;
        color: #6777ef;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.82rem;
        display: inline-block;
    }

    /* ===== ACTION BUTTONS ===== */
    .btn-circle-action {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid #eee;
        background: #fff;
        color: #6777ef;
    }

    .btn-circle-action:hover {
        background: #6777ef;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-circle-delete:hover {
        background: #fc544b;
        border-color: #fc544b;
    }

    /* ===== MODAL CUSTOM ===== */
    .modal-content-custom {
        border: none;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .modal-header-custom {
        padding: 30px 30px 10px;
        border: none;
    }

    .modal-body-custom {
        padding: 10px 30px 30px;
    }

    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 800;
        color: #8e94a9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-control-custom {
        border-radius: 12px;
        border: 2px solid #f1f3f9;
        padding: 12px 16px;
        font-weight: 600;
        color: #495057;
        transition: all 0.2s;
    }

    .form-control-custom:focus {
        border-color: #6777ef;
        background: #fff;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- HEADER BANNER -->
<div class="card page-header-card mb-4 shadow-sm">
    <div class="card-body p-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h4 class="text-primary mb-2 fw-bold">Estimasi Harga Per m²</h4>
                <p class="text-muted mb-0 small">Konfigurasi standar biaya konstruksi berdasarkan konsep arsitektur dan tier kualitas material.</p>
                <div class="mt-3 d-flex gap-3">
                    <div class="stat-pill shadow-sm">
                        <span>Konsep</span>
                        <span class="stat-num"><?= number_format($stats['total_concepts']) ?></span>
                    </div>
                    <div class="stat-pill shadow-sm">
                        <span>Tier Kualitas</span>
                        <span class="stat-num"><?= number_format($stats['total_qualities']) ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-md-end mt-4 mt-md-0">
                <?php if (can('price-estimate_create')): ?>
                <button type="button" class="btn btn-primary px-4 py-2 fw-bold shadow-primary" data-bs-toggle="modal" data-bs-target="#addConceptModal" style="border-radius: 12px; height: 46px;">
                    <i class="fas fa-plus me-2"></i>Tambah Konsep Baru
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- LIST OF CONCEPTS -->
<div class="row">
    <?php foreach ($concepts as $concept) : ?>
        <div class="col-12">
            <div class="card concept-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: 800;">
                            <?= strtoupper(substr($concept['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h5 class="m-0 fw-800 text-dark"><?= esc($concept['name']) ?></h5>
                            <small class="text-muted"><?= count($concept['qualities']) ?> Tingkat Kualitas</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if (can('price-estimate_update')): ?>
                        <button class="btn btn-light btn-sm fw-bold px-3 btn-edit-concept" data-id="<?= $concept['id'] ?>" data-name="<?= $concept['name'] ?>" style="border-radius: 8px; color: #6777ef;">
                            <i class="fas fa-edit me-1"></i> Rename
                        </button>
                        <?php endif; ?>

                        <?php if (can('price-estimate_create')): ?>
                        <button class="btn btn-primary btn-sm fw-bold px-3 btn-add-quality" data-concept-id="<?= $concept['id'] ?>" data-concept-name="<?= $concept['name'] ?>" style="border-radius: 8px;">
                            <i class="fas fa-plus me-1"></i> Kualitas
                        </button>
                        <?php endif; ?>

                        <?php if (can('price-estimate_delete')): ?>
                        <a href="<?= site_url('admin/price-estimate/concept/delete/' . $concept['id']) ?>" class="btn btn-outline-danger btn-sm fw-bold px-3 ladda-button" data-style="zoom-in" onclick="return confirm('Hapus konsep ini beserta semua datanya?')">
                            <span class="ladda-label"><i class="fas fa-trash"></i></span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table quality-table">
                            <thead>
                                <tr>
                                    <th style="width: 200px;">Tier Kualitas</th>
                                    <th style="width: 300px;">Estimasi Harga (m²)</th>
                                    <th>Deskripsi / Spesifikasi</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($concept['qualities'])) : ?>
                                    <?php foreach ($concept['qualities'] as $quality) : ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark"><?= esc($quality['label']) ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="price-pill">Rp <?= number_format($quality['min_price'], 0, ',', '.') ?></span>
                                                    <span class="text-muted">~</span>
                                                    <span class="price-pill">Rp <?= number_format($quality['max_price'], 0, ',', '.') ?></span>
                                                </div>
                                            </td>
                                            <td class="text-muted small"><?= esc($quality['description'] ?? '-') ?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <?php if (can('price-estimate_update')): ?>
                                                    <button class="btn-circle-action btn-edit-quality"
                                                        data-id="<?= $quality['id'] ?>"
                                                        data-label="<?= $quality['label'] ?>"
                                                        data-min-price="<?= $quality['min_price'] ?>"
                                                        data-max-price="<?= $quality['max_price'] ?>"
                                                        data-desc="<?= esc($quality['description'] ?? '') ?>">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <?php endif; ?>

                                                    <?php if (can('price-estimate_delete')): ?>
                                                    <a href="<?= site_url('admin/price-estimate/quality/delete/' . $quality['id']) ?>"
                                                        class="btn-circle-action btn-circle-delete text-danger ladda-button"
                                                        data-style="zoom-in"
                                                        onclick="return confirm('Hapus kualitas ini?')">
                                                        <span class="ladda-label"><i class="fas fa-trash-alt"></i></span>
                                                    </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!can('price-estimate_update') && !can('price-estimate_delete')): ?>
                                                    <span class="badge badge-light"><i class="fas fa-lock"></i></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="mb-1 text-muted" style="opacity: 0.2;">
                                                <i class="fas fa-layer-group fa-4x"></i>
                                            </div>
                                            <p class="text-muted mt-1 mb-0 small">Belum ada tingkat kualitas untuk konsep ini.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ==================================================================== -->
<!-- MODALS SECTION -->
<!-- ==================================================================== -->

<!-- Modal: Tambah Konsep -->
<div class="modal fade" id="addConceptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="fw-800 text-dark mb-0">Buat Konsep Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/price-estimate/concept/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body modal-body-custom">
                    <div class="form-group mb-4">
                        <label class="form-label-custom">Nama Konsep Desain</label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="Misal: Minimalis Modern, Industrial..." required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-3 fw-bold ladda-button" data-style="zoom-in" style="border-radius: 15px;">
                            <span class="ladda-label">Simpan Konsep</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Konsep -->
<div class="modal fade" id="editConceptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="fw-800 text-dark mb-0">Rename Konsep</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editConceptForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body modal-body-custom">
                    <div class="form-group mb-4">
                        <label class="form-label-custom">Nama Konsep Baru</label>
                        <input type="text" id="edit_concept_name" name="name" class="form-control form-control-custom" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary py-3 fw-bold ladda-button" data-style="zoom-in" style="border-radius: 15px;">
                            <span class="ladda-label">Update Konsep</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Tambah Kualitas -->
<div class="modal fade" id="addQualityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <div>
                    <h5 class="fw-800 text-dark mb-0">Tambah Tingkat Kualitas</h5>
                    <small class="text-muted" id="add_quality_concept_name"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('admin/price-estimate/quality/store') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="concept_id" id="add_quality_concept_id">
                <div class="modal-body modal-body-custom">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label-custom">Label Kualitas</label>
                            <input type="text" name="label" class="form-control form-control-custom" placeholder="E.g. Premium, Standar, Hemat..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Minimum (Rp)</label>
                            <input type="number" name="min_price" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Maksimum (Rp)</label>
                            <input type="number" name="max_price" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi / Spesifikasi Ringkas</label>
                            <textarea name="description" class="form-control form-control-custom" rows="3" placeholder="Jelaskan material yang digunakan..." required></textarea>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary py-3 fw-bold ladda-button" data-style="zoom-in" style="border-radius: 15px;">
                            <span class="ladda-label">Tambahkan Kualitas</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Kualitas -->
<div class="modal fade" id="editQualityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="fw-800 text-dark mb-0">Edit Parameter Kualitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editQualityForm" action="" method="post">
                <?= csrf_field() ?>
                <div class="modal-body modal-body-custom">
                    <div class="row g-4">
                        <div class="col-12">
                            <label class="form-label-custom">Label Kualitas</label>
                            <input type="text" id="edit_quality_label" name="label" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Minimum</label>
                            <input type="number" id="edit_quality_min_price" name="min_price" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Maksimum</label>
                            <input type="number" id="edit_quality_max_price" name="max_price" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Deskripsi / Spesifikasi</label>
                            <textarea id="edit_quality_desc" name="description" class="form-control form-control-custom" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary py-3 fw-bold ladda-button" data-style="zoom-in" style="border-radius: 15px;">
                            <span class="ladda-label">Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Modal Edit Konsep
        $('.btn-edit-concept').on('click', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#edit_concept_name').val(name);
            $('#editConceptForm').attr('action', "<?= site_url('admin/price-estimate/concept/update/') ?>" + id);
            $('#editConceptModal').modal('show');
        });

        // Modal Tambah Kualitas (Set Concept ID)
        $('.btn-add-quality').on('click', function() {
            const conceptId = $(this).data('concept-id');
            const conceptName = $(this).data('concept-name');
            $('#add_quality_concept_id').val(conceptId);
            $('#add_quality_concept_name').text('Konsep: ' + conceptName);
            $('#addQualityModal').modal('show');
        });

        // Modal Edit Kualitas
        $('.btn-edit-quality').on('click', function() {
            const id = $(this).data('id');
            const label = $(this).data('label');
            const minPrice = $(this).data('min-price');
            const maxPrice = $(this).data('max-price');
            const desc = $(this).data('desc');

            $('#edit_quality_label').val(label);
            $('#edit_quality_min_price').val(minPrice);
            $('#edit_quality_max_price').val(maxPrice);
            $('#edit_quality_desc').val(desc);

            $('#editQualityForm').attr('action', "<?= site_url('admin/price-estimate/quality/update/') ?>" + id);
            $('#editQualityModal').modal('show');
        });

        // Ladda Spinner on Submit
        $(document).on('submit', 'form', function() {
            var btn = $(this).find('.ladda-button');
            if (btn.length > 0) {
                var l = Ladda.create(btn[0]);
                l.start();
            }
        });

        // Toast Messages
        <?php if (session()->getFlashdata('message')): ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil!',
                message: '<?= session()->getFlashdata('message') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            iziToast.error({
                timeout: 6000,
                title: 'Gagal',
                message: '<?= session()->getFlashdata('error') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>