<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Estimasi Harga
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_header') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_concepts') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_modals') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\PriceEstimate\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>