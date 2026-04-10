<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Banner
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tambah Banner Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>Form Input Banner</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/banner') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                
                <!-- Tampilkan Error Validasi -->
                <?php if(session()->get('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                        <?php foreach(session()->get('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/banner/store') ?>" method="post" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label>Judul Banner (Opsional)</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Promo Diskon 50%">
                    </div>

                    <div class="form-group">
                        <label>Target Aplikasi</label>
                        <select name="target_app" class="form-control selectric">
                            <option value="client">Aplikasi Client (User)</option>
                            <option value="tukang">Aplikasi Tukang (Mitra)</option>
                        </select>
                        <small class="form-text text-muted">Banner akan muncul sesuai aplikasi yang dipilih.</small>
                    </div>

                    <div class="form-group">
                        <label>Upload Gambar</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Pilih File</label>
                        </div>
                        <small class="text-muted">Format: JPG/PNG, Maks 2MB. Ukuran ideal: 800x400 px.</small>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right">
                            <i class="fas fa-save"></i> Simpan Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Supaya nama file muncul saat dipilih di input file bootstrap
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<?= $this->endSection() ?>
