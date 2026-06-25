<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>Pengaturan Aplikasi - Pasangin<?= $this->endSection() ?>
<?= $this->section('page_title') ?>Pengaturan Aplikasi<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    .sett-wrap {
        max-width: 640px;
        margin: 1.5rem auto;
    }

    .sett-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03), 0 1px 3px rgba(15, 23, 42, 0.02);
        overflow: hidden;
    }

    /* Hero/Header */
    .sett-hero {
        background: #fff;
        padding: 32px 36px 28px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
    }
    
    .sett-hero-inner {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .sett-hero-ico {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(229, 57, 53, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    
    .sett-hero-ico i {
        color: #e53935;
        font-size: 1.2rem;
    }
    
    .sett-hero h5 {
        color: #0f172a !important;
        font-weight: 800;
        font-size: 1.15rem;
        margin: 0 0 4px;
    }
    
    .sett-hero p {
        color: #64748b;
        font-size: 0.82rem;
        margin: 0;
        font-weight: 500;
    }

    /* Body */
    .sett-body {
        padding: 32px 36px;
    }

    /* Section divider */
    .sett-divider {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #475569;
        margin: 28px 0 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .sett-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    
    .sett-divider i {
        color: #e53935;
        font-size: 0.9rem;
    }
    
    .sett-divider:first-of-type {
        margin-top: 0;
    }

    /* Form controls */
    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        display: block;
    }

    /* Hide number inputs spinner */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }

    .input-row {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .input-row:hover {
        border-color: #cbd5e1;
    }
    
    .input-row:focus-within {
        border-color: #e53935;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.15);
    }
    
    .input-row .adorn {
        padding: 0 16px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        border-right: 1.5px solid #e2e8f0;
        min-height: 48px;
        display: flex;
        align-items: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .input-row > .adorn:first-child,
    .input-row > input:first-child {
        border-top-left-radius: 10.5px;
        border-bottom-left-radius: 10.5px;
    }

    .input-row > .adorn-r:last-child,
    .input-row > input:last-child {
        border-top-right-radius: 10.5px;
        border-bottom-right-radius: 10.5px;
    }
    
    .input-row:focus-within .adorn {
        background: rgba(229, 57, 53, 0.04);
        border-color: #ffd3d3;
        color: #e53935;
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
        padding: 12px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #0f172a;
        min-height: 48px;
    }
    
    .input-hint {
        font-size: 0.72rem;
        color: #64748b;
        margin-top: 6px;
        line-height: 1.4;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .input-hint i {
        color: #94a3b8;
        font-size: 0.78rem;
    }

    /* Fee type radio cards */
    .fee-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 4px;
    }
    
    .fee-opt {
        position: relative;
        cursor: pointer;
    }
    
    .fee-opt input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .fee-opt-lbl {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .fee-opt-lbl:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);
    }
    
    .fee-opt input:checked + .fee-opt-lbl {
        border-color: #e53935;
        background: rgba(229, 57, 53, 0.02);
        box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.08), 0 4px 12px rgba(229, 57, 53, 0.04);
    }
    
    .fee-opt-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    
    .fee-opt input:checked + .fee-opt-lbl .fee-opt-ico {
        background: #e53935;
        border-color: #e53935;
    }
    
    .fee-opt-ico i {
        font-size: 0.85rem;
        color: #64748b;
        transition: all 0.2s ease;
    }
    
    .fee-opt input:checked + .fee-opt-lbl .fee-opt-ico i {
        color: #fff;
    }
    
    .fee-opt-text strong {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #1e293b;
        transition: color 0.2s ease;
    }
    
    .fee-opt-text span {
        display: block;
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 2px;
    }
    
    .fee-opt input:checked + .fee-opt-lbl .fee-opt-text strong {
        color: #e53935;
    }

    /* Actions */
    .sett-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid #f1f5f9;
        margin-top: 32px;
    }
    
    .btn-sett-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fff;
        color: #475569;
        font-weight: 700;
        font-size: 0.85rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 24px;
        text-decoration: none;
        transition: all 0.2s ease;
        height: 46px;
    }
    
    .btn-sett-cancel:hover {
        border-color: #cbd5e1;
        color: #0f172a;
        background: #f8fafc;
    }

    .btn-sett-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #e53935;
        color: #fff !important;
        font-weight: 700;
        font-size: 0.85rem;
        border: none;
        border-radius: 12px;
        padding: 12px 26px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(229, 57, 53, 0.2);
        text-decoration: none;
        height: 46px;
    }
    
    .btn-sett-save:hover {
        background: #d32f2f;
        transform: translateY(-1.5px);
        box-shadow: 0 6px 20px rgba(229, 57, 53, 0.3);
    }
    
    .btn-sett-save:active {
        transform: translateY(0);
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
                    <i class="fas fa-cog"></i>
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
                <div class="sett-divider"><i class="fas fa-receipt"></i> Pajak</div>

                <div class="form-group">
                    <label class="form-label" for="tax_rate">Tarif PPN</label>
                    <div class="input-row">
                        <span class="adorn"><i class="fas fa-file-invoice"></i></span>
                        <input type="number" name="tax_rate" id="tax_rate"
                               step="0.01" min="0" max="100"
                               value="<?= esc($settings['tax_rate']) ?>"
                               placeholder="11" required>
                        <span class="adorn adorn-r">%</span>
                    </div>
                    <p class="input-hint"><i class="fas fa-info-circle"></i> Dibebankan pada setiap pembelian produk.</p>
                </div>

                <!-- Biaya Aplikasi -->
                <div class="sett-divider"><i class="fas fa-mobile-alt"></i> Biaya Aplikasi</div>

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

                <!-- Revisi Desain -->
                <div class="sett-divider"><i class="fas fa-redo-alt"></i> Revisi Desain</div>

                <div class="form-group">
                    <label class="form-label" for="design_revision_price">Harga Tambah Kuota Revisi</label>
                    <div class="input-row">
                        <span class="adorn">Rp</span>
                        <input type="number" name="design_revision_price" id="design_revision_price"
                               min="0"
                               value="<?= esc($settings['design_revision_price'] ?? 100000) ?>"
                               placeholder="100000" required>
                    </div>
                    <p class="input-hint"><i class="fas fa-info-circle"></i> Biaya yang dikenakan kepada klien per satu kali penambahan kuota revisi desain.</p>
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
