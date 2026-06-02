<!-- Email -->
<div class="col-12">
    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= esc($user['email'] ?? '') ?>"
               placeholder="contoh@email.com" required>
    </div>
</div>

<!-- Nomor WhatsApp -->
<div class="col-12">
    <label for="phone_number" class="form-label">Nomor WhatsApp</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
        <input type="tel" class="form-control" id="phone_number" name="phone_number"
               value="<?= esc($user['phone_number'] ?? '') ?>"
               placeholder="08xxxxxxxxxx">
    </div>
</div>

<!-- Alamat -->
<div class="col-12">
    <label for="address" class="form-label">Alamat</label>
    <div class="input-group align-items-start">
        <span class="input-group-text" style="padding-top:31.5px;padding-bottom:31.5px;"><i class="fas fa-map-marker-alt"></i></span>
        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap"><?= esc($user['address'] ?? '') ?></textarea>
    </div>
</div>
