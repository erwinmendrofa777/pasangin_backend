<!-- ===== TABLE CARD: Daftar Pesanan ===== -->
<div class="card table-card">

    <!-- Card Header: Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-list me-2"></i>Daftar Pesanan
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari ID, nama penerima...">
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">ID Order</th>
                        <th class="text-center">Nama Penerima</th>
                        <th class="text-center">Total Harga</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $key => $order): ?>
                            <tr class="text-center align-middle">
                                <td>
                                    <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                </td>
                                <td class="fw-bold"><?= esc($order['order_id']) ?></td>
                                <td class="text-start ps-3 fw-semibold text-dark"><?= esc($order['recipient_name']) ?></td>
                                <td class="fw-bold text-primary">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                                <td>
                                    <?php
                                    $status = $order['status'];
                                    $sClass = 'status-default';
                                    $sIcon  = 'fas fa-circle';
                                    if (in_array($status, ['PAID', 'SETTLEMENT', 'SHIPPED', 'COMPLETED'])) {
                                        $sClass = 'status-paid';
                                        $sIcon  = 'fas fa-check-circle';
                                    } elseif (in_array($status, ['CANCELLED', 'EXPIRED'])) {
                                        $sClass = 'status-cancelled';
                                        $sIcon  = 'fas fa-times-circle';
                                    } elseif (in_array($status, ['PENDING', 'UNPAID'])) {
                                        $sClass = 'status-pending';
                                        $sIcon  = 'fas fa-clock';
                                    }
                                    ?>
                                    <span class="status-badge <?= $sClass ?>">
                                        <i class="<?= $sIcon ?>"></i> <?= esc($status) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if (can('orders')): ?>
                                            <a href="<?= base_url('admin/orders/detail/' . $order['id']) ?>"
                                                class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
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
