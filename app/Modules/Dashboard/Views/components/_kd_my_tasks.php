<!-- 3. DAFTAR TUGAS SAYA (Horizontal Scroll) -->
<div class="card border-0 mb-4 shadow-sm" style="border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.04);">
  <div class="card-header border-0 d-flex align-items-center p-3 p-md-4"
       style="background: #ffffff; border-radius: 20px 20px 0 0; border-bottom: 1px solid rgba(0,0,0,0.03) !important;">
    <div class="bg-primary bg-gradient text-white rounded-4 shadow-sm p-2 me-3 d-flex align-items-center justify-content-center"
         style="width: 48px; height: 48px;">
      <i class="fas fa-clipboard-check fa-lg"></i>
    </div>
    <div>
      <h5 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px; font-size: 1.2rem;">Daftar Tugas Saya</h5>
      <p class="text-muted mb-0" style="font-size: 0.85rem; letter-spacing: 0.2px;">Target perancangan desain pribadi Anda yang sedang aktif</p>
    </div>
  </div>

  <div class="card-body p-3 p-md-4" style="background: #f8fbff; border-radius: 0 0 20px 20px;">
    <?php if (!empty($designerTasks)): ?>
      <div class="d-flex flex-nowrap gap-4 pb-3 pt-2 px-1 task-scroll-container"
           style="overflow-x: auto; overflow-y: hidden; -webkit-overflow-scrolling: touch; scroll-behavior: smooth;">
        <?php foreach ($designerTasks as $task): ?>
          <?php
          $tStatus = $task['status'];
          $tColor = 'secondary';

          if ($tStatus === 'PENDING') {
              $tStatus = 'BELUM DIKERJAKAN';
              $tColor = 'secondary';
          } elseif ($tStatus === 'ON PROGRESS') {
              $tStatus = 'SEDANG DIPROSES';
              $tColor = 'info';
          } elseif ($tStatus === 'DONE') {
              $tStatus = 'SELESAI (TANPA FILE)';
              $tColor = 'success';
          }

          if ($task['total_designs'] > 0) {
              if ($task['approved_designs'] > 0) {
                  $tStatus = 'DISETUJUI';
                  $tColor = 'success';
              } elseif ($task['pending_designs'] > 0) {
                  $tStatus = 'TINJAUAN';
                  $tColor = 'primary';
              } else {
                  $tStatus = 'PERLU REVISI';
                  $tColor = 'danger';
              }
          }
          ?>
          <div style="min-width: 300px; width: 300px; flex: 0 0 auto;">
            <div class="card h-100 shadow-sm task-card">
              <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <?= date('d M', strtotime($task['created_at'])) ?>
                  </span>
                  <span class="badge text-white bg-<?= $tColor ?> rounded-pill px-2 py-1"
                        style="font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px;">
                    <?= esc($tStatus) ?>
                  </span>
                </div>

                <h5 class="fw-bold text-dark mb-4" style="font-size: 1.1rem; line-height: 1.4; white-space: normal;">
                  <?= esc($task['task_name']) ?>
                </h5>

                <div class="mb-4 flex-grow-1">
                  <div class="d-flex justify-content-between mb-2 pb-2 border-bottom border-light">
                    <div class="text-muted" style="font-size: 0.8rem;">Konsep</div>
                    <div class="fw-semibold text-dark text-end" style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                      <?= esc($task['design_concept'] ?? 'Proyek Khusus') ?>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div class="text-muted" style="font-size: 0.8rem;">Klien</div>
                    <div class="fw-semibold text-dark text-end" style="font-size: 0.85rem; max-width: 65%; white-space: normal;">
                      <?= esc($task['client_name'] ?? 'Klien Internal') ?>
                    </div>
                  </div>
                </div>

                <div class="mt-auto pt-1 border-top border-light d-flex justify-content-between align-items-center">
                  <?php
                  $targetStartDateStr = '';
                  $targetEndDateStr = '';
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
                      $targetStartDateStr = $tStart->format('d M');
                      $targetEndDateStr = $tEnd->format('d M Y');
                  }
                  ?>
                  <div>
                    <div class="text-dark fw-bold" style="font-size: 0.85rem;">
                      Hari <?= esc($task['start_week']) ?> &ndash; <?= esc($task['end_week']) ?>
                    </div>
                    <?php if ($targetStartDateStr): ?>
                      <div class="text-muted" style="font-size: 0.75rem; margin-top: 2px;">
                        <?= $targetStartDateStr ?> - <?= $targetEndDateStr ?>
                      </div>
                    <?php endif; ?>
                  </div>
                  <a href="<?= base_url('admin/design/show/' . $task['design_request_id']) ?>?target_id=<?= $task['id'] ?>&admin_id=<?= $task['user_admin_id'] ?>#design"
                     class="btn-minimal shadow-sm text-decoration-none stretched-link">
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="text-center py-5">
        <div class="text-muted mb-3" style="font-size: 3rem;">
          <i class="far fa-folder-open"></i>
        </div>
        <h6 class="fw-bold text-dark">Tidak Ada Tugas Desain Aktif</h6>
        <p class="text-muted" style="font-size: 0.85rem;">Anda sedang tidak memegang target pengerjaan desain saat ini.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
