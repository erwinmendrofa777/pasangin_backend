<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

    <!-- Card Header: Search & Add -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-truck me-2"></i>Daftar Supplier
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper pb-sm-0 pb-3">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon...">
            </div>
            <?php if (can('suppliers_create')): ?>
                <a href="<?= base_url('admin/suppliers/create') ?>"
                    class="btn btn-primary d-flex align-items-center justify-content-center"
                    style="border-radius: 12px; font-size: 0.88rem; padding: 3px 16px; white-space: nowrap;">
                    <i class="fas fa-plus me-1"></i> Tambah Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center" style="width: 5%;">No</th>
                        <th class="text-center">Nama Supplier</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Kontak Person</th>
                        <th class="text-center">Telepon</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $key => $supplier): ?>
                        <tr class="text-center align-middle">
                            <td>
                                <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                            </td>
                            <td class="fw-semibold text-start ps-3"><?= esc($supplier['name']) ?></td>
                            <td class="text-muted"><?= esc($supplier['email']) ?></td>
                            <td class="text-muted"><?= esc($supplier['contact_person']) ?></td>
                            <td class="text-muted"><?= esc($supplier['phone']) ?></td>
                            <td>
                                <?php
                                $status = $supplier['status'];
                                $statusMap = [
                                    'approved' => ['class' => 'status-approved', 'icon' => 'fas fa-check-circle', 'label' => 'Approved'],
                                    'pending' => ['class' => 'status-pending', 'icon' => 'fas fa-clock', 'label' => 'Pending'],
                                    'rejected' => ['class' => 'status-rejected', 'icon' => 'fas fa-times-circle', 'label' => 'Rejected'],
                                    'banned' => ['class' => 'status-banned', 'icon' => 'fas fa-ban', 'label' => 'Banned'],
                                ];
                                $s = $statusMap[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];
                                ?>
                                <span class="status-badge <?= $s['class'] ?>">
                                    <i class="<?= $s['icon'] ?>"></i><?= $s['label'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('suppliers')): ?>
                                        <a href="<?= base_url('admin/suppliers/detail/' . $supplier['id']) ?>"
                                            class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (can('suppliers_edit')): ?>
                                        <a href="<?= base_url('admin/suppliers/edit/' . $supplier['id']) ?>"
                                            class="btn-action btn-action-edit" data-toggle="tooltip" title="Edit Supplier">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
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
