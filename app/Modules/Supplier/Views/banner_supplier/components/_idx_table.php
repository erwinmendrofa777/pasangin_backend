<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center">Banner</th>
                                <th class="text-start">Supplier & Judul Promo</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banners as $key => $row): ?>
                                <tr class="text-center align-middle">
                                    <td>
                                        <span class="fw-semibold text-muted"
                                            style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('uploads/supplier/banner/' . $row['image']) ?>" class="glightbox" data-gallery="supplier-banner-gallery" data-title="<?= esc($row['supplier_name']) ?>" data-description="<?= esc($row['title']) ?>">
                                            <img src="<?= base_url('uploads/supplier/banner/' . $row['image']) ?>"
                                                class="banner-preview" data-toggle="tooltip" title="Klik untuk memperbesar">
                                        </a>
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-bold text-primary"><?= esc($row['supplier_name']) ?></div>
                                        <div class="fw-semibold mt-1"><?= esc($row['title']) ?></div>
                                        <?php if ($row['note']): ?>
                                            <div class="small text-muted mt-1 fst-italic">Note:
                                                <?= esc(substr($row['note'], 0, 50)) ?>...
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 'APPROVED'): ?>
                                            <span class="status-badge status-approved"><i class="fas fa-check-circle"></i>
                                                Approved</span>
                                        <?php elseif ($row['status'] == 'REJECTED'): ?>
                                            <span class="status-badge status-rejected"><i class="fas fa-times-circle"></i>
                                                Rejected</span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending"><i class="fas fa-clock"></i>
                                                Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            <div class="fw-bold text-dark">
                                                <?= date('d M Y', strtotime($row['created_at'])) ?>
                                            </div>
                                            <div><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= base_url('admin/banner-supplier/detail/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" data-toggle="tooltip"
                                                title="Tinjau Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (can('banner_supplier_update')): ?>
                                                <a href="<?= base_url('admin/banner-supplier/edit/' . $row['id']) ?>"
                                                    class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit Data">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can('banner_supplier_delete')): ?>
                                                <button class="btn-action btn-action-delete btn-delete"
                                                    data-id="<?= $row['id'] ?>" data-toggle="tooltip" title="Hapus Banner">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST">
    <?= csrf_field() ?>
</form>
