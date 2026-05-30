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
                        <?php if (can('banner_create')): ?>
                        <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right ladda-button" data-style="zoom-in">
                            <span class="ladda-label"><i class="fas fa-save"></i> Simpan Banner</span>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-lg btn-icon icon-right" disabled>
                            <i class="fas fa-lock"></i> Akses Ditolak
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
    <?php if (session()->getFlashdata('success')) : ?>
        iziToast.success({
            timeout: 20000,
            title: 'Berhasil',
            message: '<?= session()->getFlashdata('success') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        iziToast.error({
            timeout: 20000,
            title: 'Gagal',
            message: '<?= session()->getFlashdata('error') ?>',
            position: 'topCenter'
        });
    <?php endif; ?>
    // end konfigurasi

    // Supaya nama file muncul saat dipilih di input file bootstrap
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

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
