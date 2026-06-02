<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Kelola Permintaan Desain
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Design\Views\components\_idx_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\Design\Views\components\_idx_stats_cards') ?>
<?= $this->include('App\Modules\Design\Views\components\_idx_task_slider') ?>
<?= $this->include('App\Modules\Design\Views\components\_idx_table') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Design\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>