<!-- BACK BUTTON -->
<div class="mb-4">
    <a href="<?= base_url('admin/tips') ?>" class="btn btn-light btn-sm px-3 shadow-sm"
        style="border-radius: 10px; font-weight: 700; color: #6777ef;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-8">
        <div class="card detail-card bg-white">
            <!-- Hero Section -->
            <div class="detail-hero">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <div class="text-white">
                        <h5 class="mb-1 fw-bold" style="opacity: 0.9;">Artikel & Tips</h5>
                        <p class="small mb-0" style="opacity: 0.7;">Pratinjau konten aplikasi</p>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if ($tips['is_active'] == 1): ?>
                            <span class="badge-pill status-active"><i class="fas fa-check-circle"></i> Aktif</span>
                        <?php else: ?>
                            <span class="badge-pill status-inactive"><i class="fas fa-eye-slash"></i> Draft</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="detail-body">
                <!-- Cover Image & Meta -->
                <div class="d-flex align-items-end justify-content-between flex-wrap gap-3">
                    <div class="cover-wrapper">
                        <a href="<?= base_url('uploads/tips/' . $tips['image']) ?>" class="glightbox" data-title="<?= esc($tips['title']) ?>" data-description="Target: <?= esc($tips['target_app']) ?>">
                            <img src="<?= base_url('uploads/tips/' . $tips['image']) ?>" alt="<?= esc($tips['title']) ?>"
                                class="tips-cover-img" style="cursor:pointer;">
                        </a>
                    </div>
                    <div class="text-end pb-2">
                        <div class="text-muted small mb-1">Dipublikasikan pada:</div>
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                            <i class="far fa-calendar-alt me-1 text-primary"></i>
                            <?= date('d M Y, H:i', strtotime($tips['created_at'])) ?>
                        </div>
                    </div>
                </div>

                <div class="mt-5 px-1">
                    <!-- Target Badge -->
                    <div class="mb-3">
                        <?php if (strtolower($tips['target_app']) == 'tukang'): ?>
                            <span class="badge-pill bg-tukang"><i class="fas fa-hard-hat"></i> Target: Tukang</span>
                        <?php else: ?>
                            <span class="badge-pill bg-client"><i class="fas fa-user-tie"></i> Target: Client</span>
                        <?php endif; ?>
                    </div>

                    <!-- Title -->
                    <h2 class="fw-800 text-dark mb-4" style="font-size: 1.8rem; line-height: 1.3;">
                        <?= esc($tips['title']) ?>
                    </h2>

                    <hr class="my-4" style="border-top: 1px solid #f1f3f9;">

                    <!-- Rendered Content -->
                    <div class="rendered-content">
                        <?php
                        $json = $tips['content'];
                        $data = json_decode($json, true);

                        if (!$data || !isset($data['blocks'])) {
                            echo '<p class="text-muted italic">' . esc($json) . '</p>';
                        } else {
                            foreach ($data['blocks'] as $block) {
                                switch ($block['type']) {
                                    case 'header':
                                        $level = $block['data']['level'] ?? 2;
                                        echo "<h{$level}>" . ($block['data']['text'] ?? '') . "</h{$level}>";
                                        break;

                                    case 'paragraph':
                                        echo "<p>" . ($block['data']['text'] ?? '') . "</p>";
                                        break;

                                    case 'list':
                                        $tag = ($block['data']['style'] == 'ordered') ? 'ol' : 'ul';
                                        echo "<{$tag}>";
                                        foreach ($block['data']['items'] as $item) {
                                            echo "<li>{$item}</li>";
                                        }
                                        echo "</{$tag}>";
                                        break;

                                    case 'image':
                                        $url = $block['data']['file']['url'] ?? '';
                                        $caption = $block['data']['caption'] ?? '';
                                        $withBorder = ($block['data']['withBorder'] ?? false) ? 'border' : '';
                                        $withBackground = ($block['data']['withBackground'] ?? false) ? 'bg-light p-3' : '';
                                        $stretched = ($block['data']['stretched'] ?? false) ? 'w-100' : 'img-fluid';

                                        echo "<figure class='{$withBackground}'>";
                                        echo "<img src='{$url}' class='{$stretched} {$withBorder} rounded shadow-sm' alt='{$caption}'>";
                                        if ($caption) {
                                            echo "<figcaption>{$caption}</figcaption>";
                                        }
                                        echo "</figure>";
                                        break;

                                    case 'delimiter':
                                        echo "<hr>";
                                        break;

                                    case 'quote':
                                        $text = $block['data']['text'] ?? '';
                                        $caption = $block['data']['caption'] ?? '';
                                        $alignment = $block['data']['alignment'] ?? 'left';
                                        echo "<blockquote style='text-align:{$alignment}'>";
                                        echo "<p class='mb-2'>\"{$text}\"</p>";
                                        if ($caption) {
                                            echo "<footer class='small opacity-75'>— {$caption}</footer>";
                                        }
                                        echo "</blockquote>";
                                        break;
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <!-- ACTIONS CARD -->
        <div class="card side-widget bg-white mb-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                <h6 class="mb-0 fw-800 text-primary"
                    style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                    <i class="fas fa-cog me-2"></i>Pengaturan Tips
                </h6>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="d-grid gap-3">
                    <!-- Toggle Status Button -->
                    <form action="<?= base_url('admin/tips/update-status/' . $tips['id']) ?>" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit"
                            class="btn btn-<?= $tips['is_active'] ? 'warning' : 'success' ?> w-100 py-3 fw-bold shadow-sm"
                            style="border-radius: 15px;">
                            <?php if ($tips['is_active']): ?>
                                <i class="fas fa-eye-slash me-2"></i> Sembunyikan (Draft)
                            <?php else: ?>
                                <i class="fas fa-check-circle me-2"></i> Aktifkan Sekarang
                            <?php endif; ?>
                        </button>
                    </form>

                    <a href="<?= base_url('admin/tips/edit/' . $tips['id']) ?>"
                        class="btn btn-primary py-3 fw-bold shadow-sm" style="border-radius: 15px;">
                        <i class="fas fa-edit me-2"></i> Edit Konten
                    </a>

                    <?php if (can('tips_delete')): ?>
                        <a href="<?= base_url('admin/tips/delete/' . $tips['id']) ?>"
                            class="btn btn-outline-danger py-3 fw-bold" style="border-radius: 15px;"
                            onclick="return confirm('Yakin ingin menghapus tips ini?')">
                            <i class="fas fa-trash-alt me-2"></i> Hapus Permanen
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- STATS / SUMMARY -->
        <div class="card side-widget bg-white">
            <div class="card-body p-4">
                <div class="small text-muted mb-3 uppercase fw-bold" style="letter-spacing: 1px;">Ringkasan Data</div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">ID Tips</span>
                    <span class="fw-bold text-dark">#<?= $tips['id'] ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Target App</span>
                    <span class="fw-bold text-dark"><?= esc($tips['target_app']) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="fw-bold text-success"><?= $tips['is_active'] ? 'Published' : 'Draft' ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
