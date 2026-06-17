<!-- ===== TABLE CARD ===== -->
<div class="card table-card">

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