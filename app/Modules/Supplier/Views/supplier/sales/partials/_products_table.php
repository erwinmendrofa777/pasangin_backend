<div class="card border mt-4 shadow-sm" style="border-radius: 12px; overflow: hidden; border-color: #dee2e6 !important;">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 text-dark fw-bold"><i class="fas fa-boxes me-2 text-primary"></i> Daftar Produk - <?= esc($supplier['name']) ?></h6>
        <a href="<?= site_url('admin/sales/suppliers/' . $supplier['id'] . '/products/create') ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah Produk Baru
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-md align-middle mb-0">
                <thead>
                    <tr class="table-light">
                        <th class="text-center" style="width: 80px;">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Verifikasi Admin</th>
                        <th class="text-center" style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                Belum ada produk terdaftar untuk supplier ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td class="text-center">
                                    <img src="<?= base_url('uploads/products/' . ($p['photo'] ?? 'default.png')) ?>" alt="Foto" class="product-img-thumb" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #dee2e6;">
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block"><?= esc($p['name']) ?></span>
                                    <small class="text-muted"><?= esc($p['unit'] ?? 'pcs') ?> | Min. Order: <?= esc($p['min_order'] ?? 1) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= esc($p['category_name'] ?? 'Tanpa Kategori') ?></span>
                                </td>
                                <td class="text-end fw-bold text-dark">
                                    Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center fw-bold">
                                    <?= esc($p['stock']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($p['status'] === 'aktif'): ?>
                                        <span class="badge bg-success badge-status text-white" style="border-radius:20px; padding: 4px 10px; font-size:0.7rem; font-weight:700; text-transform:uppercase;">Aktif</span>
                                    <?php elseif ($p['status'] === 'habis'): ?>
                                        <span class="badge bg-warning badge-status text-white" style="border-radius:20px; padding: 4px 10px; font-size:0.7rem; font-weight:700; text-transform:uppercase;">Habis</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary badge-status text-white" style="border-radius:20px; padding: 4px 10px; font-size:0.7rem; font-weight:700; text-transform:uppercase;">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($p['approval_status'] === 'approved'): ?>
                                        <span class="badge bg-success badge-status text-white" style="border-radius:20px; padding: 4px 10px; font-size:0.7rem; font-weight:700; text-transform:uppercase;"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                    <?php elseif ($p['approval_status'] === 'rejected'): ?>
                                        <span class="badge bg-danger badge-status text-white" style="border-radius:20px; padding: 4px 10px; font-size:0.7rem; font-weight:700; text-transform:uppercase;"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning badge-status text-white" style="border-radius:20px; padding: 4px 10px; font-size:0.7rem; font-weight:700; text-transform:uppercase;"><i class="fas fa-clock me-1"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('admin/sales/suppliers/' . $supplier['id'] . '/products/edit/' . $p['id']) ?>" class="btn btn-warning btn-action btn-sm me-1" title="Edit Produk">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-action btn-sm btn-delete-product" data-url="<?= site_url('admin/sales/suppliers/' . $supplier['id'] . '/products/delete/' . $p['id']) ?>" title="Hapus Produk">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
