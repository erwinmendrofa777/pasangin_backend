<?php
/* ===== AVATAR & INITIALS ===== */
if (!empty($admin['photo'])) {
    $avatarSrc = strpos($admin['photo'], 'http') === 0
        ? $admin['photo']
        : base_url('uploads/profile/' . $admin['photo']);
} else {
    $avatarSrc = null;
}
$nameParts = explode(' ', trim($admin['full_name'] ?? 'A'));
$initials   = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>
<form id="edit-admin-form" method="POST" action="<?= base_url('admin/admin/update/' . $admin['id']) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row justify-content-center">

        <!-- ======================== MAIN FORM ======================== -->
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            <div class="card edit-card">

                <!-- Hero Banner -->
                <div class="edit-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
                        <div>
                            <h5 class="text-white mb-1 fw-bold ms-1" style="font-size:1.15rem;">
                                Edit Data Admin
                            </h5>
                        </div>
                        <span class="badge bg-white text-primary px-3 py-2" style="border-radius:50px; font-size:0.78rem; font-weight:700;">
                            <i class="fas fa-hashtag me-1 opacity-75"></i>ID <?= esc($admin['id']) ?>
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="edit-body pb-0">

                    <!-- Avatar -->
                    <div class="d-flex align-items-end justify-content-between mb-2">
                        <div class="avatar-wrapper position-relative">
                            <?php if ($avatarSrc): ?>
                                <img src="<?= $avatarSrc ?>" alt="<?= esc($admin['full_name']) ?>"
                                     class="avatar-img" id="img-preview">
                            <?php else: ?>
                                <div class="avatar-initials d-flex" id="img-preview-initials"><?= $initials ?></div>
                                <img src="" alt="Preview" class="avatar-img d-none" id="img-preview">
                            <?php endif; ?>

                            <!-- Upload Button Overlay -->
                            <label for="photo" class="btn btn-sm btn-primary position-absolute rounded-circle shadow" 
                                   style="bottom: 0; right: -5px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="photo" name="photo" class="d-none" accept="image/*" onchange="previewImage()">
                        </div>
                    </div>

                    <!-- ===== Identitas Pribadi ===== -->
                    <div class="card section-card mb-4">
                        <div class="card-header pt-1 pb-1 mb-0">
                            <h6><i class="fas fa-id-card me-2"></i>Identitas Admin</h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">

                                <!-- Nama Lengkap -->
                                <div class="col-12">
                                    <label for="full_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                               value="<?= esc(old('full_name', $admin['full_name'])) ?>"
                                               placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>

                                <!-- Password Baru -->
                                <div class="col-12 col-sm-6">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" id="password" name="password"
                                               placeholder="Kosongkan jika tidak diubah">
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="col-12 col-sm-6">
                                    <label for="role" class="form-label">Role Admin <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                        <?php if($admin['id'] == 1 || ($admin['role'] == 'super_admin' && session()->get('user_id') == $admin['id'])): ?>
                                            <input type="text" class="form-control" value="Super Admin" readonly>
                                            <input type="hidden" name="role" value="super_admin">
                                        <?php else: ?>
                                            <select class="form-select" id="role" name="role" required>
                                                <?php foreach ($roles as $r): ?>
                                                    <option value="<?= esc($r['role_name']) ?>" <?= old('role', $admin['role']) == $r['role_name'] ? 'selected' : '' ?>>
                                                        <?= esc(ucwords(str_replace('_', ' ', $r['role_name']))) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($admin['id'] == 1 || ($admin['role'] == 'super_admin' && session()->get('user_id') == $admin['id'])): ?>
                                        <small class="text-info mt-1 d-block"><i class="fas fa-info-circle"></i> Role Anda sendiri terkunci.</small>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ===== Kontak ===== -->
                    <div class="card section-card mb-4">
                        <div class="card-header pt-1 pb-1 mb-0">
                            <h6><i class="fas fa-address-book me-2"></i>Informasi Kontak</h6>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3">

                                <!-- Email -->
                                <div class="col-12">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?= esc(old('email', $admin['email'])) ?>"
                                               placeholder="contoh@email.com" required>
                                    </div>
                                </div>

                                <!-- Nomor Telepon -->
                                <div class="col-12">
                                    <label for="phone_number" class="form-label">Nomor Telepon</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                               value="<?= esc(old('phone_number', $admin['phone_number'] ?? '')) ?>"
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4 px-4">
                        <div class="col-6">
                            <?php if (can('admin_edit')): ?>
                            <button type="submit" class="btn btn-save w-100 ladda-button" data-style="zoom-out" id="submit-btn">
                                <span class="ladda-label"><i class="fas fa-save me-2"></i>Simpan</span>
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-lock me-2"></i>Akses Ditolak
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('admin/admin') ?>" class="btn btn-outline-secondary btn-cancel text-center w-100">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</form>
