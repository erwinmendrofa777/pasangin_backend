<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Tambah Syarat & Ketentuan <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Tambah Syarat & Ketentuan <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-md-8 col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h4>Form Tambah Syarat & Ketentuan</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/syarat_ketentuan/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" value="<?= old('title') ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.title') ?>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" style="height: 150px" required><?= old('description') ?></textarea>
                        <div class="invalid-feedback">
                            <?= session('errors.description') ?>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary ladda-button" data-style="zoom-in">
                            <span class="ladda-label">Simpan</span>
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
    // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
    $(document).on('submit', 'form', function() {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });
</script>
<?= $this->endSection() ?>
