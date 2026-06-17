<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Role
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Role
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Admin\Views\roles\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Admin\Views\roles\components\_header_card') ?>
    <?= $this->include('App\Modules\Admin\Views\roles\components\_table_card') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Admin\Views\roles\components\_scripts') ?>
<?= $this->endSection() ?>