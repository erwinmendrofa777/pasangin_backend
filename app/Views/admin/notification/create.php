<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?> Kirim Notifikasi <?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Form Kirim Notifikasi Massal</h4>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                <?php endif; ?>

                <form action="<?= base_url('admin/notification/send') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label>Pilih Target Penerima</label>
                        <select name="target" class="form-control" required>
                            <option value="tukang">Semua Tukang (Mitra)</option>
                            <option value="supplier">Semua Supplier (Toko)</option>
                            <option value="client">Semua Klien (User)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Judul Notifikasi</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Ada Promo Menarik!" required>
                    </div>

                    <div class="form-group">
                        <label>Isi Pesan</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Tulis pesan lengkapnya di sini..." required></textarea>
                    </div>

                    <div class="text-right">
                        <?php if (can('notification_create')): ?>
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm ladda-button" data-style="zoom-in">
                            <span class="ladda-label"><i class="fas fa-paper-plane"></i> Kirim Notifikasi Sekarang</span>
                        </button>
                        <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-lg shadow-sm" disabled>
                            <i class="fas fa-lock"></i> Akses Ditolak
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    // Integrasi Ladda Loading untuk tombol submit (menggunakan delegasi event agar berfungsi di pagination datatable)
    $(document).on('submit', 'form', function() {
        var btn = $(this).find('.ladda-button');
        if (btn.length > 0) {
            var l = Ladda.create(btn[0]);
            l.start();
        }
    });
</script>
<?= $this->endSection() ?>