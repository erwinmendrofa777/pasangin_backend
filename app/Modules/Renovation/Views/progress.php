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
                        $stLabel = 'PENDING';
                        if ($st === 'APPROVED') {
                            $stClass = 'st-approved';
                            $pillClass = 'pill-approved';
                            $stLabel = 'APPROVED';
                        } elseif ($st === 'REJECTED') {
                            $stClass = 'st-rejected';
                            $pillClass = 'pill-rejected';
                            $stLabel = 'REJECTED';
                        } elseif ($st === 'PENDING_CLIENT') {
                            $stClass = 'st-pending-client';
                            $pillClass = 'pill-pending-client';
                            $stLabel = 'PENDING CLIENT';
                        } else {
                            $stLabel = $st;
                        }
                        ?>
                        <div class="progress-item-card <?= $stClass ?>">
                            <div class="d-flex align-items-start gap-3">

                                <!-- Number -->
                                <div class="prog-num"><?= $localNo ?></div>

                                <!-- Photo -->
                                <?php if (!empty($app['photo'])): ?>
                                    <?php
                                    $fileUrl = base_url('uploads/progress/' . $app['photo']);
                                    $ext = strtolower(pathinfo($app['photo'], PATHINFO_EXTENSION));
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
                                    ?>
                                    <?php if ($isVideo): ?>
                                        <!-- Hidden video player container for GLightbox native playback -->
                                        <div style="display:none;" id="video-progress-<?= $app['id'] ?>">
                                            <div class="p-3 text-center"
                                                style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                                <video src="<?= $fileUrl ?>" controls
                                                    style="width:100%; max-height:60vh; border-radius:8px; display:block;"
                                                    preload="metadata" playsinline></video>
                                                <div class="text-white mt-2 text-start px-2">
                                                    <h6 class="mb-1 fw-bold text-white">Video Progress</h6>
                                                    <small class="text-muted">Tanggal: <?= esc($app['created_at']) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="#video-progress-<?= $app['id'] ?>"
                                            class="glightbox prog-photo-thumb position-relative d-flex align-items-center justify-content-center bg-dark"
                                            data-gallery="progress-gallery" data-type="inline" data-slide-class="glightbox-video-slide"
                                            style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; text-decoration:none;"
                                            title="Putar Video">
                                            <i class="fas fa-play text-warning" style="font-size:1.15rem;"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= $fileUrl ?>" class="glightbox" data-gallery="progress-gallery"
                                            data-title="Foto Progress" data-description="Tanggal: <?= esc($app['created_at']) ?>">
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
                                            <i class="fas fa-weight-hanging mr-1 text-primary"></i><?= esc($app['bobot']) ?>
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
                                            <?= esc($stLabel) ?>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <?php if ($st === 'PENDING_CLIENT'): ?>
                                            <li>
                                                <a class="dropdown-item active text-white" href="#" style="background-color:#6366f1 !important; color:#fff !important; cursor:default;" onclick="event.preventDefault();">
                                                    <i class="fas fa-user-clock mr-2 text-white"></i>PENDING CLIENT
                                                </a>
                                            </li>
                                            <?php endif; ?>
                                            <li>
                                                <a class="dropdown-item <?= $st === 'PENDING' ? 'active bg-warning text-white' : '' ?>"
                                                    href="#"
                                                    onclick="event.preventDefault(); updateProgressStatus(this, <?= $app['id'] ?>, 'PENDING', '<?= $st ?>')">
                                                    <i class="fas fa-clock mr-2 text-warning"></i>PENDING
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item <?= $st === 'APPROVED' ? 'active bg-success text-white' : '' ?>"
                                                    href="#"
                                                    onclick="event.preventDefault(); updateProgressStatus(this, <?= $app['id'] ?>, 'APPROVED', '<?= $st ?>')">
                                                    <i class="fas fa-check-circle mr-2 text-success"></i>APPROVED
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item <?= $st === 'REJECTED' ? 'active bg-danger text-white' : '' ?>"
                                                    href="#"
                                                    onclick="event.preventDefault(); updateProgressStatus(this, <?= $app['id'] ?>, 'REJECTED', '<?= $st ?>')">
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