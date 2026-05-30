<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Detail Tips - <?= esc($tips['title']) ?>
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Detail Tips & Tricks
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* ===== HERO HEADER ===== */
    .detail-hero {
        background: linear-gradient(135deg, #6777ef 0%, #4d5fd1 100%);
        border-radius: 20px 20px 0 0;
        padding: 35px 35px 85px;
        position: relative;
        overflow: hidden;
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.07);
        border-radius: 50%;
    }

    .detail-hero::after {
        content: '';
        position: absolute;
        bottom: -80px;
        left: -40px;
        width: 280px;
        height: 280px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    /* ===== IMAGE PREVIEW (Main Cover) ===== */
    .cover-wrapper {
        position: relative;
        display: inline-block;
        margin-top: -75px;
        margin-left: 35px;
        z-index: 2;
    }

    .tips-cover-img {
        width: 280px;
        height: 160px;
        object-fit: cover;
        border-radius: 18px;
        border: 5px solid #fff;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        background: #e9ecef;
    }

    /* ===== CARDS ===== */
    .detail-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
        overflow: hidden;
    }

    .detail-body {
        padding: 0 35px 40px;
    }

    /* ===== BADGES ===== */
    .badge-pill {
        border-radius: 50px;
        padding: 6px 16px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .bg-tukang {
        background: #fff7ed;
        color: #9a3412;
    }

    .bg-client {
        background: #eff6ff;
        color: #1e40af;
    }

    .status-active {
        background: #e8fdf0;
        color: #0a6640;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* ===== CONTENT RENDERING ===== */
    .rendered-content {
        color: #4a5568;
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .rendered-content h2,
    .rendered-content h3,
    .rendered-content h4 {
        color: #2d3748;
        font-weight: 800;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .rendered-content p {
        margin-bottom: 1.25rem;
    }

    .rendered-content ul,
    .rendered-content ol {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .rendered-content li {
        margin-bottom: 0.5rem;
    }

    .rendered-content figure {
        margin: 2.5rem 0;
        text-align: center;
    }

    .rendered-content figure img {
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        max-width: 100%;
    }

    .rendered-content figcaption {
        margin-top: 0.75rem;
        font-size: 0.85rem;
        color: #8e94a9;
        font-style: italic;
    }

    .rendered-content blockquote {
        background: #f8f9ff;
        border-left: 4px solid #6777ef;
        padding: 20px 25px;
        border-radius: 0 12px 12px 0;
        margin: 2rem 0;
        font-style: italic;
        color: #4d5fd1;
    }

    .rendered-content hr {
        border: 0;
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 3rem 0;
    }

    /* Side Widget */
    .side-widget {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(103, 119, 239, 0.08);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

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

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    <?php if (session()->getFlashdata('success')): ?>
        iziToast.success({ timeout: 5000, title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter' });
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        iziToast.error({ timeout: 5000, title: 'Gagal', message: '<?= session()->getFlashdata('error') ?>', position: 'topCenter' });
    <?php endif; ?>

    $(document).ready(function () {
        if (window.GLightbox) {
            GLightbox({ selector: '.glightbox' });
        }
    });
</script>
<?= $this->endSection() ?>