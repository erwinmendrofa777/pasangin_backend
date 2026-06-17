<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Admin
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Admin
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Admin\Views\admin\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Admin\Views\admin\components\_header_card') ?>
    <?= $this->include('App\Modules\Admin\Views\admin\components\_table_card') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Admin\Views\admin\components\_scripts') ?>
<?= $this->endSection() ?>