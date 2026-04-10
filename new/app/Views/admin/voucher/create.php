<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Voucher
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Buat Voucher Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Formulir Voucher</h4>
                <div class="card-header-action">
                    <a href="<?= base_url('admin/vouchers') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            
            <form action="<?= base_url('admin/vouchers/store') ?>" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    
                    <!-- Tampilkan Error Validasi jika ada -->
                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Kode Voucher (Unik)</label>
                        <input type="text" name="code" class="form-control" placeholder="Misal: PROMO50" required style="text-transform:uppercase">
                        <small class="form-text text-muted">Kode harus unik dan tanpa spasi.</small>
                    </div>

                    <div class="form-group">
                        <label>Nama Voucher</label>
                        <input type="text" name="name" class="form-control" placeholder="Misal: Diskon Kemerdekaan" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control" style="height: 100px;"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nominal Diskon (Rp)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Rp</div>
                                    </div>
                                    <input type="number" name="discount_nominal" class="form-control currency" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Berlaku Sampai</label>
                                <input type="date" name="valid_until" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gambar Voucher</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG/PNG. Maksimal 2MB.</small>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <button type="reset" class="btn btn-warning">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
