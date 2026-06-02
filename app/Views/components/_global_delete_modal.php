<!-- ===== GLOBAL DELETE CONFIRMATION MODAL ===== -->
<div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-labelledby="globalDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:14px; border:none; box-shadow:0 16px 48px rgba(0,0,0,0.18);">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3 mx-auto shadow-sm"
                    style="width:68px;height:68px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem; background:#fff5f5; color:#e03131;">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <h5 class="fw-bold mb-2" id="globalDeleteModalTitle">Hapus Data Ini?</h5>
                <p class="text-muted px-3" style="font-size:0.85rem;" id="globalDeleteModalMsg">
                    Data yang dihapus tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-4">
                <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius:8px;">
                    Batal
                </button>
                <a href="#" id="globalDeleteModalBtn" class="btn btn-danger px-4 fw-bold" style="border-radius:8px;">
                    <i class="fas fa-trash-alt me-1"></i>Hapus Permanen
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var globalDeleteModal = document.getElementById('globalDeleteModal');
        if (globalDeleteModal) {
            globalDeleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var url = button.getAttribute('data-delete-url');
                var title = button.getAttribute('data-delete-title');
                var msg = button.getAttribute('data-delete-msg');

                if (title) {
                    document.getElementById('globalDeleteModalTitle').innerHTML = title;
                }
                if (msg) {
                    document.getElementById('globalDeleteModalMsg').innerHTML = msg;
                }
                if (url) {
                    document.getElementById('globalDeleteModalBtn').setAttribute('href', url);
                }
            });
        }
    });
</script>
