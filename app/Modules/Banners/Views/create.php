<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tambah Banner
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tambah Banner Baru
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('App\Modules\Banners\Views\components\_create_form') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <?= $this->include('App\Modules\Banners\Views\components\_create_scripts') ?>
<?= $this->endSection() ?>

