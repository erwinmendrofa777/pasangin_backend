<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Log Aktivitas Admin
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Log Aktivitas Admin
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Admin\Views\activity_logs\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Admin\Views\activity_logs\components\_header_card') ?>
    <?= $this->include('App\Modules\Admin\Views\activity_logs\components\_table_card') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Admin\Views\activity_logs\components\_scripts') ?>
<?= $this->endSection() ?>