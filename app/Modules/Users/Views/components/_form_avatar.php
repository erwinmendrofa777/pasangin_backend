<?php
if (!empty($user['avatar'])) {
    $avatarSrc = strpos($user['avatar'], 'http') === 0 ? $user['avatar'] : base_url('uploads/profile/' . $user['avatar']);
} else {
    $avatarSrc = null;
}

$nameParts = explode(' ', trim($user['full_name'] ?? 'U'));
$initials   = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>
<!-- Avatar -->
<div class="d-flex align-items-end justify-content-between mb-2">
    <div class="avatar-wrapper position-relative">
        <?php if ($avatarSrc): ?>
            <img src="<?= $avatarSrc ?>" alt="<?= esc($user['full_name']) ?>"
                 class="avatar-img" id="img-preview">
        <?php else: ?>
            <div class="avatar-initials d-flex" id="img-preview-initials"><?= $initials ?></div>
            <img src="" alt="Preview" class="avatar-img d-none" id="img-preview">
        <?php endif; ?>

        <!-- Upload Button Overlay -->
        <label for="avatar" class="btn btn-sm btn-primary position-absolute rounded-circle shadow" 
               style="bottom: 0; right: -5px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid #fff;">
            <i class="fas fa-camera"></i>
        </label>
        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*" onchange="previewImage()">
    </div>
</div>
