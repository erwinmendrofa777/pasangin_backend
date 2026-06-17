<!-- ===== FORM MODAL FOR ADD & EDIT ===== -->
<div class="modal fade" id="ahspModal" tabindex="-1" aria-labelledby="ahspModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h6 class="modal-title fw-bold text-dark" id="ahspModalTitle" style="font-size: 1.1rem;">
                    <i class="fas fa-clipboard-list me-2 text-primary"></i>Tambah AHSP Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="ahspForm" action="<?= site_url('admin/ahsp/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body py-4 px-4">
                    <!-- Header Data -->
                    <div class="card p-3 mb-4 border-0" style="background: #f8fafc; border-radius: 12px;">
                        <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;"><i class="fas fa-info-circle me-1 text-primary"></i>Data Utama AHSP</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="kode" class="form-label fw-bold text-dark small">Kode AHSP <span class="text-danger">*</span></label>
                                <input type="text" name="kode" id="kode" class="form-control form-control-sm" placeholder="Contoh: A.2.2.1.1" required style="border-radius: 8px; height: 38px; border: 1.5px solid #e2e8f0; font-weight: 600;">
                            </div>
                            <div class="col-12 col-md-8">
                                <label for="uraian" class="form-label fw-bold text-dark small">Uraian Pekerjaan <span class="text-danger">*</span></label>
                                <input type="text" name="uraian" id="uraian" class="form-control form-control-sm" placeholder="Contoh: Pemasangan 1 m2 dinding bata merah..." required style="border-radius: 8px; height: 38px; border: 1.5px solid #e2e8f0; font-weight: 600;">
                            </div>
                        </div>

                    </div>

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs nav-tabs-premium mb-3" id="ahspTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bahan-tab" data-bs-toggle="tab" data-bs-target="#bahan-pane" type="button" role="tab" aria-controls="bahan-pane" aria-selected="true">
                                <i class="fas fa-boxes me-2"></i>Daftar Bahan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tenaga-tab" data-bs-toggle="tab" data-bs-target="#tenaga-pane" type="button" role="tab" aria-controls="tenaga-pane" aria-selected="false">
                                <i class="fas fa-users me-2"></i>Tenaga Kerja
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="ahspTabContent">
                        <!-- Tab Bahan -->
                        <div class="tab-pane fade show active" id="bahan-pane" role="tabpanel" aria-labelledby="bahan-tab" tabindex="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Tambahkan item bahan beserta koefisien kebutuhan material.</span>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="btn-add-bahan" style="border-radius: 6px;">
                                    <i class="fas fa-plus me-1"></i> Tambah Baris Bahan
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="table-bahan-form">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 150px; white-space: nowrap;">Kode Bahan</th>
                                            <th style="white-space: nowrap;">Nama / Uraian Bahan <span class="text-danger">*</span></th>
                                            <th style="width: 120px; white-space: nowrap;">Satuan <span class="text-danger">*</span></th>
                                            <th style="width: 150px; white-space: nowrap;">Koefisien <span class="text-danger">*</span></th>
                                            <th class="text-center" style="width: 80px; white-space: nowrap;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Row appended dynamically by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Tenaga Kerja -->
                        <div class="tab-pane fade" id="tenaga-pane" role="tabpanel" aria-labelledby="tenaga-tab" tabindex="0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small">Tambahkan klasifikasi tenaga kerja beserta koefisien waktu dan tarif.</span>
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" id="btn-add-tenaga" style="border-radius: 6px;">
                                    <i class="fas fa-plus me-1"></i> Tambah Baris Tenaga
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="table-tenaga-form">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 150px; white-space: nowrap;">Kode Tenaga</th>
                                            <th style="white-space: nowrap;">Klasifikasi Pekerja <span class="text-danger">*</span></th>
                                            <th style="width: 120px; white-space: nowrap;">Satuan <span class="text-danger">*</span></th>
                                            <th style="width: 150px; white-space: nowrap;">Koefisien <span class="text-danger">*</span></th>
                                            <th style="width: 180px; white-space: nowrap;">Harga Satuan (Rp) <span class="text-danger">*</span></th>
                                            <th class="text-center" style="width: 80px; white-space: nowrap;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Row appended dynamically by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-end gap-2 pt-0 pb-4 pe-4">
                    <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius:8px; font-size: 0.85rem; height: 38px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold" style="border-radius:8px; font-size: 0.85rem; height: 38px;">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
