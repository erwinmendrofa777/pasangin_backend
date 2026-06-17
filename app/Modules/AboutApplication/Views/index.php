<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Tentang Aplikasi Pasangin
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Tentang Aplikasi Pasangin
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\AboutApplication\Views\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\AboutApplication\Views\components\_header_card') ?>
<?= $this->include('App\Modules\AboutApplication\Views\components\_editor_card') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\AboutApplication\Views\components\_editor_scripts') ?>
<?= $this->endSection() ?>