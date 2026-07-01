<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Produk Baru
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .edit-hero {
        background: var(--palette-primary);
        border-radius: 16px 16px 0 0;
        padding: 28px 28px 72px;
        position: relative;
        overflow: hidden;
    }

    .edit-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .edit-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== PRODUCT PHOTO PREVIEW ===== */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -65px;
        width: 150px;
        height: 150px;
    }

    .banner-preview-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        object-position: center;
        border-radius: 20px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    .banner-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 20px;
        border: 4px solid #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #FFA3A3, var(--palette-primary));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    /* ===== CARDS ===== */
    .edit-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(255, 92, 92, 0.10), 0 2px 8px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        background: #fff;
    }

    .edit-body {
        padding: 0 28px 28px;
    }

    .section-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(255, 92, 92, 0.08), 0 1px 6px rgba(0, 0, 0, 0.05);
    }

    .section-card .card-header {
        background: #fffafa;
        border-bottom: 1px solid #eef2ff;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }

    .section-card .card-header h6 {
        color: var(--palette-primary);
        font-weight: 700;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin: 0;
    }

    /* ===== FORM INPUTS ===== */
    .form-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #495057;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1.5px solid #eef2ff;
        padding: 12px 16px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1);
    }

    /* ===== SUBMIT BUTTONS ===== */
    .btn-save {
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        border: none;
        color: #fff;
        cursor: pointer;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 92, 92, 0.35);
        color: #fff;
        text-decoration: none;
    }

    .btn-cancel {
        border-radius: 12px;
        padding: 12px 28px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        background: #f1f5f9;
        border: 1.5px solid #cbd5e1;
        color: #475569;
        display: inline-block;
        text-align: center;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
        color: #1e293b;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .edit-hero { padding: 20px 20px 60px; }
        .edit-body { padding: 0 16px 20px; }
        .avatar-wrapper { margin-top: -50px; }
        .banner-preview-img, .banner-placeholder { height: 160px; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade mb-4" style="border-radius: 12px;">
                    <div class="alert-body">
                        <button class="close" data-bs-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card edit-card">
                
                <!-- Hero Banner Header -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size: 1.15rem;">
                                Buat Produk Supplier
                            </h5>
                            <p class="text-white-50 small mb-0">Lengkapi formulir untuk mempublikasikan produk baru</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2 d-none d-sm-inline-block" style="border-radius: 50px; font-size: 0.75rem; font-weight: 700;">
                            <i class="fas fa-plus-circle me-1 opacity-75"></i>BARU
                        </span>
                    </div>
                </div>

                <!-- Form Card Body -->
                <div class="edit-body">
                    <form action="<?= site_url('admin/sales/suppliers/' . $supplier['id'] . '/products/store') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Product Photo Preview Area -->
                        <div class="text-center mb-4">
                            <div class="avatar-wrapper mx-auto">
                                <div class="banner-placeholder" id="img-preview-placeholder">
                                    <i class="fas fa-image fa-2x mb-1 opacity-50"></i>
                                    <span style="font-size: 0.68rem; font-weight: 700; opacity: 0.8; letter-spacing: 0.5px;">PRATINJAU FOTO</span>
                                </div>
                                <img src="" alt="Preview" class="banner-preview-img d-none" id="img-preview">

                                <!-- Upload Button Overlay -->
                                <label for="photo" class="btn btn-primary position-absolute rounded-circle shadow"
                                    style="bottom: 10px; right: 10px; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid #fff; background: var(--palette-primary); border-color: #fff;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="photo" name="photo" class="d-none" accept="image/*" onchange="previewImage()" required>
                            </div>
                            <div class="mt-3">
                                <p class="small text-muted">Rekomendasi ukuran: 800 x 800 px (Maks. 2MB)</p>
                            </div>
                        </div>

                        <!-- Product Information Section Title -->
                        <div class="mb-4" style="border-bottom: 2px solid #eef2ff; padding-bottom: 10px;">
                            <h6 class="mb-0 fw-bold text-uppercase" style="color: var(--palette-primary); font-size: 0.82rem; letter-spacing: 0.5px;">
                                <i class="fas fa-edit me-2"></i>Informasi Produk
                            </h6>
                        </div>
                        
                        <!-- Nama Produk -->
                        <div class="mb-4">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Semen Gresik 50kg" value="<?= old('name') ?>" required autocomplete="off">
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label class="form-label">Kategori Toko Supplier</label>
                            <select name="supplier_category_id" class="form-select w-100">
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= old('supplier_category_id') == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Harga & Satuan -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label">Harga Produk (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-muted" style="border: 1.5px solid #eef2ff; border-right: none;">Rp</span>
                                    <input type="number" name="price" class="form-control" placeholder="0" value="<?= old('price') ?>" required min="0" style="border-left: none;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Satuan Jual</label>
                                <input type="text" name="unit" class="form-control" placeholder="Contoh: sak, pcs, kg" value="<?= old('unit', 'pcs') ?>" autocomplete="off">
                            </div>
                        </div>

                        <!-- Stok, Min Order, Volume -->
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" placeholder="0" value="<?= old('stock', 0) ?>" required min="0">
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label">Min. Order</label>
                                <input type="number" name="min_order" class="form-control" value="<?= old('min_order', 1) ?>" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Volume (m³)</label>
                                <input type="number" step="any" name="quantity" class="form-control" value="<?= old('quantity', 0) ?>" min="0">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label">Deskripsi Produk</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Tuliskan spesifikasi detail, keunggulan, atau catatan produk lainnya..." style="resize: none;"><?= old('description') ?></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row g-3 justify-content-center mb-4">
                            <div class="col-6">
                                <a href="<?= site_url('admin/sales/suppliers?supplier_id=' . $supplier['id']) ?>" class="btn btn-cancel w-100">Batal</a>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-save w-100">
                                    <i class="fas fa-save me-2"></i>Simpan Produk
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    function previewImage() {
        const fileInput = document.getElementById('photo');
        const previewPlaceholder = document.getElementById('img-preview-placeholder');
        const previewImg = document.getElementById('img-preview');
        
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.classList.remove('d-none');
                previewPlaceholder.classList.add('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            previewImg.classList.add('d-none');
            previewPlaceholder.classList.remove('d-none');
        }
    }
</script>
<?= $this->endSection() ?>
