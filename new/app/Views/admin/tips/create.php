<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Tambah Tips <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Buat Tips Baru <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h4>Form Input Artikel</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/tips') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                
                <?php if(session()->get('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                        <?php foreach(session()->get('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/tips/store') ?>" method="post" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label>Judul Tips</label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Cara Menghemat Cat Tembok">
                    </div>

                    <div class="form-group">
                        <label>Target Aplikasi</label>
                        <select name="target_app" class="form-control selectric">
                            <option value="client">Aplikasi Client (User)</option>
                            <option value="tukang">Aplikasi Tukang (Mitra)</option>
                        </select>
                    </div>

                    <!-- INPUT DESKRIPSI -->
                    <div class="form-group">
                        <label>Deskripsi Lengkap / Isi Artikel</label>
                        <textarea name="content" class="form-control" style="height: 150px" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Gambar Cover</label>
                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="customFile" required>
                            <label class="custom-file-label" for="customFile">Pilih File</label>
                        </div>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Simpan Tips</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
<?= $this->endSection() ?>
