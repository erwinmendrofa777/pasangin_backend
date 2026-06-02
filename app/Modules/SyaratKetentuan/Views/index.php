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
    /* Styling Tabs Premium */
    .nav-pills.custom-pills .nav-link {
        color: #495057;
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 20px;
        margin-right: 8px;
        transition: all 0.3s ease;
        background: #f8f9fa;
        border: 1px solid transparent;
    }

    .nav-pills.custom-pills .nav-link:hover {
        background: #e9ecef;
    }

    .nav-pills.custom-pills .nav-link.active {
        background: #6777EF;
        color: #fff;
        box-shadow: 0 4px 10px rgba(103, 119, 239, 0.3);
    }

    /* Table Styling */
    .table-custom {
        border-spacing: 0;
        border-collapse: separate;
    }

    .table-custom thead th {
        color: #0d6efd;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        border-bottom: 2px solid #dce8ff;
        border-top: none;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .table-custom thead tr {
        background: #f0f6ff;
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