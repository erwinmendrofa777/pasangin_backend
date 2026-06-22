<?php
// Pengelompokan tugas berdasarkan status untuk Kanban
$pendingTasks = [];
$progressTasks = [];
$reviewTasks = [];
$doneTasks = [];

foreach ($designerTasks ?? [] as $task) {
    $tStatus = $task['status'];
    $totalDesigns = (int)($task['total_designs'] ?? 0);
    $approvedDesigns = (int)($task['approved_designs'] ?? 0);
    $pendingDesigns = (int)($task['pending_designs'] ?? 0);
    $rejectedDesigns = (int)($task['rejected_designs'] ?? 0);

    if ($tStatus === 'DONE' || $approvedDesigns > 0) {
        $doneTasks[] = $task;
    } elseif ($totalDesigns > 0 && $approvedDesigns == 0 && $pendingDesigns > 0) {
        $reviewTasks[] = $task;
    } elseif ($tStatus === 'ON PROGRESS' || ($totalDesigns > 0 && $rejectedDesigns > 0 && $approvedDesigns == 0)) {
        $progressTasks[] = $task;
    } else {
        $pendingTasks[] = $task;
    }
}
?>

<div class="row">
    <div class="col-12">
        <!-- Kanban Header Card -->
        <div class="card border-0 mb-4 shadow-sm" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4"
                style="background: #fff; gap: 16px; border-radius: 16px;">
                <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
                    style="font-size:0.95rem; letter-spacing:0.4px; text-transform:uppercase;">
                    <i class="fas fa-tasks me-2"></i><?= isset($title) && $title === 'Tugas Saya' ? 'Papan Kanban Tugas Saya' : 'Papan Kanban Tugas Proyek Desain' ?>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div class="search-wrapper" style="width: 280px;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" id="kanbanSearchInput" placeholder="Cari nama tugas, klien, konsep...">
                    </div>
                </div>
            </div>
        </div>

        <div class="kanban-board">
            
            <!-- COLUMN 1: PENDING -->
            <div class="kanban-column pending" id="column-pending" data-status="PENDING">
                <div class="kanban-column-header">
                    <span class="kanban-column-title">
                        <i class="fas fa-clock"></i> Belum Dikerjakan
                    </span>
                    <span class="kanban-column-count" id="count-pending"><?= count($pendingTasks) ?></span>
                </div>
                <div class="kanban-column-body sortable-list" data-status="PENDING">
                    <?php if (empty($pendingTasks)): ?>
                        <div class="kanban-column-empty">
                            <i class="fas fa-tasks fa-lg opacity-50"></i>
                            <span>Kosong</span>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($pendingTasks as $task): ?>
                        <?= view('App\Modules\Design\Views\components\_kanban_card', ['task' => $task, 'designers' => $designers]) ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- COLUMN 2: ON PROGRESS -->
            <div class="kanban-column progress-col" id="column-progress" data-status="ON PROGRESS">
                <div class="kanban-column-header">
                    <span class="kanban-column-title">
                        <i class="fas fa-spinner"></i> Sedang Diproses
                    </span>
                    <span class="kanban-column-count" id="count-progress"><?= count($progressTasks) ?></span>
                </div>
                <div class="kanban-column-body sortable-list" data-status="ON PROGRESS">
                    <?php if (empty($progressTasks)): ?>
                        <div class="kanban-column-empty">
                            <i class="fas fa-magic fa-lg opacity-50"></i>
                            <span>Kosong</span>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($progressTasks as $task): ?>
                        <?= view('App\Modules\Design\Views\components\_kanban_card', ['task' => $task, 'designers' => $designers]) ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- COLUMN 3: TINJAUAN -->
            <div class="kanban-column review" id="column-review" data-status="TINJAUAN">
                <div class="kanban-column-header">
                    <span class="kanban-column-title">
                        <i class="fas fa-hourglass-half"></i> Dalam Tinjauan
                    </span>
                    <span class="kanban-column-count" id="count-review"><?= count($reviewTasks) ?></span>
                </div>
                <!-- Column tinjauan dinonaktifkan drag-into nya di JS agar tidak bisa ditarik manual ke tinjauan tanpa file -->
                <div class="kanban-column-body sortable-list" data-status="TINJAUAN">
                    <?php if (empty($reviewTasks)): ?>
                        <div class="kanban-column-empty">
                            <i class="fas fa-file-invoice fa-lg opacity-50"></i>
                            <span>Tidak Ada Tinjauan</span>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($reviewTasks as $task): ?>
                        <?= view('App\Modules\Design\Views\components\_kanban_card', [
                            'task' => $task, 
                            'designers' => $designers,
                            'pendingDesigns' => $pendingDesignsByTarget[$task['id']] ?? []
                        ]) ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- COLUMN 4: DONE -->
            <div class="kanban-column done" id="column-done" data-status="DONE">
                <div class="kanban-column-header">
                    <span class="kanban-column-title">
                        <i class="fas fa-check-double"></i> Selesai
                    </span>
                    <span class="kanban-column-count" id="count-done"><?= count($doneTasks) ?></span>
                </div>
                <div class="kanban-column-body sortable-list" data-status="DONE">
                    <?php if (empty($doneTasks)): ?>
                        <div class="kanban-column-empty">
                            <i class="fas fa-check-circle fa-lg opacity-50"></i>
                            <span>Kosong</span>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($doneTasks as $task): ?>
                        <?= view('App\Modules\Design\Views\components\_kanban_card', ['task' => $task, 'designers' => $designers]) ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Reject untuk Kanban -->
<div class="modal fade" id="kanbanModalReject" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title mb-0"><i class="fas fa-times-circle me-1"></i>Reject Desain & Minta Revisi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kanbanRejectForm">
                <input type="hidden" name="design_id" id="kanbanRejectDesignId">
                <input type="hidden" name="target_id" id="kanbanRejectTargetId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px;">Catatan Revisi</label>
                        <textarea name="revision_note" id="kanbanRejectNote" class="form-control" rows="3"
                            placeholder="Contoh: Proporsi denah belum sesuai, mohon perbaiki bagian dapur."
                            required style="font-size:13px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" id="btnBatalReject">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger">Kirim Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Kartu Kanban -->
<div class="modal fade" id="kanbanCardDetailModal" tabindex="-1" aria-labelledby="kanbanCardDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title d-flex align-items-center mb-0" id="kanbanCardDetailModalLabel">
                    <i class="fas fa-tasks text-primary me-2" style="font-size: 1.15rem;"></i> Detail Tugas Proyek
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Kolom Kiri: Detail Tugas -->
                    <div class="col-6 border-end pe-4" id="modalDetailLeftCol">
                        <!-- Status & Title -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span id="modalDetailStatus" class="modal-badge"></span>
                            <small class="text-muted" style="font-size: 11px;"><i class="far fa-calendar-alt me-1"></i> <span id="modalDetailTimelineHeader"></span></small>
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-4" id="modalDetailTaskName" style="line-height: 1.4; font-size: 1.15rem;"></h5>
                        
                        <!-- Info Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="modal-info-card" style="background: rgba(229, 57, 53, 0.03); border: 1.5px solid rgba(229, 57, 53, 0.15);">
                                    <span class="info-label" style="color: var(--palette-primary, #e53935); font-weight: 800;"><i class="fas fa-user-circle" style="color: var(--palette-primary, #e53935);"></i> Proyek Klien</span>
                                    <span class="info-value" id="modalDetailClientName" style="color: #0f172a; font-weight: 800; font-size: 0.9rem;"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="modal-info-card">
                                    <span class="info-label"><i class="fas fa-palette"></i> Desainer Pelaksana</span>
                                    <span class="info-value text-designer" id="modalDetailDesigner"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="modal-info-card">
                                    <span class="info-label"><i class="fas fa-calendar-alt"></i> Jadwal Pengerjaan</span>
                                    <span class="info-value" id="modalDetailTimeline"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan / Deskripsi -->
                        <div class="mb-3">
                            <span class="info-label d-block mb-2 text-muted fw-bold" style="font-size: 10px; letter-spacing: 0.8px; text-transform: uppercase;">Keterangan / Deskripsi</span>
                            <textarea id="modalDetailKeterangan" class="form-control" rows="3" placeholder="Tulis keterangan atau instruksi tugas di sini..." style="font-size: 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px; resize: none;"></textarea>
                            <div class="text-end mt-2">
                                <button type="button" id="btnSaveKeterangan" class="btn btn-sm btn-primary py-1 px-3 fw-bold" style="font-size: 11px; border-radius: 8px; height: 28px;">
                                    <i class="fas fa-save me-1"></i> Simpan Catatan
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kolom Kanan: Upload & Hasil Desain -->
                    <div class="col-6 ps-4 d-flex flex-column" id="modalDetailRightCol" style="min-height: 350px;">
                        <!-- Form Unggah Hasil Desain -->
                        <div class="pb-4 border-bottom">
                            <span class="info-label d-block mb-3 text-muted fw-bold" style="font-size: 10px; letter-spacing: 0.8px; text-transform: uppercase;">
                                <i class="fas fa-cloud-upload-alt text-primary me-1"></i> Upload Hasil Desain
                            </span>
                            <form id="modalUploadDesignForm" action="" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="design_targets_id" id="modalUploadTargetId">
                                <input type="hidden" name="user_admin_id" value="<?= session()->get('user_id') ?>">
                                <input type="hidden" name="redirect_to" value="managerial">
                                
                                <div class="row g-2">
                                    <div class="col-12 mb-2">
                                        <label class="form-label mb-1 text-muted fw-bold" style="font-size: 9px; letter-spacing: 0.4px;">NAMA DESAIN</label>
                                        <input type="text" name="design_name" class="form-control form-control-sm" 
                                               placeholder="Contoh: Denah Lantai 1" required style="font-size: 12px; height: 38px; border-radius: 10px; border: 1.5px solid #cbd5e1;">
                                    </div>
                                    <div class="col-12 mb-2" id="modalFileUploadCol">
                                        <label class="form-label mb-1 text-muted fw-bold" style="font-size: 9px; letter-spacing: 0.4px;">PILIH BERKAS (OPSIONAL)</label>
                                        <div class="custom-file-upload">
                                            <label for="modalUploadFile" class="file-upload-label">
                                                <span class="file-upload-filename" id="modalUploadFileNameLabel">Pilih file...</span>
                                                <i class="fas fa-file-upload"></i>
                                            </label>
                                            <input type="file" id="modalUploadFile" name="design_files[]" multiple
                                                   accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv,.obj,.fbx,.glb,.gltf,.dwg,.rvt" class="d-none">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2" id="modal3dObjectCol">
                                        <label class="form-label mb-1 text-muted fw-bold" style="font-size: 9px; letter-spacing: 0.4px;">NAMA OBJEK 3D (STRING) (OPSIONAL)</label>
                                        <input type="text" name="3d_object_name" id="modal3dObjectNameInput" class="form-control form-control-sm" 
                                               placeholder="Contoh: UnityObject_Building_Floor1" style="font-size: 12px; height: 38px; border-radius: 10px; border: 1.5px solid #cbd5e1;">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold d-flex align-items-center justify-content-center" style="height: 38px; border-radius: 10px; font-size: 12px; gap: 4px;">
                                            <i class="fas fa-upload"></i> Upload Sekarang
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- List Hasil Desain -->
                        <div class="pt-4 flex-grow-1 d-flex flex-column">
                            <span class="info-label d-block mb-3 text-muted fw-bold" style="font-size: 10px; letter-spacing: 0.8px; text-transform: uppercase;">
                                <i class="fas fa-images text-primary me-1"></i> Hasil Desain Terunggah
                            </span>
                            <div class="design-results-list flex-grow-1" id="modalDesignResultsList" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                                <!-- AJAX content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
