<!-- Nama Lengkap -->
<div class="col-12">
    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-user"></i></span>
        <input type="text" class="form-control" id="full_name" name="full_name"
               value="<?= esc($user['full_name'] ?? '') ?>"
               placeholder="Masukkan nama lengkap" required>
    </div>
</div>

<!-- NIK -->
<div class="col-12 col-sm-6">
    <label for="nik" class="form-label">NIK</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
        <input type="text" class="form-control" id="nik" name="nik"
               value="<?= esc($user['nik'] ?? '') ?>"
               placeholder="16 digit NIK" maxlength="16">
    </div>
</div>

<!-- Jenis Kelamin -->
<div class="col-12 col-sm-6">
    <label for="gender" class="form-label">Jenis Kelamin</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
        <select class="form-select" id="gender" name="gender">
            <option value="">-- Pilih --</option>
            <option value="Laki - laki" <?= ($user['gender'] ?? '') === 'Laki - laki' ? 'selected' : '' ?>>Laki - laki</option>
            <option value="Perempuan" <?= ($user['gender'] ?? '') === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>
    </div>
</div>

<!-- Tanggal Lahir -->
<div class="col-12 col-sm-6">
    <label for="birth_date" class="form-label">Tanggal Lahir</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
        <input type="date" class="form-control" id="birth_date" name="birth_date"
               value="<?= esc($user['birth_date'] ?? '') ?>">
    </div>
</div>
