<!-- ===== DATA TABLE CARD ===== -->
<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped w-100" id="table-ahsp">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">Detail</th>
                        <th class="text-center" style="width: 80px;">No</th>
                        <th style="width: 150px;">Kode</th>
                        <th>Uraian Pekerjaan</th>
                        <th>Dibuat Pada</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ahsp)): ?>
                        <?php $no = 1; foreach ($ahsp as $item): ?>
                            <tr class="ahsp-row" data-id="<?= $item['id'] ?>">
                                <td class="text-center toggle-details" style="cursor: pointer; color: #64748b; font-size: 14px;">
                                    <i class="fas fa-chevron-right toggle-icon" style="transition: transform 0.25s ease;"></i>
                                </td>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="fw-bold text-primary"><?= esc($item['kode']) ?></td>
                                <td class="fw-bold text-dark"><?= esc($item['uraian']) ?></td>
                                <td><?= !empty($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <!-- Detail Button -->
                                        <button type="button" class="btn-action btn-action-show btn-detail-ahsp" 
                                                data-bs-toggle="modal" data-bs-target="#ahspDetailModal"
                                                data-id="<?= $item['id'] ?>"
                                                title="Detail AHSP">
                                            <i class="fas fa-eye"></i>
                                        </button>
 
                                        <?php if (can('ahsp_edit')): ?>
                                            <button type="button" class="btn-action btn-action-edit btn-edit-ahsp" 
                                                    data-bs-toggle="modal" data-bs-target="#ahspModal"
                                                    data-id="<?= $item['id'] ?>"
                                                    title="Edit AHSP">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
 
                                        <?php if (can('ahsp_delete')): ?>
                                            <button type="button" class="btn-action btn-action-delete"
                                                    data-bs-toggle="modal" data-bs-target="#globalDeleteModal"
                                                    data-delete-url="<?= site_url('admin/ahsp/delete/' . $item['id']) ?>"
                                                    data-delete-title="Hapus AHSP?"
                                                    data-delete-msg="Apakah Anda yakin ingin menghapus data AHSP <strong><?= esc($item['kode']) ?> - <?= esc($item['uraian']) ?></strong> beserta seluruh rincian bahan dan tenaga kerjanya?"
                                                    title="Hapus AHSP">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <!-- Detail Row dengan Accordion -->
                            <tr class="ahsp-detail-row" id="detail-ahsp-<?= $item['id'] ?>" style="display: none; background: #f8fafc;">
                                <td></td>
                                <td colspan="5" style="padding: 15px 20px;" class="detail-container">
                                    <div class="accordion" id="accordionAhsp-<?= $item['id'] ?>">
                                        
                                        <!-- Accordion Bahan -->
                                        <div class="accordion-item" style="border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 8px; overflow: hidden; background: #fff;">
                                            <h2 class="accordion-header" id="headingBahan-<?= $item['id'] ?>">
                                                <button class="accordion-button collapsed py-2 px-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBahan-<?= $item['id'] ?>" aria-expanded="false" aria-controls="collapseBahan-<?= $item['id'] ?>" style="font-size: 11px; color: #475569; background: #f8fafc; border: none; box-shadow: none;">
                                                    <i class="fas fa-boxes me-2 text-primary"></i> Rincian Bahan / Material (<?= count($item['bahan'] ?? []) ?>)
                                                </button>
                                            </h2>
                                            <div id="collapseBahan-<?= $item['id'] ?>" class="accordion-collapse collapse" aria-labelledby="headingBahan-<?= $item['id'] ?>" data-bs-parent="#accordionAhsp-<?= $item['id'] ?>">
                                                <div class="accordion-body p-2" style="background: #fff;">
                                                    <?php if (!empty($item['bahan'])): ?>
                                                        <table class="table table-bordered table-sm m-0" style="font-size: 10px;">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 50px;" class="text-center">No</th>
                                                                    <th>Nama Bahan</th>
                                                                    <th style="width: 100px;" class="text-center">Satuan</th>
                                                                    <th style="width: 100px;" class="text-end">Koefisien</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $noB = 1; foreach ($item['bahan'] as $b): ?>
                                                                    <tr>
                                                                        <td class="text-center"><?= $noB++ ?></td>
                                                                        <td class="fw-medium"><?= esc($b['uraian']) ?></td>
                                                                        <td class="text-center"><span class="badge bg-light text-dark"><?= esc($b['satuan']) ?></span></td>
                                                                        <td class="text-end font-monospace fw-bold text-primary"><?= number_format($b['koefisien'], 4, ',', '.') ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    <?php else: ?>
                                                        <div class="text-center text-muted small p-2">Tidak membutuhkan bahan/material.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
 
                                        <!-- Accordion Tenaga Kerja -->
                                        <div class="accordion-item" style="border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; background: #fff;">
                                            <h2 class="accordion-header" id="headingTenaga-<?= $item['id'] ?>">
                                                <button class="accordion-button collapsed py-2 px-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTenaga-<?= $item['id'] ?>" aria-expanded="false" aria-controls="collapseTenaga-<?= $item['id'] ?>" style="font-size: 11px; color: #475569; background: #f8fafc; border: none; box-shadow: none;">
                                                    <i class="fas fa-users me-2 text-primary"></i> Rincian Tenaga Kerja (<?= count($item['tenaga_kerja'] ?? []) ?>)
                                                </button>
                                            </h2>
                                            <div id="collapseTenaga-<?= $item['id'] ?>" class="accordion-collapse collapse" aria-labelledby="headingTenaga-<?= $item['id'] ?>" data-bs-parent="#accordionAhsp-<?= $item['id'] ?>">
                                                <div class="accordion-body p-2" style="background: #fff;">
                                                    <?php if (!empty($item['tenaga_kerja'])): ?>
                                                        <table class="table table-bordered table-sm m-0" style="font-size: 10px;">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 50px;" class="text-center">No</th>
                                                                    <th>Klasifikasi Pekerja</th>
                                                                    <th style="width: 80px;" class="text-center">Satuan</th>
                                                                    <th style="width: 80px;" class="text-end">Koefisien</th>
                                                                    <th style="width: 120px;" class="text-end">Harga Satuan</th>
                                                                    <th style="width: 120px;" class="text-end">Jumlah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $noT = 1; foreach ($item['tenaga_kerja'] as $t): 
                                                                    $subTotalT = $t['koefisien'] * $t['harga_satuan'];
                                                                ?>
                                                                    <tr>
                                                                        <td class="text-center"><?= $noT++ ?></td>
                                                                        <td class="fw-medium"><?= esc($t['uraian']) ?></td>
                                                                        <td class="text-center"><span class="badge bg-light text-dark"><?= esc($t['satuan']) ?></span></td>
                                                                        <td class="text-end font-monospace"><?= number_format($t['koefisien'], 4, ',', '.') ?></td>
                                                                        <td class="text-end font-monospace">Rp <?= number_format($t['harga_satuan'], 0, ',', '.') ?></td>
                                                                        <td class="text-end font-monospace fw-bold text-success">Rp <?= number_format($subTotalT, 0, ',', '.') ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    <?php else: ?>
                                                        <div class="text-center text-muted small p-2">Tidak membutuhkan tenaga kerja.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center p-0">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Data AHSP</h6>
                                    <p class="text-muted small mb-0">Silakan tambahkan data AHSP baru dengan mengklik tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
