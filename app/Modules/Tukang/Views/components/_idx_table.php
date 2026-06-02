<div class="card table-card">
    <!-- Header with Search and Create Button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4 table-card-header"
        style="border-bottom: 1px solid #f0f4fa; background: #fff; gap: 16px;">
        <h6 class="mb-0 fw-bold text-primary d-flex align-items-center"
            style="font-size:0.9rem; letter-spacing:0.4px; text-transform:uppercase;">
            <i class="fas fa-tools me-2"></i>Daftar Mitra Tukang
        </h6>
        <div class="d-flex flex-column flex-sm-row gap-2 header-actions">
            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email...">
            </div>
            <?php if (can('tukang_create')): ?>
                <a href="<?= base_url('admin/tukang/create') ?>"
                    class="btn btn-primary d-flex align-items-center justify-content-center text-nowrap"
                    style="border-radius: 12px; font-size: 0.82rem; padding: 8px 14px;">
                    <i class="fas fa-plus me-1"></i> Tambah Mitra
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="table-1">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Foto</th>
                        <th class="text-start" style="width: 30%;">Nama & Spesialisasi</th>
                        <th>Email & Telepon</th>
                        <th>Status</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tukang as $key => $row): ?>
                        <tr class="text-center">
                            <td class="fw-bold text-muted"><?= $key + 1 ?></td>
                            <td>
                                <?php
                                $photoSrc = !empty($row['profile_photo']) ? base_url('uploads/tukang/' . $row['profile_photo']) : base_url('uploads/tukang/default.jpg');
                                ?>
                                <a href="<?= $photoSrc ?>" class="glightbox" data-gallery="tukang-<?= $row['id'] ?>"
                                    data-title="<?= esc($row['name']) ?>"
                                    data-description="Nama: <?= esc($row['name']) ?> &lt;br&gt; Spesialisasi: <?= esc($row['specialization'] ?: 'Umum') ?> &lt;br&gt; Email: <?= esc($row['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($row['phone'] ?: '-') ?> &lt;br&gt; Rating: <?= esc($row['rata_rata_rating'] ?: '0.0') ?> / 5.0">
                                    <img src="<?= $photoSrc ?>" class="tukang-avatar" alt="<?= esc($row['name']) ?>"
                                        data-toggle="tooltip" title="Klik untuk memperbesar">
                                </a>
                            </td>
                            <td class="text-start">
                                <div class="fw-bold text-dark"><?= esc($row['name']) ?></div>
                                <div class="text-muted small"><i class="fas fa-briefcase me-1"></i>
                                    <?= esc($row['specialization'] ?: 'Umum') ?></div>
                            </td>
                            <td class="text-start">
                                <div class="small text-dark fw-semibold"><i class="fas fa-envelope me-1 opacity-50"></i>
                                    <?= esc($row['email'] ?: '-') ?></div>
                                <div class="small text-muted"><i class="fas fa-phone me-1 opacity-50"></i>
                                    <?= esc($row['phone'] ?: '-') ?></div>
                            </td>
                            <td>
                                <?php
                                $status = $row['status'];
                                $statusClass = 'status-berkas';
                                $icon = 'fas fa-file-alt';

                                switch ($status) {
                                    case 'Berkas Diproses':
                                        $statusClass = 'status-berkas';
                                        $icon = 'fas fa-file-medical';
                                        break;
                                    case 'Ditolak':
                                        $statusClass = 'status-ditolak';
                                        $icon = 'fas fa-times-circle';
                                        break;
                                    case 'Proses Test':
                                        $statusClass = 'status-test';
                                        $icon = 'fas fa-vial';
                                        break;
                                    case 'Proses Aktivasi':
                                        $statusClass = 'status-aktivasi';
                                        $icon = 'fas fa-user-check';
                                        break;
                                    case 'Siap Kerja':
                                        $statusClass = 'status-siap';
                                        $icon = 'fas fa-check-double';
                                        break;
                                }
                                ?>
                                <span class="status-badge <?= $statusClass ?>">
                                    <i class="<?= $icon ?> me-1"></i> <?= $status ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column align-items-center">
                                    <div class="fw-bold text-primary mb-1">
                                        <i class="fas fa-star text-warning me-1"></i><?= $row['rata_rata_rating'] ?>
                                    </div>
                                    <div style="font-size: 0.65rem;" class="text-muted text-nowrap">
                                        S: <?= $row['skill_score'] ?> | B: <?= $row['behavior_score'] ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <?php if (can('tukang')): ?>
                                        <a href="<?= base_url('admin/tukang/detail/' . $row['id']) ?>"
                                            class="btn-action btn-action-detail" data-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (can('tukang_delete')): ?>
                                        <a href="<?= base_url('admin/tukang/delete/' . $row['id']) ?>"
                                            class="btn-action btn-action-delete ladda-button" data-style="zoom-in"
                                            onclick="return confirm('Hapus data mitra tukang ini?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
