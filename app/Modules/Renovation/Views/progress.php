<style>
    .animate-up {
        animation: progFadeUp 0.4s ease both;
    }

    @keyframes progFadeUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .progress-title {
        font-size: 1rem;
        font-weight: 700;
        color: #34395e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .progress-count-badge {
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        border-radius: 50px;
        padding: 4px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    /* ── Target Group Card ── */
    .target-group-card {
        border: 1px solid #e4e9f0;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 16px;
        background: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .target-group-header {
        background: linear-gradient(135deg, #f8f9ff, #eef1ff);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s ease;
        border-bottom: 1px solid #e4e9f0;
    }

    .target-group-header:hover {
        background: #eaedff;
    }

    .target-group-header .target-name {
        font-weight: 700;
        font-size: 0.88rem;
        color: #34395e;
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .target-group-header .target-name .tg-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .target-group-header .target-name>div {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .target-group-header .tg-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .target-group-header .tg-chevron {
        transition: transform 0.25s ease;
        color: #6777ef;
        font-size: 0.8rem;
    }

    .target-group-header.collapsed .tg-chevron {
        transform: rotate(-90deg);
    }

    .tg-count-pill {
        background: #e0e4ff;
        color: #6777ef;
        border-radius: 50px;
        padding: 2px 10px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    /* ── Progress Card inside group ── */
    .progress-item-card {
        padding: 16px 20px;
        background: #fff;
        transition: all 0.2s ease;
        position: relative;
        border-bottom: 1px solid #f0f2f5;
    }

    .progress-item-card:last-child {
        border-bottom: none;
    }

    .progress-item-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #e9ecef;
        transition: background 0.2s ease;
    }

    .progress-item-card:hover {
        background: #fafbff;
    }

    .progress-item-card:hover::before {
        background: #6777ef;
    }

    .progress-item-card.st-approved::before {
        background: #47c363;
    }

    .progress-item-card.st-rejected::before {
        background: #fc544b;
    }

    .progress-item-card.st-pending::before {
        background: #ffa426;
    }

    /* ── Status pills ── */
    .prog-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.4px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .pill-approved {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .pill-rejected {
        background: #f8d7da;
        color: #842029;
    }

    .pill-pending {
        background: #fff3cd;
        color: #7d5a00;
    }

    .prog-status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* ── Number & Photos ── */
    .prog-num {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: #f0f3ff;
        color: #6777ef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .prog-photo-thumb {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .prog-photo-thumb:hover {
        transform: scale(1.1);
    }

    .prog-no-photo {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ced4da;
    }

    /* ── Mobile Optimization ── */
    @media (max-width: 575px) {
        .progress-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .target-group-header {
            padding: 12px 15px;
        }

        .target-group-header .tg-meta .tg-count-pill {
            display: none;
        }

        .progress-item-card {
            padding: 15px;
        }

        .progress-item-card .d-flex {
            flex-wrap: wrap;
        }

        .progress-item-card .prog-dropdown {
            width: 100%;
            margin-top: 12px;
        }

        .progress-item-card .prog-status-pill {
            width: 100%;
            justify-content: center;
        }

        .prog-num {
            display: none;
        }

        /* Hide number on mobile to save space */
    }

    /* ── Empty state ── */
    .progress-empty {
        text-align: center;
        padding: 60px 20px;
    }

    .progress-empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: #f0f3ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.8rem;
        color: #6777ef;
        opacity: 0.5;
    }

    /* ===== GLIGHTBOX VIDEO INLINE SLIDE PREMIUM SYSTEM ===== */
    .glightbox-video-slide .gslide-inline {
        background: #000000 !important;
        border-radius: 16px;
        padding: 0 !important;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8) !important;
        max-width: 850px !important;
    }

    .glightbox-video-slide .gslide-inner-content {
        background: transparent !important;
    }

    .glightbox-video-slide .gslide-description {
        background: rgba(0, 0, 0, 0.85) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px 20px !important;
    }

    .glightbox-video-slide .gslide-media {
        box-shadow: none !important;
    }
</style>

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
                                            <?= esc($st) ?>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
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

<script>
    function updateProgressStatus(el, id, newStatus, oldStatus) {
        if (newStatus === oldStatus) return;
        if (!confirm('Ubah status menjadi ' + newStatus + '?')) return;
        $.post('<?= base_url('admin/renovation/update_progress_status') ?>/' + id + '/' + newStatus, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function (res) {
            location.reload();
        }).fail(function () {
            alert('Gagal update status!');
        });
    }

    // Toggle chevron rotation on collapse
    $(document).on('show.bs.collapse', '.target-group-card .collapse', function () {
        $(this).prev('.target-group-header').removeClass('collapsed');
    });
    $(document).on('hide.bs.collapse', '.target-group-card .collapse', function () {
        $(this).prev('.target-group-header').addClass('collapsed');
    });
</script>