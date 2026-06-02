<!-- ===== DELETE CONFIRMATION MODAL ===== -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger" id="deleteProductModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus Produk
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3 mx-auto shadow-sm"
                    style="width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem; background:#fff5f5; color:#e03131;">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 class="fw-bold mb-2">Hapus Produk Ini?</h5>
                <p class="text-muted px-3" style="font-size:0.85rem;">
                    Anda akan menghapus produk <strong><?= esc($product['name']) ?></strong>.
                    Tindakan ini permanen dan data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal" style="border-radius:8px;">
                    Batal
                </button>
                <a href="<?= base_url('admin/products/delete/' . $product['id']) ?>" class="btn btn-danger px-4 fw-semibold" style="border-radius:8px;">
                    <i class="fas fa-trash-alt me-1"></i>Hapus Permanen
                </a>
            </div>
        </div>
    </div>
</div>
