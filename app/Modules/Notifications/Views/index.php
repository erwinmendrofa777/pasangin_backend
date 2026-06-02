<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Notifikasi
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Notifikasi
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Notifications\Views\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Notifications\Views\components\_idx_header') ?>
<?= $this->include('App\Modules\Notifications\Views\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Notifications\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>