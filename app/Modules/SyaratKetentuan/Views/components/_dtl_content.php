<div class="row justify-content-center">
    <div class="col-12 col-lg-9">
        <div class="card detail-card">
            <div class="detail-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light btn-sm rounded-circle me-3" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left small"></i>
                    </a>
                    <div>
                        <span class="meta-label">Pratinjau Dokumen</span>
                        <h4 class="mb-0 fw-800 text-dark"><?= esc($data['title']) ?></h4>
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="target-badge">
                        <i class="fas fa-bullseye me-1"></i> <?= $data['target_app'] ?>
                    </span>
                    <a href="<?= base_url('admin/syarat_ketentuan/edit/' . $data['id']) ?>" class="btn btn-warning px-3 fw-bold" style="border-radius: 10px;">
                        <i class="fas fa-pencil-alt me-2"></i>Edit
                    </a>
                </div>
            </div>
            <div class="detail-body">
                <div class="mb-4">
                    <span class="meta-label mb-3">Isi Dokumen</span>
                    <div class="doc-content shadow-sm" id="doc-rendered-content">
                        <!-- Content rendered by JS below -->
                    </div>
                </div>

                <div class="pt-4 border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="far fa-clock me-1"></i> Terakhir diperbarui: <span class="fw-bold"><?= date('d M Y, H:i') ?></span>
                    </div>
                    <a href="<?= base_url('admin/syarat_ketentuan') ?>" class="btn btn-light px-4 fw-bold" style="border-radius: 10px;">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
