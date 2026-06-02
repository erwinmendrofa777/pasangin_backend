<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="d-flex justify-content-between align-items-center px-4 py-3"
                style="border-bottom: 1px solid #f0f4fa;">
                <div>
                    <h6 class="mb-0 fw-bold text-primary"
                        style="font-size:0.85rem; letter-spacing:0.4px; text-transform:uppercase;">
                        <i class="fas fa-images me-2"></i>Daftar Banner Iklan
                    </h6>
                </div>
                <div class="d-flex gap-3">
                    <div class="search-wrapper" style="width: 280px;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari banner...">
                    </div>
                    <?php if (can('banner_create')): ?>
                        <a href="<?= base_url('admin/banner/create') ?>"
                            class="btn btn-primary d-flex align-items-center gap-2 px-3"
                            style="border-radius: 12px; font-weight: 600;">
                            <i class="fas fa-plus"></i> Tambah Banner
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center" style="width: 60px;">No</th>
                                <th class="text-center">Pratinjau</th>
                                <th class="text-start">Judul Banner</th>
                                <th class="text-center">Target App</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Dibuat Pada</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banners as $key => $row): ?>
                                <tr class="text-center align-middle">
                                    <td>
                                        <span class="fw-semibold text-muted"
                                            style="font-size:0.82rem;"><?= $key + 1 ?></span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('uploads/banners/' . $row['image']) ?>" class="glightbox"
                                            data-gallery="banner-gallery" data-title="<?= esc($row['title']) ?>"
                                            data-description="Target: <?= $row['target_app'] == 'client' ? 'Client App' : 'Tukang App' ?>">
                                            <img src="<?= base_url('uploads/banners/' . $row['image']) ?>"
                                                class="banner-preview" data-toggle="tooltip"
                                                title="<?= esc($row['title']) ?>">
                                        </a>
                                    </td>
                                    <td class="text-start fw-semibold"><?= esc($row['title'] ?: '-') ?></td>
                                    <td>
                                        <?php if ($row['target_app'] == 'client'): ?>
                                            <span class="status-badge badge-client">Client App</span>
                                        <?php else: ?>
                                            <span class="status-badge badge-tukang">Tukang App</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-active">
                                            <i class="fas fa-check-circle"></i> Active
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted" style="font-size: 0.8rem;">
                                            <div class="fw-bold text-dark">
                                                <?= date('d M Y', strtotime($row['created_at'])) ?>
                                            </div>
                                            <div><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (can('banner_delete')): ?>
                                            <a href="<?= base_url('admin/banner/delete/' . $row['id']) ?>"
                                                class="btn btn-danger btn-sm ladda-button rounded-pill px-3"
                                                data-style="zoom-in"
                                                onclick="if(confirm('Yakin hapus banner ini?')) { Ladda.create(this).start(); return true; } return false;">
                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                            </a>
                                        <?php else: ?>
                                            <span class="badge badge-light"><i class="fas fa-lock"></i> No Access</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
