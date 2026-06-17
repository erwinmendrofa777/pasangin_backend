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
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_header_card') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_idx_content') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\SyaratKetentuan\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>