<?php
// Pengelompokan tugas berdasarkan status untuk Kanban
$pendingTasks = [];
$progressTasks = [];
$reviewTasks = [];
$doneTasks = [];

foreach ($designerTasks ?? [] as $task) {
    $tStatus = $task['status'];
    $totalDesigns = (int) ($task['total_designs'] ?? 0);
    $approvedDesigns = (int) ($task['approved_designs'] ?? 0);
    $pendingDesigns = (int) ($task['pending_designs'] ?? 0);
    $rejectedDesigns = (int) ($task['rejected_designs'] ?? 0);

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
        <div class="card header-card mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: rgba(255, 92, 92, 0.1); color: var(--palette-primary); flex-shrink: 0;">
                                <i class="fas fa-tasks" style="font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;"><?= isset($title) && $title === 'Tugas Saya' ? 'Papan Kanban Tugas Saya' : 'Tugas Proyek Desain' ?></h5>
                                <p class="text-muted mb-0 small">Kelola tugas, unggah berkas, dan pantau revisi secara instan dalam satu papan interaktif.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center justify-content-lg-end gap-2 flex-wrap flex-sm-nowrap">
                            <!-- Search Input -->
                            <div class="search-wrapper" style="width: 280px; max-width: 100%; position: relative;">
                                <input type="text" class="search-input w-100" id="kanbanSearchInput" placeholder="Cari nama tugas, klien, konsep..." style="padding-left: 38px !important; padding-right: 32px !important;">
                                <i class="fas fa-search search-icon"></i>
                                <button type="button" id="kanbanSearchClear" title="Hapus pencarian"
                                    style="display:none; position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:#94a3b8; font-size:0.85rem; line-height:1; cursor:pointer; padding:0; transition: color 0.2s;"
                                    onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#94a3b8'">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="kanbanRejectForm" enctype="multipart/form-data">
                <input type="hidden" name="design_id" id="kanbanRejectDesignId">
                <input type="hidden" name="target_id" id="kanbanRejectTargetId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size:13px;">Catatan Revisi</label>
                        <textarea name="revision_note" id="kanbanRejectNote" class="form-control" rows="3"
                            placeholder="Contoh: Proporsi denah belum sesuai, mohon perbaiki bagian dapur." required
                            style="font-size:13px;"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold" style="font-size:13px;">Lampiran Gambar <span
                                class="text-muted fw-normal">(Opsional, maks. 3 foto)</span></label>
                        <input type="file" name="image_revision_note[]" id="kanbanRejectImages"
                            class="form-control form-control-sm" multiple accept=".jpg,.jpeg,.png,.webp"
                            style="font-size:12px; border-radius: 6px; border: 1.5px solid #cbd5e1;">
                        <small class="text-muted d-block mt-1" style="font-size:10px;"><i
                                class="fas fa-image me-1"></i>Format: JPG, PNG, WEBP.</small>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"
                        id="btnBatalReject">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger">Kirim Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Kartu Kanban -->
<div class="modal fade" id="kanbanCardDetailModal" tabindex="-1" aria-labelledby="kanbanCardDetailModalLabel"
    aria-hidden="true">
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
                            <small class="text-muted" style="font-size: 11px;"><i class="far fa-calendar-alt me-1"></i>
                                <span id="modalDetailTimelineHeader"></span></small>
                        </div>

                        <h5 class="fw-bold text-dark mb-4" id="modalDetailTaskName"
                            style="line-height: 1.4; font-size: 1.15rem;"></h5>

                        <!-- Info Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="modal-info-card"
                                    style="background: rgba(229, 57, 53, 0.03); border: 1.5px solid rgba(229, 57, 53, 0.15);">
                                    <span class="info-label"
                                        style="color: var(--palette-primary, #e53935); font-weight: 800;"><i
                                            class="fas fa-user-circle"
                                            style="color: var(--palette-primary, #e53935);"></i> Proyek Klien</span>
                                    <span class="info-value" id="modalDetailClientName"
                                        style="color: #0f172a; font-weight: 800; font-size: 0.9rem;"></span>
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
                                    <span class="info-label"><i class="fas fa-calendar-alt"></i> Jadwal
                                        Pengerjaan</span>
                                    <span class="info-value" id="modalDetailTimeline"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan / Deskripsi -->
                        <div class="mb-3">
                            <span class="info-label d-block mb-2 text-muted fw-bold"
                                style="font-size: 10px; letter-spacing: 0.8px; text-transform: uppercase;">Keterangan /
                                Deskripsi</span>
                            <textarea id="modalDetailKeterangan" class="form-control" rows="3"
                                placeholder="Tulis keterangan atau instruksi tugas di sini..."
                                style="font-size: 12px; border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px; resize: none;"></textarea>
                            <div class="text-end mt-2">
                                <button type="button" id="btnSaveKeterangan"
                                    class="btn btn-sm btn-primary py-1 px-3 fw-bold"
                                    style="font-size: 11px; border-radius: 8px; height: 28px;">
                                    <i class="fas fa-save me-1"></i> Simpan Catatan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Upload & Hasil Desain -->
                    <div class="col-6 ps-4 d-flex flex-column" id="modalDetailRightCol" style="min-height: 350px;">
                        <div class="row g-4" id="modalRightColRow" style="width: 100%; margin: 0;">
                            <!-- Form Unggah Hasil Desain (hanya tampil saat Sedang Diproses) -->
                            <div id="modalUploadSection" class="pb-4 border-bottom">
                                <span class="info-label d-block mb-3 text-muted fw-bold"
                                    style="font-size: 10px; letter-spacing: 0.8px; text-transform: uppercase;">
                                    <i class="fas fa-cloud-upload-alt text-primary me-1"></i> Upload Hasil Desain
                                </span>
                                <form id="modalUploadDesignForm" action="" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="design_targets_id" id="modalUploadTargetId">
                                    <input type="hidden" name="user_admin_id" value="<?= session()->get('user_id') ?>">
                                    <input type="hidden" name="redirect_to" value="managerial">

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label mb-1 text-muted fw-bold"
                                                style="font-size: 10px; letter-spacing: 0.5px;">NAMA GAMBAR</label>
                                            <input type="text" name="design_name" class="form-control form-control-sm"
                                                placeholder="Contoh: Denah Lantai 1" required
                                                style="font-size: 12px; height: 38px; border-radius: 8px; border: 1.5px solid #cbd5e1;">
                                        </div>

                                        <div class="col-12" id="modalFileUploadCol">
                                            <label class="form-label mb-1 text-muted fw-bold"
                                                style="font-size: 10px; letter-spacing: 0.5px;">FILE DESAIN
                                                (OPSIONAL)</label>
                                            <div class="dropzone-area" id="dropzoneArea" style="padding: 15px;">
                                                <input type="file" id="modalUploadFile" name="design_files[]" multiple
                                                    accept=".pdf,.jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.webm,.mkv,.obj,.fbx,.glb,.gltf,.dwg,.rvt"
                                                    class="d-none">
                                                <div class="dropzone-content text-center py-2">
                                                    <div class="dropzone-icon-wrapper mb-2 mx-auto d-flex align-items-center justify-content-center"
                                                        style="width: 36px; height: 36px; font-size: 15px;">
                                                        <i class="fas fa-cloud-upload-alt text-primary"></i>
                                                    </div>
                                                    <h6 class="dropzone-title fw-bold mb-1"
                                                        style="font-size: 11px; color: #344054;">Tarik & lepaskan file
                                                        di sini</h6>
                                                    <p class="dropzone-subtitle text-muted mb-0"
                                                        style="font-size: 9.5px;">atau <span
                                                            class="text-primary fw-bold"
                                                            style="text-decoration: underline;">pilih dari
                                                            komputer</span></p>
                                                    <span class="d-block mt-1 text-muted"
                                                        style="font-size: 8px;">Format: PDF, Gambar, Video, atau File 3D
                                                        (Maks. 250MB)</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12" id="modal3dObjectCol">
                                            <label class="form-label mb-1 text-muted fw-bold"
                                                style="font-size: 10px; letter-spacing: 0.5px;">NAMA OBJEK 3D (STRING)
                                                (OPSIONAL)</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="3d_object_name" id="modal3dObjectNameInput"
                                                    class="form-control form-control-sm"
                                                    placeholder="Contoh: UnityObject_Building_Floor1"
                                                    style="font-size: 12px; height: 38px; border-radius: 8px 0 0 8px; border: 1.5px solid #cbd5e1; border-right: none;">
                                                <button class="btn btn-outline-primary btn-sm shadow-none" type="button"
                                                    id="btnGenerate3dKey"
                                                    style="border-radius: 0 8px 8px 0; border: 1.5px solid #cbd5e1; border-left: none; height: 38px; background-color: #f8fafc; color: var(--palette-primary, #ff5c5c); font-weight: bold; font-size: 11px; padding: 0 16px;">
                                                    <i class="fas fa-random"></i> Generate Key
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Pratinjau Berkas Terpilih (Lokasi inline di bawah form upload) -->
                                        <div class="col-12 mt-2 d-none" id="modalUploadPreviewContainer">
                                            <label class="form-label text-muted fw-bold mb-2"
                                                style="font-size: 9px; letter-spacing: 0.5px; text-transform: uppercase;">Berkas
                                                Terpilih</label>
                                            <div class="preview-files-list flex-column gap-2">
                                                <!-- Dinamis via JS -->
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <!-- List Hasil Desain -->
                            <div id="modalDesignResultsSection" class="pt-4 flex-grow-1 d-flex flex-column">
                                <span class="info-label d-block mb-3 text-muted fw-bold"
                                    style="font-size: 10px; letter-spacing: 0.8px; text-transform: uppercase;">
                                    <i class="fas fa-images text-primary me-1"></i> Hasil Desain Terunggah
                                </span>
                                <div class="design-results-list flex-grow-1" id="modalDesignResultsList"
                                    style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                                    <!-- AJAX content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer (hanya untuk status Sedang Diproses/Revisi) -->
            <div class="modal-footer border-top py-3 px-4" id="kanbanModalFooter"
                style="background: #f8fafc; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; display: none; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal"
                    style="height: 38px; border-radius: 8px; font-size: 12px; padding: 0 16px; background-color: #e2e8f0; color: #475569; border: none;">Batal</button>
                <button type="submit" form="modalUploadDesignForm" class="btn btn-primary fw-bold"
                    style="height: 38px; border-radius: 8px; font-size: 12px; padding: 0 16px; background-color: var(--palette-primary, #ff5c5c); border-color: var(--palette-primary, #ff5c5c);">
                    <i class="fas fa-cloud-upload-alt me-1"></i> Upload Sekarang
                </button>
            </div>

        </div>
    </div>
</div>