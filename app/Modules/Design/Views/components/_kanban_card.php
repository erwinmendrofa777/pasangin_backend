<?php
$tStatus = $task['status'];
$tColor = 'pending';
$statusLabel = 'Belum Dikerjakan';

if ($tStatus === 'PENDING') {
    $statusLabel = 'Belum Dikerjakan';
    $tColor = 'pending';
} elseif ($tStatus === 'ON PROGRESS') {
    $statusLabel = 'Sedang Diproses';
    $tColor = 'progress';
} elseif ($tStatus === 'DONE') {
    $statusLabel = 'Selesai';
    $tColor = 'done';
}

$approvedDesigns = (int) ($task['approved_designs'] ?? 0);
$pendingDesignsCount = (int) ($task['pending_designs'] ?? 0);
$totalDesigns = (int) ($task['total_designs'] ?? 0);
$pendingDesigns = $pendingDesigns ?? [];

if ($totalDesigns > 0) {
    if ($approvedDesigns > 0) {
        $statusLabel = 'Disetujui';
        $tColor = 'done';
    } elseif ($pendingDesignsCount > 0) {
        $statusLabel = 'Tinjauan';
        $tColor = 'review';
    } else {
        $statusLabel = 'Perlu Revisi';
        $tColor = 'revisi';
    }
}

// Hitung tanggal mulai dan target selesai berdasarkan request_start_date
$timelineStr = 'Hari ' . esc($task['start_week']) . ' - ' . esc($task['end_week']);
if (!empty($task['request_start_date'])) {
    $projStart = new DateTime($task['request_start_date']);

    $tStart = clone $projStart;
    if ($task['start_week'] > 1) {
        $tStart->modify('+' . ($task['start_week'] - 1) . ' days');
    }

    $tEnd = clone $projStart;
    if ($task['end_week'] > 1) {
        $tEnd->modify('+' . ($task['end_week'] - 1) . ' days');
    }

    $timelineStr = $tStart->format('d M') . ' - ' . $tEnd->format('d M');
}

// Hitung tanggal mulai dan selesai proyek design
$projectTimelineStr = 'Belum disetel';
if (!empty($task['request_start_date']) && !empty($task['request_target_date'])) {
    $pStart = new DateTime($task['request_start_date']);
    $pEnd = new DateTime($task['request_target_date']);
    if ($pStart->format('Y') === $pEnd->format('Y')) {
        $projectTimelineStr = $pStart->format('d M') . ' - ' . $pEnd->format('d M Y');
    } else {
        $projectTimelineStr = $pStart->format('d M Y') . ' - ' . $pEnd->format('d M Y');
    }
}
?>

<div class="kanban-card" data-id="<?= $task['id'] ?>" data-request-id="<?= $task['design_request_id'] ?>"
    data-task-name="<?= esc($task['task_name']) ?>"
    data-concept="<?= esc($task['design_concept'] ?? 'Proyek Khusus') ?>"
    data-client-name="<?= esc($task['client_name'] ?? 'Internal') ?>" data-timeline="<?= esc($timelineStr) ?>"
    data-project-timeline="<?= esc($projectTimelineStr) ?>" data-status="<?= esc($statusLabel) ?>"
    data-keterangan="<?= esc($task['keterangan'] ?? '') ?>"
    data-created-at="<?= date('d M Y, H:i', strtotime($task['created_at'])) ?>">
    <!-- Card Header: Status & Deadline -->
    <div class="d-flex justify-content-between align-items-center">
        <span class="kanban-card-badge badge-<?= $tColor ?> mb-0"><?= $statusLabel ?></span>
        <span class="kanban-card-deadline text-muted" style="font-size: 0.72rem; font-weight: 700;"
            title="Jadwal Pengerjaan: Hari <?= esc($task['start_week']) ?> - <?= esc($task['end_week']) ?>">
            <?= esc($timelineStr) ?>
        </span>
    </div>

    <!-- Task Title & Concept Row -->
    <div class="d-flex justify-content-between align-items-start gap-2">
        <div class="kanban-card-title mb-0" style="margin-bottom: 0 !important; flex-grow: 1;">
            <?= esc($task['task_name']) ?></div>
        <div class="kanban-card-concept text-nowrap" style="margin-bottom: 0 !important; padding-top: 2px;"><i
                class="fas fa-paint-brush"></i> <?= esc($task['design_concept'] ?? 'Proyek Khusus') ?></div>
    </div>

    <!-- Client Name Highlight -->
    <div class="kanban-card-client-badge" title="Proyek Klien">
        <i class="fas fa-user-circle"></i> Proyek: <span
            class="client-name"><?= esc($task['client_name'] ?? 'Internal') ?></span>
    </div>

    <!-- File Preview Row for Awaiting Review (TINJAUAN) -->
    <?php if (!empty($pendingDesigns) && $pendingDesignsCount > 0): ?>
        <div class="review-preview-row">
            <?php foreach ($pendingDesigns as $pd):
                $dtype = $pd['design_type'] ?? 'general';
                $isPdf = ($dtype === 'pdf');
                $isVideo = ($dtype === 'video');
                $is3d = ($dtype === '3d');
                $isImg = ($dtype === 'image');
                $fileUrl = base_url('uploads/design_results/' . $pd['file']);
                ?>
                <div class="review-preview-item" data-design-id="<?= $pd['id'] ?>">
                    <?php if ($isPdf): ?>
                        <a href="<?= $fileUrl ?>" target="_blank" title="Lihat PDF: <?= esc($pd['design_name']) ?>">
                            <div class="pdf-file-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                        </a>
                    <?php elseif ($isVideo): ?>
                        <!-- Video Lightbox -->
                        <div style="display:none;" id="kanban-video-<?= $pd['id'] ?>">
                            <div class="p-3 text-center"
                                style="background:#000; border-radius:12px; max-width:800px; margin:0 auto;">
                                <video src="<?= $fileUrl ?>" controls
                                    style="width:100%; max-height:60vh; border-radius:8px; display:block;" preload="metadata"
                                    playsinline></video>
                                <div class="text-white mt-2 text-start px-2">
                                    <h6 class="mb-1 fw-bold text-white"><?= esc($pd['design_name']) ?></h6>
                                    <small class="text-muted">Revisi: Rev. <?= $pd['revision_number'] ?></small>
                                </div>
                            </div>
                        </div>
                        <a href="#kanban-video-<?= $pd['id'] ?>" class="glightbox" data-gallery="kanban-gallery-<?= $task['id'] ?>"
                            data-slide-class="glightbox-video-slide" title="Putar Video: <?= esc($pd['design_name']) ?>">
                            <i class="fas fa-file-video text-warning"
                                style="font-size:24px; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);"></i>
                            <div class="video-play-icon">
                                <i class="fas fa-play"></i>
                            </div>
                        </a>
                    <?php elseif ($is3d): ?>
                        <!-- 3D Object Name Copy Button -->
                        <button type="button" class="glightbox-none" 
                                onclick="navigator.clipboard.writeText('<?= esc($pd['file']) ?>'); iziToast.success({title: 'Copied', message: 'Nama objek disalin!', position: 'topRight'});" 
                                title="Salin Nama Objek 3D: <?= esc($pd['file']) ?>"
                                style="width: 100%; height: 100%; border: none; background: #eafcff; color: #0dcaf0; display: flex; align-items: center; justify-content: center; border-radius: 6px; outline: none;">
                            <i class="far fa-copy" style="font-size: 16px;"></i>
                        </button>
                    <?php else: ?>
                        <!-- Image Lightbox -->
                        <a href="<?= $fileUrl ?>" class="glightbox" data-gallery="kanban-gallery-<?= $task['id'] ?>"
                            data-title="<?= esc($pd['design_name']) ?>"
                            data-description="Revisi: Rev. <?= $pd['revision_number'] ?>">
                            <img src="<?= $fileUrl ?>" alt="<?= esc($pd['design_name']) ?>">
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Re-assign Designer Dropdown & Details action -->
    <div class="mt-0 pt-2" style="border-top:1px dashed #f1f5f9;">
        <?php 
        $role = strtolower(session()->get('role') ?? '');
        $isSuperAdmin = in_array('super_admin_override', session()->get('permissions') ?? []);
        if ($role === 'kepala divisi desain' || $isSuperAdmin): 
        ?>
        <label class="d-block mb-1" style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.6px;"><i class="fas fa-user-edit me-1"></i>Tugaskan Desainer</label>
        <div class="d-flex justify-content-between align-items-center">
            <div class="flex-grow-1 me-2">
                <select class="form-select form-select-sm kanban-card-designer-select" data-target-id="<?= $task['id'] ?>">
                    <option value="">— Pilih Desainer —</option>
                    <?php foreach ($designers as $designer): ?>
                        <option value="<?= $designer['id'] ?>" <?= ($task['user_admin_id'] == $designer['id']) ? 'selected' : '' ?>>
                            <?= esc($designer['full_name'] ?? $designer['username']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Detail link (eye) -->
            <a href="<?= base_url('admin/design/show/' . $task['design_request_id']) ?>" class="btn-kanban-action shadow-sm"
                data-toggle="tooltip" title="Buka Detail Proyek" style="flex-shrink: 0;">
                <i class="fas fa-eye"></i>
            </a>
        </div>
        <?php else: ?>
        <div class="d-flex justify-content-between align-items-center">
            <div class="flex-grow-1 me-2 text-muted text-truncate" style="font-size: 0.8rem; font-weight: 600;" title="<?= esc($task['designer_name'] ?? 'Belum Ditugaskan') ?>">
                <i class="fas fa-user-circle me-1 text-primary"></i> <?= esc($task['designer_name'] ?? 'Belum Ditugaskan') ?>
            </div>
            <!-- Detail link (eye) -->
            <a href="<?= base_url('admin/design/show/' . $task['design_request_id']) ?>" class="btn-kanban-action shadow-sm"
                data-toggle="tooltip" title="Buka Detail Proyek" style="flex-shrink: 0;">
                <i class="fas fa-eye"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>