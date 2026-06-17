<!-- ===== TABLE CARD: Daftar Produk ===== -->
<div class="card table-card">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Foto</th>
                        <th class="text-center">Nama Produk</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $key => $row): ?>
                            <tr class="text-center align-middle">
                                <td>
                                    <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                </td>
                                <td>
                                    <?php
                                    $photoUrl = '';
                                    if (!empty($row['photo']) && file_exists('./uploads/products/' . $row['photo'])) {
                                        $photoUrl = base_url('uploads/products/' . $row['photo']);
                                    } elseif (strpos($row['photo'], 'http') === 0) {
                                        $photoUrl = $row['photo'];
                                    }
                                    ?>
                                    <?php if ($photoUrl): ?>
                                        <a href="<?= $photoUrl ?>" class="glightbox" 
                                           data-gallery="product-<?= $row['id'] ?>" 
                                           data-title="<?= esc($row['name']) ?>" 
                                           data-description="Nama Produk: <?= esc($row['name']) ?> &lt;br&gt; Supplier: <?= esc($row['supplier_name'] ?: '-') ?> &lt;br&gt; Harga: Rp <?= number_format($row['price'], 0, ',', '.') ?> &lt;br&gt; Stok: <?= esc($row['stock'] ?: '0') ?>">
                                            <img src="<?= $photoUrl ?>" class="product-img" data-toggle="tooltip" title="Klik untuk memperbesar">
                                        </a>
                                    <?php else: ?>
                                        <div class="product-img d-flex align-items-center justify-content-center bg-light text-muted"
                                            style="margin: 0 auto;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold text-start ps-3"><?= esc($row['name'] ?: '-') ?></td>
                                <td class="text-muted"><?= esc($row['supplier_name'] ?: '-') ?></td>
                                <td class="fw-bold text-dark">Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                                <td class="text-muted"><?= esc($row['stock'] ?: '0') ?></td>
                                <td>
                                    <?php if ($row['status'] === 'aktif' && $row['stock'] > 0): ?>
                                        <span class="status-badge status-available"><i class="fas fa-check-circle"></i>
                                            Tersedia</span>
                                    <?php elseif ($row['status'] === 'aktif' && $row['stock'] <= 0): ?>
                                        <span class="status-badge status-out"><i class="fas fa-clock"></i> Habis</span>
                                    <?php else: ?>
                                        <span class="status-badge status-inactive"><i class="fas fa-ban"></i> Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if (can('products')): ?>
                                            <a href="<?= base_url('admin/products/detail/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye ms-0"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (can('products_delete')): ?>
                                            <a href="<?= base_url('admin/products/delete/' . $row['id']) ?>"
                                                class="btn-action btn-action-delete"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')" data-toggle="tooltip"
                                                title="Hapus Produk">
                                                <i class="fas fa-trash ms-0"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
