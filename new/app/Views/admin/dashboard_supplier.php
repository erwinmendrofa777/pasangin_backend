<?= $this->extend('admin/supplier/layout/main') ?>

<!-- Bagian judul tab browser -->
<?= $this->section('title') ?>
Dasbor
<?= $this->endSection() ?>

<!-- Bagian judul di header konten -->
<?= $this->section('page_title') ?>
Dasbor Supplier
<?= $this->endSection() ?>

<!-- Bagian isi konten -->
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Pesanan Baru</h4>
                </div>
                <div class="card-body">
                    12
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Pendapatan (Bulan Ini)</h4>
                </div>
                <div class="card-body">
                    Rp 4.500.000
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-info">
                <i class="fas fa-box"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Produk Aktif</h4>
                </div>
                <div class="card-body">
                    35
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Aktivitas Terkini</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <b>Selamat Datang, <?= esc(session()->get('supplier_name')) ?>!</b> Ini adalah pusat kendali untuk bisnis Anda di Pasangin.co.id.
                </div>
                <p>Di sini Anda bisa menampilkan tabel pesanan terakhir, produk yang baru ditambahkan, atau notifikasi penting lainnya.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
