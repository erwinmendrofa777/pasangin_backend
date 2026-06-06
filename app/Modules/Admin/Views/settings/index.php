<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>Pengaturan Aplikasi - Pasangin<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Pengaturan Aplikasi<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .sett-wrap {
        max-width: 600px;
        margin: 0 auto;
    }

    .sett-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,.06), 0 16px 48px rgba(255,92,92,.08);
        overflow: hidden;
    }

    /* Hero */
    .sett-hero {
        background: linear-gradient(135deg, #ff5c5c 0%, #ff8585 100%);
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
    }
    .sett-hero::after {
        content: '';
        position: absolute;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        top: -60px; right: -40px;
    }
    .sett-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .sett-hero-ico {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .sett-hero-ico i { color: #fff; font-size: 1.1rem; }
    .sett-hero h5 { color: #fff !important; font-weight: 800; font-size: 1.05rem; margin: 0 0 2px; }
    .sett-hero p  { color: rgba(255,255,255,.8); font-size: .78rem; margin: 0; }

    /* Body */
    .sett-body { padding: 30px 32px; }

    /* Divider label */
    .sett-divider {
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #ff5c5c;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .sett-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #ffe5e5;
    }

    /* Form */
    .form-group { margin-bottom: 22px; }

    .form-label {
        font-size: .75rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }

    .input-row {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        overflow: hidden;
        transition: all .2s;
    }
    .input-row:focus-within {
        border-color: #ff5c5c;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255,92,92,.1);
    }
    .input-row .adorn {
        padding: 0 12px;
        font-size: .82rem;
        font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        border-right: 1.5px solid #e2e8f0;
        min-height: 44px;
        display: flex; align-items: center;
        flex-shrink: 0;
        transition: all .2s;
    }
    .input-row:focus-within .adorn {
        background: #fff5f5;
        border-color: #ffd3d3;
        color: #ff5c5c;
    }
    .adorn-r {
        border-right: none !important;
        border-left: 1.5px solid #e2e8f0 !important;
    }
    .input-row input,
    .input-row select {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        padding: 11px 14px;
        font-size: .88rem;
        font-weight: 600;
        color: #0f172a;
        min-height: 44px;
    }
    .input-hint {
        font-size: .7rem;
        color: #94a3b8;
        margin-top: 5px;
    }

    /* Fee type radio cards */
    .fee-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    .fee-opt { position: relative; cursor: pointer; }
    .fee-opt input { position: absolute; opacity: 0; width: 0; height: 0; }
    .fee-opt-lbl {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        background: #f8fafc;
        transition: all .2s;
        cursor: pointer;
    }
    .fee-opt input:checked + .fee-opt-lbl {
        border-color: #ff5c5c;
        background: #fff5f5;
        box-shadow: 0 0 0 3px rgba(255,92,92,.08);
    }
    .fee-opt-ico {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: all .2s;
    }
    .fee-opt input:checked + .fee-opt-lbl .fee-opt-ico {
        background: #ff5c5c; border-color: #ff5c5c;
    }
    .fee-opt-ico i { font-size: .78rem; color: #94a3b8; transition: all .2s; }
    .fee-opt input:checked + .fee-opt-lbl .fee-opt-ico i { color: #fff; }
    .fee-opt-text strong { display: block; font-size: .8rem; font-weight: 700; color: #334155; }
    .fee-opt-text span   { font-size: .68rem; color: #94a3b8; }
    .fee-opt input:checked + .fee-opt-lbl .fee-opt-text strong { color: #ff5c5c; }

    /* Actions */
    .sett-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 8px;
        border-top: 1px solid #f1f5f9;
        margin-top: 8px;
    }
    .btn-sett-cancel {
        display: inline-flex; align-items: center; gap: 6px;
        background: #fff;
        color: #64748b;
        font-weight: 700; font-size: .82rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 20px;
        text-decoration: none;
        transition: all .2s;
    }
    .btn-sett-cancel:hover { border-color: #ffd3d3; color: #ff5c5c; background: #fff5f5; }

    .btn-sett-save {
        display: inline-flex; align-items: center; gap: 6px;
        background: #ff5c5c;
        color: #fff !important;
        font-weight: 700; font-size: .82rem;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 12px rgba(255,92,92,.3);
        text-decoration: none;
    }
    .btn-sett-save:hover {
        background: #e04d4d;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255,92,92,.4);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="sett-wrap">
    <div class="sett-card">

        <!-- Hero -->
        <div class="sett-hero">
            <div class="sett-hero-inner">
                <div class="sett-hero-ico">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h5>Pengaturan Aplikasi</h5>
                    <p>Tarif pajak & biaya layanan transaksi</p>
                </div>
            </div>
        </div>

        <!-- Form body -->
        <div class="sett-body">
            <form action="<?= site_url('admin/settings/update') ?>" method="POST" id="form-settings">
                <?= csrf_field() ?>

                <!-- Pajak -->
                <p class="sett-divider"><i class="fas fa-receipt"></i> Pajak</p>

                <div class="form-group">
                    <label class="form-label" for="tax_rate">Tarif PPN</label>
                    <div class="input-row">
                        <span class="adorn"><i class="fas fa-percentage"></i></span>
                        <input type="number" name="tax_rate" id="tax_rate"
                               step="0.01" min="0" max="100"
                               value="<?= esc($settings['tax_rate']) ?>"
                               placeholder="11" required>
                        <span class="adorn adorn-r">%</span>
                    </div>
                    <p class="input-hint">Dibebankan pada setiap pembelian produk.</p>
                </div>

                <!-- Biaya Aplikasi -->
                <p class="sett-divider"><i class="fas fa-mobile-alt"></i> Biaya Aplikasi</p>

                <div class="form-group">
                    <label class="form-label">Tipe Biaya</label>
                    <div class="fee-grid">
                        <label class="fee-opt">
                            <input type="radio" name="app_fee_type" value="flat"
                                <?= $settings['app_fee_type'] === 'flat' ? 'checked' : '' ?>>
                            <div class="fee-opt-lbl">
                                <div class="fee-opt-ico"><i class="fas fa-tag"></i></div>
                                <div class="fee-opt-text">
                                    <strong>Flat</strong>
                                    <span>Nominal tetap (Rp)</span>
                                </div>
                            </div>
                        </label>
                        <label class="fee-opt">
                            <input type="radio" name="app_fee_type" value="percentage"
                                <?= $settings['app_fee_type'] === 'percentage' ? 'checked' : '' ?>>
                            <div class="fee-opt-lbl">
                                <div class="fee-opt-ico"><i class="fas fa-percent"></i></div>
                                <div class="fee-opt-text">
                                    <strong>Persentase</strong>
                                    <span>% dari total harga</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="app_fee_value">Nilai Biaya</label>
                    <div class="input-row">
                        <span class="adorn" id="app-fee-prefix">Rp</span>
                        <input type="number" name="app_fee_value" id="app_fee_value"
                               step="0.01" min="0"
                               value="<?= esc($settings['app_fee_value']) ?>"
                               placeholder="2000" required>
                        <span class="adorn adorn-r" id="app-fee-suffix" style="display:none;">%</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="sett-actions">
                    <a href="<?= base_url('admin/dashboard') ?>" class="btn-sett-cancel">
                        Batal
                    </a>
                    <?php if (can('settings_edit')) : ?>
                        <button type="submit" class="btn-sett-save ladda-button" data-style="zoom-in">
                            <span class="ladda-label"><i class="fas fa-save"></i> Simpan</span>
                        </button>
                    <?php endif; ?>
                </div>

            </form>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios  = document.querySelectorAll('input[name="app_fee_type"]');
    const prefix  = document.getElementById('app-fee-prefix');
    const suffix  = document.getElementById('app-fee-suffix');

    function toggle() {
        const val = document.querySelector('input[name="app_fee_type"]:checked')?.value;
        if (val === 'flat') {
            prefix.style.display = ''; prefix.innerHTML = 'Rp';
            suffix.style.display = 'none';
        } else {
            prefix.style.display = 'none';
            suffix.style.display = ''; suffix.innerHTML = '%';
        }
    }

    radios.forEach(r => r.addEventListener('change', toggle));
    toggle();
});
</script>

<?php if (session()->getFlashdata('success')) : ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        iziToast.success({ title: 'Berhasil!', message: '<?= session()->getFlashdata('success') ?>', position: 'topCenter', timeout: 5000 });
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        iziToast.error({ title: 'Gagal!', message: '<?= strip_tags(session()->getFlashdata('error')) ?>', position: 'topCenter', timeout: 6000 });
    });
</script>
<?php endif; ?>
<?= $this->endSection() ?>
