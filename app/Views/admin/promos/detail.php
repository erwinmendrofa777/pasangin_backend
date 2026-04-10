<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Promo
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Promo
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-7 col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Promo</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/promo') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <?php if (!empty($promo['photo'])): ?>

                        <?php if (strpos($promo['photo'], 'http') === 0): ?>
                            <img src="<?= $promo['photo'] ?>" id="img-preview" alt="logo" class="img-thumbnail img-fluid shadow-sm rounded mb-0" data-bs-toggle="modal" data-bs-target="#imageModal" style="width: 250px; height: auto; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px;" data-toggle="tooltip" title="<?= $promo['title'] ?>">
                        <?php else: ?>
                            <img src="<?= base_url('uploads/promos/' . $promo['photo']) ?>" id="img-preview" alt="logo" data-bs-toggle="modal" data-bs-target="#imageModal" class="img-thumbnail img-fluid shadow-sm rounded mb-0" style="width: 250px; height: auto; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px;">
                        <?php endif; ?>

                    <?php else: ?>
                        <p class="text-muted">Tidak ada gambar sebelumnya.</p>
                    <?php endif; ?>
                </div>
                <div class="text-center">
                    <small class="text-muted">Klik untuk pratinjau gambar</small>
                </div>

                <!-- Modal untuk pratinjau gambar -->
                <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered"> <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Pratinjau Gambar</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">

                                <?php if (strpos($promo['photo'], 'http') === 0): ?>
                                    <img src="<?= $promo['photo'] ?>" id="img-preview" alt="logo" class="img-thumbnail w-100 img-fluid shadow-sm rounded" data-bs-toggle="modal" data-bs-target="#imageModal" style="width: 250px; height: auto; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px;" data-toggle="tooltip" title="<?= $promo['title'] ?>">
                                <?php else: ?>
                                    <img src="<?= base_url('uploads/promos/' . $promo['photo']) ?>" id="img-preview" alt="logo" data-bs-toggle="modal" data-bs-target="#imageModal" class="img-thumbnail w-100 img-fluid shadow-sm rounded" style="width: 250px; height: auto; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px;">
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mx-auto" style="width: 500px;">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                                    
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Nama Product</div>
                                    <div class="col-7 "><?= $promo['title'] ?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Nama Supplier</div>
                                    <div class="col-7 "><?= $promo['supplier_name'] ?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Deskripsi</div>
                                    <div class="col-7 "><?= $promo['description'] ?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Diskon</div>
                                    <div class="col-7">
                                        <?php if($promo['discount_type'] == "fixed"): ?>
                                            Rp <?= number_format($promo['discount_value'], 0, ',', '.') ?>
                                        <?php else: ?>
                                            <?= number_format($promo['discount_value'], 0, ',', '.') ?>%
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">kode promo</div>
                                    <div class="col-7 "><?= $promo['promo_code'] ?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Masa Aktif</div>
                                    <div class="col-7 "><?= $promo['start_date']?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Masa Berakhir</div>
                                    <div class="col-7 "><?= $promo['end_date']?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Status</div>
                                    <div class="col-7 ">
                                        <?php if($promo['status'] == "active"): ?>
                                            <div class="badge badge-success">Aktif</div>
                                        <?php else: ?>
                                            <div class="badge badge-danger">Non-Aktif</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // preview image
    function previewImage() {
        const file = document.querySelector('#logo_url');
        const imgPreview = document.querySelector('#img-preview');

        // Membuat objek FileReader untuk membaca file
        const fileReader = new FileReader();
        
        // Ambil data file yang diupload
        fileReader.readAsDataURL(file.files[0]);

        // Saat file selesai dibaca, ganti src gambar preview
        fileReader.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }

    // Supaya nama file muncul saat dipilih di input file bootstrap
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<?= $this->endSection() ?>