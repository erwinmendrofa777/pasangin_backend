<!-- ===== TABLE CARD: Daftar Notifikasi ===== -->
<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm table-card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 py-3 px-4">
                <div class="search-wrapper">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchInput"
                        placeholder="Ketik untuk mencari notifikasi">
                </div>
                <?php if (can('notification_create')): ?>
                    <a href="<?= base_url('admin/notification/create') ?>" class="btn btn-primary px-4 py-2 fw-bold"
                        style="border-radius: 12px; box-shadow: 0 4px 12px rgba(103, 119, 239, 0.35); height: 44px; display: flex; align-items: center;">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Notifikasi Baru
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-1">
                        <thead>
                            <tr>
                                <th>Waktu Kirim</th>
                                <th>Target</th>
                                <th>Konten Notifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notifications as $n): ?>
                                <?php
                                $targetClass = 'badge-all';
                                if ($n['target_type'] == 'client')   $targetClass = 'badge-client';
                                if ($n['target_type'] == 'tukang')   $targetClass = 'badge-tukang';
                                if ($n['target_type'] == 'supplier') $targetClass = 'badge-supplier';
                                ?>
                                <tr>
                                    <td>
                                        <div class="notif-time">
                                            <i class="far fa-clock me-1"></i>
                                            <?= date('d M Y', strtotime($n['created_at'])) ?>
                                            <?= date('H:i', strtotime($n['created_at'])) ?> WIB
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <!-- Badge Role -->
                                            <span class="target-badge <?= $targetClass ?> mb-1">
                                                <i class="fas <?= $n['target_type'] == 'client' ? 'fa-user' : ($n['target_type'] == 'tukang' ? 'fa-tools' : 'fa-store') ?> me-1"></i>
                                                <?= ucfirst($n['target_type']) ?>
                                            </span>

                                            <!-- Badge Scope (Spesifik / Semua) -->
                                            <?php if (!empty($n['target_id'])): ?>
                                                <span class="badge badge-dark"
                                                    style="font-size: 0.65rem; padding: 4px 8px; border-radius: 6px; letter-spacing: 0.3px;">
                                                    <i class="fas fa-user-tag me-1"></i> ID: <?= $n['target_id'] ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-light border text-muted"
                                                    style="font-size: 0.65rem; padding: 4px 8px; border-radius: 6px; letter-spacing: 0.3px;">
                                                    <i class="fas fa-globe me-1"></i> Semua User
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-start gap-3">
                                            <?php if (!empty($n['image_url'])): ?>
                                                <img src="<?= esc($n['image_url']) ?>" alt="Banner" class="rounded-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light p-2 rounded-3 text-primary d-flex justify-content-center align-items-center"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-bell"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <span class="notif-title"><?= esc($n['title']) ?></span>
                                                <p class="notif-msg mb-0"><?= esc($n['message']) ?></p>
                                            </div>
                                        </div>
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
