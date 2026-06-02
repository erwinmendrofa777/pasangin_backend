<?php
$status = $user['status'] ?? 'unknown';
?>
<?php if (can('users_status')): ?>
    <div class="card shadow-sm mb-3 action-card">

        <!-- Card Header -->
        <div class="card-header">
            <h6 class="text-white mb-0 fw-bold">
                <i class="fas fa-sliders-h me-2"></i>Kelola Status Akun
            </h6>
        </div>

        <div class="card-body p-4 pt-3 pb-2">
            <div class="d-grid gap-2">
                <?php
                $actions = [
                    'approved' => ['color' => 'success', 'icon' => 'fas fa-check-circle',  'label' => 'Approved',  'desc' => 'Akun aktif & dapat digunakan'],
                    'pending'  => ['color' => 'warning', 'icon' => 'fas fa-clock',          'label' => 'Pending',   'desc' => 'Akun menunggu verifikasi'],
                    'rejected' => ['color' => 'danger',  'icon' => 'fas fa-times-circle',   'label' => 'Rejected',  'desc' => 'Akun ditolak oleh admin'],
                    'banned'   => ['color' => 'dark',    'icon' => 'fas fa-ban',            'label' => 'Banned',    'desc' => 'Akun diblokir permanen'],
                ];
                foreach ($actions as $key => $act):
                    $isActive = ($status === $key);
                ?>
                    <button type="button"
                        class="btn <?= $isActive ? 'btn-' . $act['color'] : 'btn-outline-' . $act['color'] ?> status-action-btn text-start"
                        <?= $isActive ? 'disabled' : '' ?>
                        <?= !$isActive ? 'data-bs-toggle="modal" data-bs-target="#globalStatusModal"' : '' ?>
                        data-status="<?= $key ?>"
                        data-status-label="<?= $act['label'] ?>"
                        data-color="<?= $act['color'] ?>"
                        data-icon="<?= $act['icon'] ?>"
                        data-status-url="<?= base_url('admin/users/update_status/' . $user['id']) ?>"
                        data-status-msg="Status akun <strong><?= esc($user['full_name']) ?></strong> akan segera diperbarui. Pastikan keputusan ini sudah benar.">
                        <div class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center gap-2">
                                <i class="<?= $act['icon'] ?>" style="width:16px; text-align:center;"></i>
                                <div>
                                    <div style="font-size:0.88rem; font-weight:700; line-height:1.2;"><?= $act['label'] ?></div>
                                    <div style="font-size:0.72rem; font-weight:400; opacity:0.75;"><?= $act['desc'] ?></div>
                                </div>
                            </div>
                            <?php if ($isActive): ?>
                                <i class="fas fa-check-circle ms-2" style="font-size:1rem;"></i>
                            <?php else: ?>
                                <i class="fas fa-chevron-right ms-2" style="font-size:0.75rem; opacity:0.6;"></i>
                            <?php endif; ?>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- Note -->
            <div class="mt-3 pt-3 border-top">
                <p class="text-muted mb-0" style="font-size:0.78rem;">
                    <i class="fas fa-info-circle text-primary me-1"></i>
                    Tombol berwarna solid menunjukkan status yang sedang aktif dan tidak dapat dipilih kembali.
                </p>
            </div>

        </div>
    </div>
<?php endif; ?>

<!-- Actions Card (Hapus) -->
<?php if (can('users_delete')): ?>
    <div class="card shadow-sm section-card">
        <div class="card-body">
            <button type="button"
                class="btn btn-outline-danger w-100"
                style="border-radius:10px; font-size:0.85rem; font-weight:600;"
                data-bs-toggle="modal"
                data-bs-target="#globalDeleteModal"
                data-delete-url="<?= base_url('admin/users/delete/' . $user['id']) ?>"
                data-delete-title="Hapus User Ini?"
                data-delete-msg="Anda akan menghapus akun <strong><?= esc($user['full_name']) ?></strong>. Tindakan ini permanen dan data yang dihapus tidak dapat dikembalikan.">
                <i class="fas fa-trash-alt me-2"></i>Hapus User Ini
            </button>
        </div>
    </div>
<?php endif; ?>
