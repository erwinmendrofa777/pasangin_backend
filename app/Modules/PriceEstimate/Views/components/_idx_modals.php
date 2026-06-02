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
