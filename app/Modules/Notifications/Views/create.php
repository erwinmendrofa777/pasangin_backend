<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Kirim Notifikasi Massal
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<?= $this->include('App\Modules\Notifications\Views\components\_create_styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form id="create-notification-form" method="POST" action="<?= base_url('admin/notification/send') ?>"
    enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card edit-card mb-5">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold" style="font-size:1.15rem;">
                                Buat Notifikasi Baru
                            </h5>
                            <p class="text-white-50 small mb-0">Kirim pemberitahuan dan info promo secara real-time</p>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2 d-none d-sm-inline-block"
                            style="border-radius:50px; font-size:0.75rem; font-weight:700;">
                            <i class="fas fa-paper-plane me-1 opacity-75"></i>KIRIM
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pe-0 ps-0">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-9">

                            <?= $this->include('App\Modules\Notifications\Views\components\_create_banner_preview') ?>

                            <?= $this->include('App\Modules\Notifications\Views\components\_create_target') ?>

                            <?= $this->include('App\Modules\Notifications\Views\components\_create_content_form') ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Notifications\Views\components\_create_scripts') ?>
<?= $this->endSection() ?>