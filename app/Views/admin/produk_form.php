<?= $this->extend('admin/supplier/layout/main') ?>

<!-- Bagian judul tab browser -->
<?= $this->section('title') ?>
Tambah Produk Baru
<?= $this->endSection() ?>

<!-- Bagian judul di header konten -->
<?= $this->section('page_title') ?>
Formulir Tambah Produk
<?= $this->endSection() ?>

<!-- Bagian isi konten -->
<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Masukkan Detail Produk</h4>
            </div>
            <div class="card-body">
                <!-- PENTING: tambahkan enctype="multipart/form-data" untuk upload file -->
                <form action="<?= site_url('supplier/produk/create') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?> <!-- Keamanan CodeIgniter -->

                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="photo">Foto Produk</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" name="photo">
                            <label class="custom-file-label" for="photo">Pilih gambar...</label>
                        </div>
                        <small class="form-text text-muted">Ukuran maksimal 2MB. Format: JPG, PNG.</small>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Rp</div>
                            </div>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Produk (Opsional)</label>
                        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        <a href="<?= site_url('supplier/produk') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
