<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Detail Syarat & Ketentuan <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Detail Syarat & Ketentuan <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-md-8 col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h4>Detail Syarat & Ketentuan</h4>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Judul</label>
                    <p><?= esc($data['title']) ?></p>
                </div>
                <div class="form-group mb-3">
                    <label class="font-weight-bold">Deskripsi</label>
                    <div class="p-3 border rounded bg-light">
                        <?= nl2br(esc($data['description'])) ?>
                    </div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $data['id']) ?>" class="btn btn-primary">Edit Data</a>
                    <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
