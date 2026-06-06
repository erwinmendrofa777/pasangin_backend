<!-- ===== PAGE HEADER ===== -->
<div class="page-header-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h4 class="text-white"><i class="fas fa-lightbulb me-2" style="opacity:0.85;"></i>Tips & Tricks</h4>
            <p>Kelola konten tips dan artikel yang ditampilkan di aplikasi</p>
        </div>
        <?php if (can('tips_create')): ?>
            <a href="<?= base_url('admin/tips/create') ?>" class="btn-add-tips">
                <i class="fas fa-plus"></i> Tambah Tips Baru
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- ===== STAT CARDS ===== -->
<div class="row g-3 mb-4">
    <?php
    $total = count($tips);
    $aktif = count(array_filter($tips, fn($t) => $t['is_active'] == 1));
    $client = count(array_filter($tips, fn($t) => strtolower($t['target_app']) == 'client'));
    $tukang = count(array_filter($tips, fn($t) => strtolower($t['target_app']) == 'tukang'));
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#eef0fd;">
                <i class="fas fa-layer-group" style="color:var(--palette-primary);"></i>
            </div>
            <div>
                <div class="stat-val"><?= $total ?></div>
                <div class="stat-lbl">Total Tips</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#e8fdf0;">
                <i class="fas fa-check-circle" style="color:#0a6640;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $aktif ?></div>
                <div class="stat-lbl">Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#eff6ff;">
                <i class="fas fa-user" style="color:#1e40af;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $client ?></div>
                <div class="stat-lbl">Untuk Client</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-mini-card bg-white">
            <div class="stat-mini-icon" style="background:#fff7ed;">
                <i class="fas fa-tools" style="color:#9a3412;"></i>
            </div>
            <div>
                <div class="stat-val"><?= $tukang ?></div>
                <div class="stat-lbl">Untuk Tukang</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABLE CARD ===== -->
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h6 class="mb-0 fw-800"
            style="color:var(--palette-primary); font-size:0.82rem; letter-spacing:0.5px; text-transform:uppercase;">
            <i class="fas fa-list-ul me-2"></i>Daftar Tips & Tricks
        </h6>
        <div class="search-wrapper" style="width:260px;">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="form-control" id="searchInput" placeholder="Cari judul tips...">
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($tips)): ?>
            <div class="empty-state">
                <i class="fas fa-lightbulb d-block"></i>
                <p class="fw-bold mb-1" style="color:#4a5568;">Belum ada data tips</p>
                <p class="small mb-0">Mulai tambah konten tips & artikel untuk pengguna aplikasi.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="table-1">
                    <thead>
                        <tr class="text-center">
                            <th style="width:50px;">No</th>
                            <th style="width:130px;">Visual</th>
                            <th class="text-start">Judul Tips</th>
                            <th>Target</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tips as $key => $row): ?>
                            <tr class="text-center">
                                <td class="fw-bold text-muted" style="font-size:0.8rem;"><?= $key + 1 ?></td>
                                <td>
                                    <a href="<?= base_url('uploads/tips/' . $row['image']) ?>" class="glightbox"
                                        data-gallery="tips-gallery" data-title="<?= esc($row['title']) ?>"
                                        data-description="Target: <?= esc($row['target_app']) ?>">
                                        <img src="<?= base_url('uploads/tips/' . $row['image']) ?>" class="tips-img"
                                            alt="<?= esc($row['title']) ?>">
                                    </a>
                                </td>
                                <td class="text-start">
                                    <div class="tips-title"><?= esc($row['title']) ?></div>
                                    <div class="tips-excerpt">
                                        <?php
                                        // Ambil teks dari JSON Editor.js jika ada, fallback ke strip_tags
                                        $contentRaw = $row['content'] ?? '';
                                        $decoded = json_decode($contentRaw, true);
                                        if ($decoded && isset($decoded['blocks'])) {
                                            $firstText = '';
                                            foreach ($decoded['blocks'] as $block) {
                                                if (isset($block['data']['text']) && !empty($block['data']['text'])) {
                                                    $firstText = strip_tags($block['data']['text']);
                                                    break;
                                                }
                                            }
                                            echo esc($firstText ?: '—');
                                        } else {
                                            echo esc(strip_tags($contentRaw) ?: '—');
                                        }
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (strtolower($row['target_app']) == 'tukang'): ?>
                                        <span class="badge-pill bg-tukang"><i class="fas fa-tools"></i> Tukang</span>
                                    <?php else: ?>
                                        <span class="badge-pill bg-client"><i class="fas fa-user"></i> Client</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:0.8rem;">
                                        <?= date('d M Y', strtotime($row['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['is_active'] == 1): ?>
                                        <span class="badge-pill status-active"><i class="fas fa-check-circle"
                                                style="font-size:0.7rem;"></i> Aktif</span>
                                    <?php else: ?>
                                        <span class="badge-pill status-inactive"><i class="fas fa-eye-slash"
                                                style="font-size:0.7rem;"></i> Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <?php if (can('tips')): ?>
                                            <a href="<?= base_url('admin/tips/detail/' . $row['id']) ?>"
                                                class="btn-action btn-action-detail" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (can('tips_create') || can('tips_update')): ?>
                                            <a href="<?= base_url('admin/tips/edit/' . $row['id']) ?>"
                                                class="btn-action btn-action-edit" title="Edit Konten">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (can('tips_delete')): ?>
                                            <a href="<?= base_url('admin/tips/delete/' . $row['id']) ?>"
                                                class="btn-action btn-action-delete" title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus tips ini?')">
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
        <?php endif; ?>
    </div>
</div>