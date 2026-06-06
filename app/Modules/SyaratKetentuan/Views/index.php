<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Kelola Syarat & Ketentuan <?= $this->endSection() ?>
<?= $this->section('page_title') ?> Kelola Syarat & Ketentuan <?= $this->endSection() ?>

<?php
/**
 * Extract plain text preview from Editor.js JSON string.
 * Returns up to $maxLen characters from the first text-bearing block.
 */
if (!function_exists('editorPreview')) {
    function editorPreview(?string $json, int $maxLen = 150): string {
        if (!$json) return '-';
        $data = json_decode($json, true);
        if (!$data || !isset($data['blocks'])) return esc(mb_strimwidth($json, 0, $maxLen, '...'));
        $texts = [];
        foreach ($data['blocks'] as $block) {
            $type = $block['type'] ?? '';
            if (in_array($type, ['paragraph', 'header'])) {
                $texts[] = strip_tags($block['data']['text'] ?? '');
            } elseif ($type === 'list') {
                foreach (($block['data']['items'] ?? []) as $item) {
                    $texts[] = '• ' . strip_tags($item);
                }
            }
        }
        $preview = implode(' ', $texts);
        return esc(mb_strimwidth($preview, 0, $maxLen, '...'));
    }
}
?>

<?= $this->section('style') ?>
<style>
    /* Force card header title to Coral Red */
    .card .card-header h4,
    .card-header h4 {
        color: #ff5c5c !important;
    }

    /* Styling Tabs Premium */
    .nav-pills.custom-pills .nav-link {
        color: var(--palette-primary) !important;
        font-weight: 700;
        border-radius: 50px;
        padding: 7px 20px;
        margin-right: 8px;
        transition: all 0.25s ease;
        background: #fff5f5;
        border: 1px solid #ffd3d3 !important;
        font-size: 0.88rem;
    }

    .nav-pills.custom-pills .nav-link:hover {
        background: #ffe0e0;
        border-color: var(--palette-primary) !important;
        color: var(--palette-primary) !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.15);
    }

    .nav-pills.custom-pills .nav-link.active {
        background: var(--palette-primary);
        border-color: var(--palette-primary) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(255, 92, 92, 0.35);
    }

    /* Table Styling */
    .table-custom {
        border-spacing: 0;
        border-collapse: separate;
    }

    .table-custom thead th {
        color: var(--palette-primary);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #ffdddd;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .table-custom thead tr {
        background: #fff5f5;
    }

    .table-custom tbody tr {
        transition: all 0.3s;
    }

    .table-custom tbody tr:hover {
        background-color: #f8f9fc;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .table-custom td {
        vertical-align: middle;
        padding: 15px 12px;
        border-bottom: 1px solid #e3e6f0;
    }

    .desc-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_idx_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>