<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Supplier
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Formulir Tambah Supplier
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Isi Data Supplier</h4>
            </div>
            <div class="card-body">
                <!-- Form ini akan mengirim data ke method 'save' di SupplierController -->
                <form action="<?= base_url('admin/suppliers/save'); ?>" method="post">
                    <?= csrf_field(); // Keamanan CodeIgniter ?>

                    <!-- ============================================== -->
                    <!-- FIELD NAMA SUPPLIER (DENGAN VALIDASI BARU) -->
                    <!-- ============================================== -->
                    <div class="form-group">
                        <label for="name">Nama Supplier</label>
                        <input type="text" 
                               class="form-control <?php if (session('errors.name')) : ?>is-invalid<?php endif ?>" 
                               id="name" 
                               name="name" 
                               value="<?= old('name'); ?>" 
                               placeholder="Contoh: TB Jaya Abadi" 
                               required>
                        <div class="invalid-feedback">
                            <?php if (session('errors.name')) : ?>
                                <?= session('errors.name') ?>
                            <?php endif ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact_person">Kontak Person</label>
                        <input type="text" 
                               class="form-control" 
                               id="contact_person" 
                               name="contact_person" 
                               value="<?= old('contact_person'); ?>" 
                               placeholder="Contoh: Bapak Suryo">
                    </div>

                    <div class="form-group">
                        <label for="phone">Telepon</label>
                        <input type="text" 
                               class="form-control" 
                               id="phone" 
                               name="phone" 
                               value="<?= old('phone'); ?>" 
                               placeholder="Contoh: 08123456789">
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea class="form-control" 
                                  rows="3" 
                                  id="address" 
                                  name="address" 
                                  placeholder="Alamat lengkap supplier"><?= old('address'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="is_active">
                            <option value="1" <?= (old('is_active') == '1') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="0" <?= (old('is_active') == '0') ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                        <a href="<?= base_url('admin/suppliers'); ?>" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
