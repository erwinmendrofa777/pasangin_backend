<?php
$status = $user['status'] ?? 'unknown';
$statusMeta = [
    'approved' => ['class' => 'status-approved', 'icon' => 'fas fa-check-circle',  'label' => 'Approved'],
    'pending'  => ['class' => 'status-pending',  'icon' => 'fas fa-clock',          'label' => 'Pending'],
    'rejected' => ['class' => 'status-rejected', 'icon' => 'fas fa-times-circle',   'label' => 'Rejected'],
    'banned'   => ['class' => 'status-banned',   'icon' => 'fas fa-ban',            'label' => 'Banned'],
];
$currentMeta = $statusMeta[$status] ?? ['class' => 'status-default', 'icon' => 'fas fa-circle', 'label' => ucfirst($status)];

if (!empty($user['avatar'])) {
    $avatarSrc = strpos($user['avatar'], 'http') === 0 ? $user['avatar'] : base_url('uploads/profile/' . $user['avatar']);
} else {
    $avatarSrc = null;
}

$nameParts = explode(' ', trim($user['full_name'] ?? 'U'));
$initials   = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>
<!-- Hero Banner -->
<div class="profile-hero bg-primary">
    <div class="d-flex justify-content-between align-items-center" style="z-index:2; position: relative;">
        <h5 class="text-white mb-0 ms-3 fw-bold" style="font-size:1.3rem; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">
            <?= esc($user['full_name'] ?? '-') ?>
        </h5>
        <div class="d-flex align-items-center gap-2">
            <span class="role-chip-hero">
                <i class="fas fa-user-tag me-1"></i><?= esc($user['role'] ?? '-') ?>
            </span>
            <span class="status-pill <?= $currentMeta['class'] ?>">
                <span class="dot"></span><?= $currentMeta['label'] ?>
            </span>
        </div>
    </div>
</div>

<!-- Hero Profile Overlap Info -->
<div class="px-4 pb-3" style="background: #fff;">
    <div class="d-flex align-items-end justify-content-between flex-wrap" style="margin-top: -50px; z-index: 5; position: relative;">
        <div class="avatar-wrapper">
            <?php if ($avatarSrc): ?>
                <a href="<?= $avatarSrc ?>" class="glightbox" data-title="<?= esc($user['full_name']) ?>" data-description="Email: <?= esc($user['email'] ?: '-') ?> &lt;br&gt; Telepon: <?= esc($user['phone_number'] ?: '-') ?> &lt;br&gt; Status: <?= esc(ucfirst($user['status'] ?? '')) ?>">
                    <img src="<?= $avatarSrc ?>" alt="<?= esc($user['full_name']) ?>"
                        class="avatar-img" id="img-preview" data-toggle="tooltip" title="Klik untuk memperbesar">
                </a>
            <?php else: ?>
                <div class="avatar-initials"><?= $initials ?></div>
            <?php endif; ?>
        </div>
        <span class="text-muted mb-2" style="font-size:0.77rem;">
            Daftar Pada: <strong><?= !empty($user['created_at']) ? date('d M Y H:i', strtotime($user['created_at'])) : '-' ?></strong>
        </span>
    </div>
</div>
