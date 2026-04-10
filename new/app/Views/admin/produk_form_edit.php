<?= $this->extend('admin/supplier/layout/main') ?>

<?= $this->section('title') ?>Edit Produk<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Formulir Edit Produk<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Ubah Detail Produk</h4>
            </div>
            <div class="card-body">
                <form action="<?= site_url('supplier/produk/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label>Foto Saat Ini</label><br>
                        <img src="<?= base_url('uploads/products/' . esc($product['photo'])); ?>" alt="<?= esc($product['name']); ?>" width="150" class="img-thumbnail mb-2">
                    </div>

                    <div class="form-group">
                        <label for="photo">Ganti Foto (Opsional)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="photo" name="photo">
                            <label class="custom-file-label" for="photo">Pilih gambar baru...</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= esc($product['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" value="<?= esc($product['price']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="<?= esc($product['stock']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="aktif" <?= ($product['status'] == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="tidak aktif" <?= ($product['status'] == 'tidak aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="habis" <?= ($product['status'] == 'habis') ? 'selected' : '' ?>>Habis</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?= esc($product['description']) ?></textarea>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Update Produk</button>
                        <a href="<?= site_url('supplier/produk') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
