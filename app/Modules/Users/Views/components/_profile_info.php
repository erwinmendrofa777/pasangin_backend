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
<div class="card shadow-sm profile-card">

    <!-- Hero Banner -->
    <div class="profile-hero bg-primary">
        <div class="d-flex justify-content-between align-items-center" style="z-index:1;">
            <h5 class="text-white mb-0 ms-3 fw-bold" style="font-size:1.2rem;">
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

    <!-- Profile Body -->
    <div class="profile-body">

        <!-- Avatar + ID -->
        <div class="d-flex align-items-end justify-content-between mb-3">
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
            <span class="text-muted" style="font-size:0.77rem; padding-bottom:4px;">
                Dibuat Pada: <strong><?= esc($user['created_at']) ?></strong>
            </span>
        </div>

        <hr class="my-3">

        <!-- Info List -->
        <p class="section-title"><i class="fas fa-id-card me-1"></i>Informasi Pribadi</p>

        <div class="info-list">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-user"></i></div>
                <div>
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value"><?= esc($user['full_name'] ?? '-') ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                <div>
                    <div class="info-label">NIK</div>
                    <div class="info-value"><?= esc($user['nik'] ?? '-') ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value"><?= esc($user['email'] ?? '-') ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fab fa-whatsapp"></i></div>
                <div>
                    <div class="info-label">Nomor WhatsApp</div>
                    <div class="info-value"><?= esc($user['phone_number'] ?? '-') ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-venus-mars"></i></div>
                <div>
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value"><?= esc($user['gender'] ?? '-') ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-birthday-cake"></i></div>
                <div>
                    <div class="info-label">Tanggal Lahir</div>
                    <div class="info-value"><?= esc($user['birth_date'] ?? '-') ?></div>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="info-label">Alamat</div>
                    <div class="info-value"><?= esc($user['address'] ?? '-') ?></div>
                </div>
            </div>
        </div>

    </div>
</div>
