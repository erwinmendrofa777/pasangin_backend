<!-- ===== CONFIRMATION MODAL ===== -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1"
    aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="confirmStatusModalLabel">
                    <i class="fas fa-shield-alt text-primary me-2"></i>Konfirmasi Perubahan Status
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div id="modalIconWrap" class="mb-3 mx-auto"
                    style="width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.9rem;">
                </div>
                <p class="mb-1 fw-semibold" style="font-size:1rem;">Ubah status menjadi</p>
                <h5 id="modalStatusLabel" class="fw-bold mb-3"></h5>
                <p class="text-muted" style="font-size:0.85rem;">
                    Status produk <strong><?= esc($product['name']) ?></strong> akan segera diperbarui.
                    Pastikan keputusan ini sudah benar.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <form id="updateStatusForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" id="modalConfirmBtn" class="btn px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i>Ya, Ubah Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
