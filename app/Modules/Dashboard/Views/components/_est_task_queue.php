<!-- 3. ROW 1: TASK QUEUE -->
<div class="row">
  <!-- Antrean Tugas (Full Width) -->
  <div class="col-12">
    <div class="card premium-card">
      <div class="card-header">
        <h4><i class="fas fa-tasks"></i> Antrean Tugas Estimasi & RAB</h4>
      </div>
      <div class="card-body">
        <!-- Nav Tabs -->
        <ul class="nav premium-tabs-nav" id="rabTaskTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="main-queue-tab" data-toggle="tab" href="#main-queue" role="tab" aria-controls="main-queue" aria-selected="true">
              Antrean Utama (RAB) <span class="badge badge-primary ml-1" style="font-size: 0.7rem;"><?= count($estimatorStats['queues']['main']) ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="upcoming-queue-tab" data-toggle="tab" href="#upcoming-queue" role="tab" aria-controls="upcoming-queue" aria-selected="false">
              Pra-RAB / Upcoming <span class="badge badge-secondary ml-1" style="font-size: 0.7rem;"><?= count($estimatorStats['queues']['upcoming']) ?></span>
            </a>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="rabTaskTabsContent">
          <!-- Tab 1: Antrean Utama -->
          <div class="tab-pane fade show active" id="main-queue" role="tabpanel" aria-labelledby="main-queue-tab">
            <?php if (empty($estimatorStats['queues']['main'])): ?>
              <div class="empty-state">
                <i class="far fa-check-circle"></i>
                <p>Tidak ada antrean pembuatan RAB aktif saat ini.</p>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table premium-table">
                  <thead>
                    <tr>
                      <th>Proyek / Klien</th>
                      <th>Tipe</th>
                      <th>Status Proyek</th>
                      <th>Nilai Biaya</th>
                      <th>Target Progres</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($estimatorStats['queues']['main'] as $p): ?>
                      <tr>
                        <td>
                          <strong><?= esc($p['full_name'] ?: 'Klien Umum') ?></strong>
                          <div class="text-muted" style="font-size: 0.75rem;">ID: #<?= $p['id'] ?> | <?= date('d M Y', strtotime($p['created_at'])) ?></div>
                        </td>
                        <td>
                          <span class="status-badge badge-type-<?= $p['type'] ?>">
                            <i class="fas <?= $p['type'] === 'construction' ? 'fa-building' : 'fa-home' ?>"></i> <?= esc($p['type']) ?>
                          </span>
                        </td>
                        <td>
                          <span class="status-badge badge-status-rab">RAB</span>
                        </td>
                        <td>
                          <strong>Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></strong>
                          <div class="text-muted" style="font-size: 0.75rem;">
                            <?= $p['is_rab_locked'] === '1' ? '🔒 Terkunci' : '📝 Draf (' . $p['rab_count'] . ' item)' ?>
                          </div>
                        </td>
                        <td>
                          <div style="font-size: 0.8rem; font-weight: 700;">
                            <?= $p['target_count'] > 0 ? $p['total_bobot_target'] . '%' : 'Belum Dibuat' ?>
                          </div>
                        </td>
                        <td>
                          <a href="<?= base_url('admin/' . $p['type'] . '/detail/' . $p['id']) ?>" class="btn-premium-action btn-premium-action-sm">
                            <i class="fas fa-edit"></i> Detail & RAB
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>

          <!-- Tab 2: Upcoming / Pra-RAB -->
          <div class="tab-pane fade" id="upcoming-queue" role="tabpanel" aria-labelledby="upcoming-queue-tab">
            <?php if (empty($estimatorStats['queues']['upcoming'])): ?>
              <div class="empty-state">
                <i class="fas fa-calendar-alt"></i>
                <p>Tidak ada proyek dalam tahap Survei atau Desain.</p>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table premium-table">
                  <thead>
                    <tr>
                      <th>Proyek / Klien</th>
                      <th>Tipe</th>
                      <th>Status Proyek</th>
                      <th>Anggaran Awal</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($estimatorStats['queues']['upcoming'] as $p): ?>
                      <tr>
                        <td>
                          <strong><?= esc($p['full_name'] ?: 'Klien Umum') ?></strong>
                          <div class="text-muted" style="font-size: 0.75rem;">ID: #<?= $p['id'] ?> | <?= date('d M Y', strtotime($p['created_at'])) ?></div>
                        </td>
                        <td>
                          <span class="status-badge badge-type-<?= $p['type'] ?>">
                            <i class="fas <?= $p['type'] === 'construction' ? 'fa-building' : 'fa-home' ?>"></i> <?= esc($p['type']) ?>
                          </span>
                        </td>
                        <td>
                          <span class="status-badge badge-status-<?= strtolower($p['status']) ?>">
                            <?= esc($p['status']) ?>
                          </span>
                        </td>
                        <td>
                          Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?>
                        </td>
                        <td>
                          <a href="<?= base_url('admin/' . $p['type'] . '/detail/' . $p['id']) ?>" class="btn-premium-action btn-premium-action-sm">
                            <i class="fas fa-eye"></i> Detail
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
