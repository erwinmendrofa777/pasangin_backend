<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Managerial Tugas (Kanban)
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Managerial Tugas
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Design\Views\components\_idx_styles') ?>
<?= $this->include('App\Modules\Design\Views\components\_kanban_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <?= $this->include('App\Modules\Design\Views\components\_idx_kanban_board') ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<?= $this->include('App\Modules\Design\Views\components\_idx_scripts') ?>
<?= $this->endSection() ?>
