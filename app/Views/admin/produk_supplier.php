<?= $this->extend('admin/supplier/layout/main') ?>

<!-- Bagian judul tab browser -->
<?= $this->section('title') ?>
Produk Saya
<?= $this->endSection() ?>

<!-- Bagian judul di header konten -->
<?= $this->section('page_title') ?>
Manajemen Produk
<?= $this->endSection() ?>

<!-- Bagian isi konten -->
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">

        <?php
        // Menampilkan notifikasi pesan sukses atau error setelah redirect
        if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <b>Sukses!</b> <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php elseif(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <b>Error!</b> <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        <?php endif; ?>


        <div class="card">
            <div class="card-header">
                <h4>Daftar Produk Saya</h4>
                <div class="card-header-action">
                    <a href="<?= site_url('supplier/produk/new') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Produk Baru</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Foto</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if (!empty($products) && is_array($products)) : ?>
        <?php $i = 1; ?>
        <?php foreach ($products as $product) : ?>
            <tr>
                <!-- BENAR: Tidak ada titik koma -->
                <td><?= $i++ ?></td>
                
                <td>
                    <!-- BENAR: Tidak ada titik koma -->
                    <img src="<?= base_url('uploads/products/' . esc($product['photo'])) ?>" alt="<?= esc($product['name']) ?>" width="100" class="img-thumbnail">
                </td>

                <!-- BENAR: Tidak ada titik koma -->
                <td><?= esc($product['name']) ?></td>
                
                <!-- BENAR: Tidak ada titik koma -->
                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                
                <!-- BENAR: Tidak ada titik koma -->
                <td><?= esc($product['stock']) ?></td>

                <td>
                    <?php if ($product['status'] == 'aktif') : ?>
                        <div class="badge badge-success">Aktif</div>
                    <?php elseif ($product['status'] == 'habis') : ?>
                        <div class="badge badge-danger">Habis</div>
                    <?php else : ?>
                        <div class="badge badge-warning">Tidak Aktif</div>
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Tombol Edit -->
                    <a href="<?= site_url('supplier/produk/edit/' . $product['id']) ?>" class="btn btn-secondary btn-sm">Edit</a>

                    <!-- Tombol Hapus -->
                    <form action="<?= site_url('supplier/produk/delete/' . $product['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="7" class="text-center">Anda belum memiliki produk. Silakan tambahkan produk baru.</td>
        </tr>
    <?php endif; ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

