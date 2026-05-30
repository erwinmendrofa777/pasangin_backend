<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Akses Ditolak
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="section">
    <div class="section-body">
        <div class="card">
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <i class="fas fa-lock text-danger" style="font-size: 5rem;"></i>
                </div>
                <h2 class="text-dark">Akses Ditolak</h2>
                <p class="lead text-muted">
                    Maaf, Anda tidak memiliki izin untuk melihat halaman Dashboard ini.
                </p>
                <div class="mt-4">
                    <p>Silakan hubungi Super Admin untuk mendapatkan akses atau pilih menu lain di samping.</p>
                    <a href="<?= base_url('admin/logout') ?>" class="btn btn-danger px-4 shadow-sm">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
