<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Klaim Toko Supplier
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .claim-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(229, 57, 53, 0.08), 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .claim-hero {
        background: linear-gradient(135deg, var(--palette-primary, #e53935), #ff7070);
        padding: 40px;
        color: white;
        position: relative;
    }
    .claim-hero h3 {
        font-weight: 800;
        margin-bottom: 8px;
    }
    .claim-body {
        padding: 30px;
    }
    .form-control-lg {
        border-radius: 10px;
        border: 2px solid #dee2e6;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-align: center;
    }
    .form-control-lg:focus {
        border-color: var(--palette-primary, #e53935);
        box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15);
    }
    .btn-claim {
        background: linear-gradient(135deg, var(--palette-primary, #e53935), #c62828);
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 700;
        color: white;
        transition: all 0.3s;
    }
    .btn-claim:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(229, 57, 53, 0.3);
        color: white;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-bs-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card claim-card">
                <div class="claim-hero text-center">
                    <i class="fas fa-qrcode fa-3x mb-3"></i>
                    <h3>Klaim Toko Supplier</h3>
                    <p class="mb-0">Hubungkan akun supplier yang Anda bantu input produknya.</p>
                </div>
                <div class="claim-body">
                    <form action="<?= site_url('admin/sales/claim') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="form-group mb-4">
                            <label class="form-label text-center d-block fw-bold mb-3 text-muted">MASUKKAN KODE REFERAL DARI HP SUPPLIER</label>
                            <input type="text" name="code" class="form-control form-control-lg" placeholder="SUP-XXXXXX" value="<?= old('code') ?>" required autocomplete="off">
                            <small class="text-muted d-block text-center mt-2">Kode referal dibuat oleh supplier melalui aplikasi mobile dan berlaku selama 10 menit.</small>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-claim btn-lg">
                                <i class="fas fa-link me-2"></i> Hubungkan Toko
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
