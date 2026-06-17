<!-- ===== TABLE CARD: DAFTAR PERMINTAAN DESAIN ===== -->
<div class="card table-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1" style="width:100%">
                <thead class="text-center">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama Client</th>
                        <th class="text-center">Tanggal Pengajuan</th>
                        <th class="text-center">Konsep</th>
                        <th class="text-center">Estimasi Pengerjaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)): ?>
                        <?php foreach ($requests as $key => $row): ?>
                            <tr class="text-center align-middle">
                                <td>
                                    <span class="fw-semibold text-muted" style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                </td>
                                <td>
                                    <div class="text-start text-center ps-3">
                                        <span class="fw-bold text-dark d-block"><?= esc($row['full_name']) ?></span>
                                        <span class="text-muted"
                                            style="font-size: 0.8rem;"><?= esc($row['phone_number']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold"><i
                                            class="far fa-calendar-alt text-muted me-1"></i><?= date('d M Y', strtotime($row['created_at'])) ?></span>
                                </td>
                                <td class="fw-bold text-primary"><?= esc($row['design_concept']) ?></td>
                                <td>
                                    <?php if (!empty($row['start_date']) && !empty($row['target_date'])): ?>
                                        <div class="text-start d-inline-block">
                                            <div style="font-size: 0.75rem;"><strong
                                                    class="text-dark"><?= date('d M Y', strtotime($row['start_date'])) ?> -
                                                    <?= date('d M Y', strtotime($row['target_date'])) ?></strong></div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic" style="font-size: 0.75rem;">Belum dijadwalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = $row['status'];
                                    $sClass = 'status-default';
                                    $sIcon = 'fas fa-circle';
                                    if ($status == 'PENDING') {
                                        $sClass = 'status-pending';
                                        $sIcon = 'fas fa-clock';
                                    } elseif ($status == 'SURVEY_SCHEDULED') {
                                        $sClass = 'status-survey';
                                        $sIcon = 'fas fa-calendar-check';
                                    } elseif ($status == 'PAYMENT_VERIFIED') {
                                        $sClass = 'status-payment';
                                        $sIcon = 'fas fa-file-invoice-dollar';
                                    } elseif ($status == 'COMPLETED') {
                                        $sClass = 'status-completed';
                                        $sIcon = 'fas fa-check-circle';
                                    } elseif ($status == 'CANCELLED') {
                                        $sClass = 'status-cancelled';
                                        $sIcon = 'fas fa-times-circle';
                                    }
                                    ?>
                                    <span class="status-badge <?= $sClass ?>">
                                        <i class="<?= $sIcon ?>"></i> <?= esc(str_replace('_', ' ', $status)) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if (can('design_detail')): ?>
                                            <a href="<?= base_url('admin/design/show/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn-action btn-action-disabled" data-toggle="tooltip"
                                                title="Akses Detail Terkunci">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>

                                        <?php if (can('design_delete')): ?>
                                            <a href="<?= base_url('admin/design/delete/' . $row['id']) ?>"
                                                class="btn-action btn-action-delete"
                                                onclick="return confirm('Yakin hapus data ini?')" data-toggle="tooltip"
                                                title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn-action btn-action-disabled" data-toggle="tooltip"
                                                title="Akses Hapus Terkunci">
                                                <i class="fas fa-lock"></i>
                                            </button>
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
