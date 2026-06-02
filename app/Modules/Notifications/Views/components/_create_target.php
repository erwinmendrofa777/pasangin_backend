<!-- ===== TARGET PENGIRIMAN ===== -->
<div class="card section-card mb-4">
    <div class="card-header">
        <h6><i class="fas fa-bullseye me-2"></i>Target Pengiriman</h6>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label">Tipe Pengiriman <span class="text-danger">*</span></label>
                <div class="d-flex gap-3 mt-1">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sendAll" name="send_type"
                            class="custom-control-input" value="all" checked>
                        <label class="custom-control-label" style="font-weight: 600;"
                            for="sendAll">Semua User</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sendSpecific" name="send_type"
                            class="custom-control-input" value="specific">
                        <label class="custom-control-label" style="font-weight: 600;"
                            for="sendSpecific">Spesifik</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Role Target <span class="text-danger">*</span></label>
                <select name="target" id="targetRole" class="form-control" required>
                    <option value="client">Klien (User)</option>
                    <option value="tukang">Tukang (Mitra)</option>
                    <option value="supplier">Supplier (Toko)</option>
                    <option value="admin">Admin (Internal)</option>
                </select>
            </div>
        </div>

        <div class="mb-2" id="specificUserContainer" style="display: none;">
            <label class="form-label">Pilih User <span class="text-danger">*</span></label>
            <select name="target_id" id="targetId" class="form-control select2" style="width: 100%;">
                <option value="">Ketik nama / no HP...</option>
            </select>
            <small class="text-muted d-block mt-1">Ketik minimal 3 karakter untuk mencari</small>
        </div>
    </div>
</div>
