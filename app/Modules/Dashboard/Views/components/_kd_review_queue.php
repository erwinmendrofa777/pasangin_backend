<div class="premium-card" id="awaiting-review-section" style="height: 100%; padding: 20px 16px;">
  <div class="premium-card-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h4 class="mb-0"><i class="fas fa-hourglass-half text-warning"></i> Perlu Persetujuan</h4>
    <span class="badge bg-warning text-dark fw-bold px-2.5 py-1.5" style="border-radius: 20px; font-size: 0.72rem;">
      <?= count($kadivStats['awaiting_reviews'] ?? []) ?> Berkas
    </span>
  </div>

  <div class="d-flex flex-column gap-2 review-scroll-container"
    style="flex: 1; min-height: 0; overflow-y: auto; padding-right: 6px;">
    <?php if (!empty($kadivStats['awaiting_reviews'])): ?>
      <?php foreach ($kadivStats['awaiting_reviews'] as $r): ?>
        <?php
        $fileExt = strtolower(pathinfo($r['file'], PATHINFO_EXTENSION));
        $isPdf = ($fileExt === 'pdf');
        $isVideo = in_array($fileExt, ['mp4', 'mov', 'avi', 'webm', 'mkv']);
        $isImg = in_array($fileExt, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
        $fileUrl = base_url('uploads/design_results/' . $r['file']);

        // Fix broken avatar check by checking file existence on disk
        $photoName = $r['designer_photo'] ?? '';
        $photoPath = FCPATH . 'uploads/admin/' . $photoName;
        $designerPhoto = (!empty($photoName) && file_exists($photoPath))
          ? base_url('uploads/admin/' . $photoName)
          : base_url('assets/img/avatar/avatar-1.png');
        ?>
        <?php
        if ($isImg) {
          $fileIcon = 'far fa-image';
          $previewTitle = 'Lihat Gambar';
        } elseif ($isPdf) {
          $fileIcon = 'far fa-file-pdf text-danger';
          $previewTitle = 'Buka PDF';
        } elseif ($isVideo) {
          $fileIcon = 'far fa-file-video text-warning';
          $previewTitle = 'Putar Video';
        } else {
          $fileIcon = 'fas fa-download';
          $previewTitle = 'Unduh Berkas';
        }
        ?>
        <div class="p-3 rounded-3 d-flex flex-column gap-2.5 review-queue-card">

          <!-- Top row: Designer info & Action/Revision badges -->
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
              <img src="<?= $designerPhoto ?>" alt="<?= esc($r['designer_name'] ?? 'Desainer') ?>" class="rounded-circle"
                style="width: 32px; height: 32px; object-fit: cover; border: 1.5px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.08);">
              <div>
                <div class="fw-bold text-dark" style="font-size: 0.82rem; line-height: 1.2;">
                  <?= esc($r['designer_name'] ?? 'Sistem') ?>
                </div>
                <div class="text-muted" style="font-size: 0.68rem;"><?= date('d M Y, H:i', strtotime($r['created_at'])) ?>
                </div>
              </div>
            </div>

            <div class="d-flex align-items-center gap-2">
              <!-- Compact Circular Preview Icon Button -->
              <a href="<?= $fileUrl ?>" class="btn-preview-circle" target="_blank" title="<?= $previewTitle ?>">
                <i class="<?= $fileIcon ?>"></i>
              </a>
              <span class="badge bg-warning-subtle text-warning border border-warning-subtle fw-bold px-2 py-0.5"
                style="font-size: 0.65rem; background-color: #fffbeb; color: #d97706; border-color: #fef3c7; border-radius: 6px;">
                Rev. <?= $r['revision_number'] ?>
              </span>
            </div>
          </div>

          <!-- Middle row: Concept & Task name -->
          <div style="border-top: 1px dashed #f1f5f9; padding-top: 8px;">
            <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem; line-height: 1.3;"
              title="<?= esc($r['design_concept'] ?? 'Proyek Khusus') ?> — <?= esc($r['task_name']) ?>">
              <?= esc($r['design_concept'] ?? 'Proyek Khusus') ?> — <span
                class="text-primary"><?= esc($r['task_name']) ?></span>
            </div>
            <div class="text-muted mt-1" style="font-size: 0.72rem;">
              <i class="far fa-user me-1"></i>Klien: <strong
                class="text-secondary"><?= esc($r['client_name'] ?? 'Internal') ?></strong>
            </div>
          </div>

          <!-- Bottom row: Sleek Full Width Action Button -->
          <div class="d-flex gap-2 mt-1">
            <a href="<?= base_url('admin/design/show/' . $r['design_request_id'] . '#target') ?>"
              class="btn btn-sm btn-review-primary w-100 d-flex align-items-center justify-content-center gap-1.5"
              style="height: 30px; font-size: 0.75rem; font-weight: 700; border-radius: 6px;">
              <i class="fas fa-check-double"></i> <span>Periksa Dokumen</span>
            </a>
          </div>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-center py-5">
        <div class="text-muted mb-2" style="font-size: 2.2rem;">
          <i class="far fa-check-circle text-success"></i>
        </div>
        <h6 class="fw-bold text-dark" style="font-size: 0.9rem;">Antrean Bersih</h6>
        <p class="text-muted mb-0" style="font-size: 0.75rem;">Semua draf selesai ditinjau.</p>
      </div>
    <?php endif; ?>
  </div>
</div>