<!-- Beban Kerja Staf -->
<div class="chart-card" style="height: 100%; padding: 20px 16px;">
  <div class="chart-title-wrapper">
    <h4><i class="fas fa-project-diagram"></i> Distribusi Tugas Kerja Tim</h4>
  </div>
  <div class="workload-chart-container">
    <canvas id="bebanKerjaChart"></canvas>
  </div>
  
  <!-- Keterangan Tertulis Distribusi Tugas Kerja Tim -->
  <div class="mt-3 pt-3" style="border-top: 1px dashed #e2e8f0;">
    <div class="text-center mb-2">
      <small class="text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.8px; text-transform: uppercase;">Detail Tugas Aktif Tim</small>
    </div>
    <div class="d-flex flex-wrap justify-content-center gap-2.5">
      <?php if (!empty($kadivStats['team_workload'])): ?>
        <?php foreach ($kadivStats['team_workload'] as $row): ?>
          <?php
          $photoName = $row['photo'] ?? '';
          $photoPath = FCPATH . 'uploads/admin/' . $photoName;
          $photoUrl = (!empty($photoName) && file_exists($photoPath)) 
              ? base_url('uploads/admin/' . $photoName) 
              : base_url('assets/img/avatar/avatar-1.png');
          ?>
          <div class="d-flex align-items-center gap-2 px-3 py-1.5 rounded-pill" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02); transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.borderColor='rgba(239, 68, 68, 0.4)'; this.style.transform='translateY(-1.5px)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.04)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.boxShadow='0 2px 5px rgba(0,0,0,0.02)';">
            <img src="<?= $photoUrl ?>" alt="<?= esc($row['full_name']) ?>" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover; border: 1px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
            <span class="fw-bold text-dark" style="font-size: 0.76rem;"><?= esc($row['full_name']) ?></span>
            <span class="badge rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 20px; height: 20px; padding: 0; font-size: 0.72rem; font-weight: 800; <?= (int)$row['active_tasks'] > 0 ? 'background-color: #ef4444; color: #ffffff;' : 'background-color: #f1f5f9; color: #64748b;' ?>">
              <?= (int)$row['active_tasks'] ?>
            </span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
