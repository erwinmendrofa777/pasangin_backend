<!-- ===== DATA TABLE CARD ===== -->
<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped w-100" id="table-satuan">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">No</th>
                        <th>Nama Satuan</th>
                        <th>Dibuat Pada</th>
                        <th>Diubah Pada</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($satuan)): ?>
                        <?php $no = 1; foreach ($satuan as $item): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold text-dark"><?= esc($item['nama_satuan']) ?></td>
                                <td><?= !empty($item['created_at']) ? date('d M Y H:i', $item['created_at']) : '-' ?></td>
                                <td><?= !empty($item['updated_at']) ? date('d M Y H:i', $item['updated_at']) : '-' ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <?php if (can('satuan_edit')): ?>
                                            <button type="button" class="btn-action btn-action-edit btn-edit-satuan" 
                                                    data-bs-toggle="modal" data-bs-target="#satuanModal"
                                                    data-id="<?= $item['id'] ?>"
                                                    data-nama="<?= esc($item['nama_satuan']) ?>"
                                                    title="Edit Satuan">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (can('satuan_delete')): ?>
                                            <button type="button" class="btn-action btn-action-delete"
                                                    data-bs-toggle="modal" data-bs-target="#globalDeleteModal"
                                                    data-delete-url="<?= site_url('admin/satuan/delete/' . $item['id']) ?>"
                                                    data-delete-title="Hapus Satuan?"
                                                    data-delete-msg="Apakah Anda yakin ingin menghapus satuan <strong><?= esc($item['nama_satuan']) ?></strong>?"
                                                    title="Hapus Satuan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center p-0">
                                <div class="empty-state">
                                    <i class="fas fa-balance-scale"></i>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Data Satuan</h6>
                                    <p class="text-muted small mb-0">Silakan tambahkan data satuan baru dengan mengklik tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
