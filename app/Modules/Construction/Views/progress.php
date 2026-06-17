<?php
// Group progress items by target_key
$groupedProgress = [];
foreach ($progress_list ?? [] as $item) {
    $key = $item['target_key'] ?? 'Tanpa Target';
    $groupedProgress[$key][] = $item;
}
$totalProgress = count($progress_list ?? []);
$totalTargets = count($groupedProgress);
?>

<div class="progress-header">
    <p class="progress-title">
        <i class="fas fa-chart-line text-primary"></i>
        Laporan Progress Proyek
    </p>
    <div class="d-flex align-items-center gap-2">
        <span class="progress-count-badge"><?= $totalTargets ?> Target</span>
        <span class="progress-count-badge"
            style="background: linear-gradient(135deg, #47c363, #5ad178);"><?= $totalProgress ?> Laporan</span>
    </div>
</div>

<?php if (!empty($groupedProgress)): ?>
    <?php $groupIdx = 0;
    foreach ($groupedProgress as $targetName => $items):
        $groupIdx++;
        $approvedCount = count(array_filter($items, fn($i) => $i['status'] === 'APPROVED'));
        $totalItems = count($items);
        $collapseId = 'targetGroup' . $groupIdx;
        ?>
        <div class="target-group-card">
            <!-- Target Header (collapsible) -->
            <div class="target-group-header" data-toggle="collapse" data-target="#<?= $collapseId ?>" aria-expanded="true">
                <div class="target-name">
                    <div class="tg-icon"><i class="fas fa-hammer"></i></div>
                    <div>
                        <div><?= esc($targetName) ?></div>
                        <div style="font-size:0.72rem; font-weight:400; color:#6c757d; margin-top:1px;">
                            <i class="fas fa-check-circle text-success mr-1"></i><?= $approvedCount ?>/<?= $totalItems ?>
                            disetujui
                        </div>
                    </div>
                </div>
                <div class="tg-meta">
                    <span class="tg-count-pill"><?= $totalItems ?> laporan</span>
                    <i class="fas fa-chevron-down tg-chevron"></i>
                </div>
            </div>

            <!-- Target Body -->
            <div class="collapse show" id="<?= $collapseId ?>">
                <div class="target-group-body">
                    <?php $localNo = 0;
                    foreach ($items as $app):
                        $localNo++;
                        $st = strtoupper($app['status'] ?? 'PENDING');
                        $stClass = 'st-pending';
                        $pillClass = 'pill-pending';
                        if ($st === 'APPROVED') {
                            $stClass = 'st-approved';
                            $pillClass = 'pill-approved';
                        } elseif ($st === 'REJECTED') {
                            $stClass = 'st-rejected';
                            $pillClass = 'pill-rejected';
                        }
                        ?>
                        <div class="progress-item-card <?= $stClass ?>">
                            <div class="d-flex align-items-start gap-3">

                                <!-- Number -->
                                <div class="prog-num"><?= $localNo ?></div>

                                <!-- Photo -->
                                <?php if (!empty($app['photo'])): ?>
                                    <?php 
                                    $fileUrl = base_url('uploads/construction/progress/' . $app['photo']);
                                    $ext = strtolower(pathinfo($app['photo'], PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                    $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv']);
                                    ?>
                                    <?php if ($isVideo): ?>
                                        <!-- Hidden video player container for native playbacks -->
                                        <div style="display:none;" id="video-progress-<?= $app['id'] ?>">
                                            <div class="p-3 text-center" style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                <video src="<?= $fileUrl ?>" controls style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata" playsinline></video>
                                                <div class="text-white mt-2 text-start px-2">
                                                    <h6 class="mb-1 fw-bold text-white">Video Progress #<?= $localNo ?></h6>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#video-progress-<?= $app['id'] ?>" class="glightbox d-flex align-items-center justify-content-center bg-light flex-shrink-0 position-relative prog-photo-thumb"
                                            data-gallery="progress-gallery"
                                            data-slide-class="glightbox-video-slide"
                                            data-type="inline"
                                            style="background:#fff9f0 !important; border: 2px solid #ffeeba;">
                                            <i class="fas fa-file-video text-warning" style="font-size:18px;"></i>
                                            <span class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);">
                                                <i class="fas fa-play-circle text-warning bg-white rounded-circle" style="font-size:8px;"></i>
                                            </span>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= $fileUrl ?>" class="glightbox" data-gallery="progress-gallery" data-title="Foto Progress #<?= $localNo ?>">
                                            <img src="<?= $fileUrl ?>" class="prog-photo-thumb" alt="Foto Progress">
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="prog-no-photo"><i class="fas fa-image"></i></div>
                                <?php endif; ?>

                                <!-- Content -->
                                <div class="flex-grow-1" style="min-width:0;">
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <span class="badge badge-light shadow-sm" style="font-size:0.75rem;">
                                            <i class="fas fa-box mr-1 text-primary"></i><?= esc($app['volume']) ?>
                                        </span>
                                        <span class="text-muted" style="font-size:0.78rem;">
                                            <i class="fas fa-calendar-alt mr-1"></i><?= esc($app['created_at']) ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($app['keterangan']) && $app['keterangan'] !== '-'): ?>
                                        <p class="text-muted mb-0 mt-1" style="font-size:0.8rem; line-height:1.4;">
                                            <i class="fas fa-comment-alt mr-1" style="opacity:0.5;"></i><?= esc($app['keterangan']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Status Dropdown -->
                                <div class="flex-shrink-0 prog-dropdown">
                                    <div class="dropdown">
                                        <button type="button" class="prog-status-pill <?= $pillClass ?> dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="dot"></span>
                                            <?= esc($st) ?>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item <?= $st === 'PENDING' ? 'active bg-warning text-white' : '' ?>"
                                                    href="<?= base_url('admin/construction/update_progress_status/' . $app['id'] . '/PENDING') ?>"
                                                    onclick="return confirm('Ubah status menjadi PENDING?')">
                                                    <i class="fas fa-clock mr-2 text-warning"></i>PENDING
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item <?= $st === 'APPROVED' ? 'active bg-success text-white' : '' ?>"
                                                    href="<?= base_url('admin/construction/update_progress_status/' . $app['id'] . '/APPROVED') ?>"
                                                    onclick="return confirm('Ubah status menjadi APPROVED?')">
                                                    <i class="fas fa-check-circle mr-2 text-success"></i>APPROVED
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item <?= $st === 'REJECTED' ? 'active bg-danger text-white' : '' ?>"
                                                    href="<?= base_url('admin/construction/update_progress_status/' . $app['id'] . '/REJECTED') ?>"
                                                    onclick="return confirm('Ubah status menjadi REJECTED?')">
                                                    <i class="fas fa-times-circle mr-2 text-danger"></i>REJECTED
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <div class="progress-empty">
        <div class="progress-empty-icon">
            <i class="fas fa-hard-hat"></i>
        </div>
        <h6 class="font-weight-bold text-dark mb-1">Belum Ada Laporan</h6>
        <p class="text-muted mb-0" style="font-size:0.83rem;">
            Belum ada laporan progress dari tukang di proyek ini.<br>
            Laporan akan muncul setelah tukang mengirim progress pekerjaan.
        </p>
    </div>
<?php endif; ?>