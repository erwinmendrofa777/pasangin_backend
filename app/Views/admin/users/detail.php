<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail User
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail User
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-7 col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail User</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <?php if (!empty($user['avatar'])): ?>

                        <?php if (strpos($user['avatar'], 'http') === 0): ?>
                            <img src="<?= $user['avatar'] ?>" id="img-preview" alt="avatar" class="img-thumbnail shadow-sm rounded" style="width: 250px; height: auto; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px;" data-toggle="tooltip" title="<?= $user['full_name'] ?>">
                        <?php else: ?>
                            <img src="<?= base_url('uploads/users/' . $user['avatar']) ?>" id="img-preview" alt="avatar" class="img-thumbnail shadow-sm rounded" style="width: 250px; height: auto; margin-bottom: 10px; border: 1px solid #ddd; padding: 5px;">
                        <?php endif; ?>

                    <?php else: ?>
                        <p class="text-muted">Tidak ada gambar sebelumnya.</p>
                    <?php endif; ?>
                </div>
                <div class="card mx-auto" style="width: 500px;">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Nama Lengkap</div>
                                    <div class="col-7 "><?= $user['full_name'] ?></div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Email</div>
                                    <div class="col-7 text-break"><?= $user['email'] ?></div> </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Whatsapp</div>
                                    <div class="col-7 text-break"><?= $user['phone_number'] ?></div> </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Jenis Kelamin</div>
                                    <div class="col-7 text-break"><?= $user['gender'] ?></div> </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Tanggal Lahir</div>
                                    <div class="col-7 text-break"><?= $user['birth_date'] ?></div> </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Alamat</div>
                                    <div class="col-7 text-wrap text-break">
                                        <?= $user['address'] ?>
                                    </div>
                                </div>
                            </li>

                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-5 fw-bold">Hak Akses</div>
                                    <div class="col-7"><?= $user['role'] ?></div>
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







                                        