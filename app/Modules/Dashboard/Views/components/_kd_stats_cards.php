<style>
  .stat-card-link {
    text-decoration: none !important;
    display: block;
    color: inherit;
    transition: transform 0.25s ease;
  }
  .stat-card-link:hover {
    transform: translateY(-4px);
  }
  .stat-card-clickable {
    cursor: pointer;
  }
  .icon-warning-task {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.28);
  }
  .icon-unassigned-zero {
    background: linear-gradient(135deg, #94a3b8, #64748b);
    box-shadow: 0 8px 20px rgba(148, 163, 184, 0.28);
  }
  .icon-awaiting-review {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 8px 20px rgba(245, 158, 11, 0.28);
  }
  .icon-awaiting-zero {
    background: linear-gradient(135deg, #94a3b8, #64748b);
    box-shadow: 0 8px 20px rgba(148, 163, 184, 0.28);
  }

  /* Premium theme hover transitions */
  .stat-card {
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease !important;
  }
  .stat-card-warning-hover:hover {
    box-shadow: 0 12px 30px rgba(239, 68, 68, 0.12) !important;
    border-color: rgba(239, 68, 68, 0.3) !important;
  }
  .stat-card-awaiting-hover:hover {
    box-shadow: 0 12px 30px rgba(245, 158, 11, 0.12) !important;
    border-color: rgba(245, 158, 11, 0.3) !important;
  }
  .stat-card-active-hover:hover {
    box-shadow: 0 12px 30px rgba(229, 57, 53, 0.12) !important;
    border-color: rgba(229, 57, 53, 0.3) !important;
  }
  .stat-card-neutral-hover:hover {
    box-shadow: 0 12px 30px rgba(100, 116, 139, 0.1) !important;
    border-color: rgba(100, 116, 139, 0.25) !important;
  }
</style>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
  
  <!-- Card 1: Belum Ditugaskan -->
  <?php 
  $unassignedCount = (int)($kadivStats['overview']['unassigned_tasks'] ?? 0);
  $unassignedIconClass = ($unassignedCount > 0) ? 'icon-warning-task' : 'icon-unassigned-zero';
  $unassignedBorderColor = ($unassignedCount > 0) ? '#ef4444' : '#cbd5e1';
  $unassignedHoverClass = ($unassignedCount > 0) ? 'stat-card-warning-hover' : 'stat-card-neutral-hover';
  ?>
  <a href="<?= base_url('admin/design/managerial') ?>" class="stat-card-link">
    <div class="stat-card stat-clickable <?= $unassignedHoverClass ?>" style="height: 100%; border-left: 4px solid <?= $unassignedBorderColor ?>;">
      <div class="stat-info">
        <h3>Belum Ditugaskan</h3>
        <div class="stat-value" style="<?= ($unassignedCount > 0) ? 'color: #ef4444;' : '' ?>"><?= $unassignedCount ?></div>
      </div>
      <div class="stat-icon <?= $unassignedIconClass ?>">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
    </div>
  </a>

  <!-- Card 2: Butuh Persetujuan -->
  <?php 
  $awaitingReviewsCount = (int)($kadivStats['overview']['awaiting_reviews_count'] ?? 0);
  $awaitingIconClass = ($awaitingReviewsCount > 0) ? 'icon-awaiting-review' : 'icon-awaiting-zero';
  $awaitingBorderColor = ($awaitingReviewsCount > 0) ? '#f59e0b' : '#cbd5e1';
  $awaitingHoverClass = ($awaitingReviewsCount > 0) ? 'stat-card-awaiting-hover' : 'stat-card-neutral-hover';
  ?>
  <a href="#awaiting-review-section" class="stat-card-link">
    <div class="stat-card stat-clickable <?= $awaitingHoverClass ?>" style="height: 100%; border-left: 4px solid <?= $awaitingBorderColor ?>;">
      <div class="stat-info">
        <h3>Butuh Persetujuan</h3>
        <div class="stat-value" style="<?= ($awaitingReviewsCount > 0) ? 'color: #d97706;' : '' ?>"><?= $awaitingReviewsCount ?></div>
      </div>
      <div class="stat-icon <?= $awaitingIconClass ?>">
        <i class="fas fa-hourglass-half"></i>
      </div>
    </div>
  </a>

  <!-- Card 3: Proyek Desain Aktif -->
  <a href="<?= base_url('admin/design/managerial') ?>" class="stat-card-link">
    <div class="stat-card stat-clickable stat-card-active-hover" style="height: 100%; border-left: 4px solid #e53935;">
      <div class="stat-info">
        <h3>Proyek Desain Aktif</h3>
        <div class="stat-value"><?= (int)($kadivStats['overview']['active_projects_breakdown']['design'] ?? 0) ?></div>
      </div>
      <div class="stat-icon icon-active-proj">
        <i class="fas fa-palette"></i>
      </div>
    </div>
  </a>

</div>
