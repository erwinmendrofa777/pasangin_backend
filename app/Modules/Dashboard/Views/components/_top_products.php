<div class="card dash-card">
  <div class="card-header d-flex align-items-center gap-2">
    <i class="fas fa-trophy text-warning"></i> Top 5 Products
  </div>
  <div class="card-body p-0">
    <div class="list-scroll p-4">
      <?php if (!empty($topProducts)): ?>
        <?php foreach ($topProducts as $product): ?>
          <div class="product-item">
            <?php if (!empty($product['product_photo'])): ?>
              <?php $src = strpos($product['product_photo'], 'http') === 0 ? $product['product_photo'] : base_url('uploads/products/' . $product['product_photo']); ?>
              <img src="<?= $src ?>" class="product-thumb" alt="<?= esc($product['product_name']) ?>">
            <?php else: ?>
              <div class="product-thumb-placeholder"><i class="fas fa-image"></i></div>
            <?php endif; ?>
            <div class="flex-grow-1 text-start" style="min-width:0;">
              <div class="product-name"><?= esc($product['product_name'] ?? 'N/A') ?></div>
              <div class="product-meta"><?= esc($product['supplier_name'] ?? 'N/A') ?></div>
            </div>
            <div class="text-end">
              <div class="product-price">Rp <?= number_format($product['product_price'], 0, ',', '.') ?></div>
              <div class="product-sales"><?= number_format($product['total_sales']) ?> terjual</div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-center text-muted py-4 small">
          <i class="fas fa-box-open fa-2x mb-2 d-block opacity-50"></i>
          Belum ada data penjualan produk
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
