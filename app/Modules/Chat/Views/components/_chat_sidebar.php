<!-- Sidebar: Daftar User Chat -->
<div class="col-md-4 col-12 chat-list-container">
    <div class="chat-list-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark" style="font-size: 1rem;">Obrolan Aktif</h6>
        <div class="d-flex align-items-center" style="gap: 5px;">
            <?php
            $totalUnread = 0;
            if (!empty($conversations)) {
                foreach ($conversations as $c) {
                    $totalUnread += intval($c['unread_by_admin_count'] ?? 0);
                }
            }
            if (!empty($projectConversations)) {
                foreach ($projectConversations as $pc) {
                    $totalUnread += intval($pc['unread_by_admin_count'] ?? 0);
                }
            }
            ?>
            <span id="total-unread-badge" class="badge px-2 py-1 text-danger"
                style="font-size: 0.72rem; border-radius: 12px; background-color: #fee2e2; font-weight: 600; <?= $totalUnread > 0 ? '' : 'display: none;' ?>">
                <?= $totalUnread ?> Belum Dibaca
            </span>
            <span class="badge px-2 py-1"
                style="font-size: 0.72rem; border-radius: 12px; background-color: rgba(255, 92, 92, 0.08); color: var(--palette-primary); border: 1px solid rgba(255, 92, 92, 0.15); font-weight: 600;">
                <?= count($conversations ?? []) + count($projectConversations ?? []) ?> Percakapan
            </span>
            <!-- Tombol Mulai Chat Proyek -->
            <button type="button" id="btn-start-project-chat" title="Mulai Chat Proyek Baru"
                data-bs-toggle="modal" data-bs-target="#modalStartProjectChat"
                style="width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(99,102,241,0.3); transition: all 0.2s ease; flex-shrink: 0;">
                <i class="fas fa-plus" style="font-size: 0.7rem;"></i>
            </button>
        </div>
    </div>

    <!-- Search & Filter Tab Area -->
    <div class="chat-search-filter-wrapper">
        <div class="search-input-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="chat-search" placeholder="Cari nama klien/proyek..." autocomplete="off">
            <i class="fas fa-times clear-search" id="clear-search" style="display: none; cursor: pointer;"></i>
        </div>
        <?php $activeTab = $activeTab ?? 'all'; ?>
        <div class="filter-pills">
            <button class="btn btn-filter <?= $activeTab === 'all' ? 'active' : '' ?>" data-filter="all">Semua</button>
            <button class="btn btn-filter <?= $activeTab === 'client' ? 'active' : '' ?>" data-filter="client">CS Klien</button>
            <button class="btn btn-filter <?= $activeTab === 'project' ? 'active' : '' ?>" data-filter="project">Proyek</button>
            <button class="btn btn-filter <?= $activeTab === 'supplier' ? 'active' : '' ?>" data-filter="supplier">Monitoring</button>
        </div>

        <?php if (can('super_admin_override') || count($allowedCategories ?? []) > 1): ?>
            <div class="category-select-wrapper mt-2">
                <select id="chat-category-select" class="form-select form-select-sm"
                    style="font-size: 0.75rem; font-weight: 600; color: #475569; border-radius: 10px; border: 1px solid #e2e8f0; padding: 8px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background-color: #ffffff; cursor: pointer; transition: all 0.2s ease;">
                    <option value="all">Semua Departemen</option>
                    <?php if (can('super_admin_override') || in_array('technical', $allowedCategories ?? [])): ?>
                        <option value="technical">Technical</option>
                    <?php endif; ?>
                    <?php if (can('super_admin_override') || in_array('accounting', $allowedCategories ?? [])): ?>
                        <option value="accounting">Accounting</option>
                    <?php endif; ?>
                    <?php if (can('super_admin_override') || in_array('general', $allowedCategories ?? [])): ?>
                        <option value="general">General</option>
                    <?php endif; ?>
                </select>
            </div>
        <?php endif; ?>
    </div>

    <!-- List Users (CS Chat) -->
    <div id="chat-list">
        <ul class="list-unstyled mb-0">
            <?php if (!empty($conversations)): ?>
                <?php foreach ($conversations as $convo): ?>
                    <?php
                    $isTukang = ($convo['client_type'] === 'tukang');
                    $badgeClass = $isTukang ? 'badge-tukang' : 'badge-klien';
                    $badgeText = $isTukang ? 'Tukang' : 'Klien';

                    // Format waktu pesan terakhir secara rapi
                    $timeStr = '';
                    if (!empty($convo['last_message_at'])) {
                        $timeStr = date('H:i', strtotime($convo['last_message_at']));
                        if (date('Y-m-d', strtotime($convo['last_message_at'])) !== date('Y-m-d')) {
                            $timeStr = date('d M', strtotime($convo['last_message_at']));
                        }
                    }

                    $lastMsg = esc($convo['last_message_preview'] ?? 'Belum ada riwayat pesan');

                    // Determine category badge class & text
                    $cat = $convo['category'] ?? 'general';
                    if ($cat === 'technical') {
                        $catBadgeClass = 'badge-tech';
                        $catText = 'Technical';
                    } elseif ($cat === 'accounting') {
                        $catBadgeClass = 'badge-acct';
                        $catText = 'Accounting';
                    } else {
                        $catBadgeClass = 'badge-gen';
                        $catText = 'General';
                    }
                    ?>
                    <?php
                    // Menentukan foto profil asli jika ada
                    $avatarUrl = '';
                    if (!empty($convo['client_avatar'])) {
                        if ($isTukang) {
                            $avatarUrl = base_url('uploads/tukang/' . $convo['client_avatar']);
                        } else {
                            if (strpos($convo['client_avatar'], 'http') === 0) {
                                $avatarUrl = $convo['client_avatar'];
                            } else {
                                $avatarUrl = base_url('uploads/profile/' . $convo['client_avatar']);
                            }
                        }
                    } else {
                        $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($convo['client_name'] ?? 'User') . "&background=random&color=fff";
                    }
                    ?>
                    <li class="d-flex chat-list-user p-3 align-items-center border-bottom" data-id="<?= $convo['id'] ?>"
                        data-chat-type="cs"
                        data-name="<?= esc($convo['client_name']) ?>" data-type="<?= esc($convo['client_type']) ?>"
                        data-avatar="<?= esc($convo['client_avatar'] ?? '') ?>"
                        data-status="<?= esc($convo['status'] ?? 'open') ?>"
                        data-title="<?= esc($convo['title'] ?? 'Obrolan') ?>" data-category="<?= esc($cat) ?>"
                        data-supplier-id="<?= $convo['supplier_id'] ?? '' ?>"
                        data-supplier-name="<?= esc($convo['supplier_name'] ?? '') ?>">
                        <img class="me-3 rounded-circle border shadow-sm" width="48" height="48" src="<?= $avatarUrl ?>"
                            alt="avatar">
                        <div class="flex-grow-1" style="overflow: hidden;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mt-0 mb-0 fw-bold text-truncate text-dark chat-client-name"
                                    style="font-size: 0.92rem; max-width: 65%;">
                                    <?= esc($convo['client_name']) ?>
                                    <?php if (($convo['status'] ?? 'open') === 'closed'): ?>
                                        <i class="fas fa-lock text-danger ms-1" style="font-size: 0.75rem;" title="Tertutup"></i>
                                    <?php endif; ?>
                                </h6>
                                <span class="text-small text-muted chat-item-time"
                                    style="font-size: 0.72rem; font-weight: 500;"><?= $timeStr ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-small text-muted text-truncate mb-0 chat-item-preview"
                                    style="max-width: 70%; font-size: 0.8rem;"><?= $lastMsg ?></p>
                                <div class="d-flex align-items-center" style="gap: 4px;">
                                    <?php $unreadCount = intval($convo['unread_by_admin_count'] ?? 0); ?>
                                    <span class="unread-badge me-2" id="unread-badge-<?= $convo['id'] ?>" <?= $unreadCount > 0 ? '' : 'style="display: none;"' ?>><?= $unreadCount ?></span>
                                    <span class="badge <?= $catBadgeClass ?> px-2 py-1 text-uppercase"
                                        style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;"><?= $catText ?></span>
                                    <?php if (!empty($convo['supplier_id'])): ?>
                                        <span class="badge px-2 py-1 text-uppercase"
                                            style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px; background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">Monitored</span>
                                    <?php else: ?>
                                        <span class="badge px-2 py-1 text-uppercase"
                                            style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px; background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3;">CS
                                            Active</span>
                                        <span class="badge <?= $badgeClass ?> px-2 py-1 text-uppercase"
                                            style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;"><?= $badgeText ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Project Conversations -->
            <?php if (!empty($projectConversations)): ?>
                <?php foreach ($projectConversations as $pConvo): ?>
                    <?php
                    $pTimeStr = '';
                    if (!empty($pConvo['last_message_at'])) {
                        $pTimeStr = date('H:i', strtotime($pConvo['last_message_at']));
                        if (date('Y-m-d', strtotime($pConvo['last_message_at'])) !== date('Y-m-d')) {
                            $pTimeStr = date('d M', strtotime($pConvo['last_message_at']));
                        }
                    }
                    $pLastMsg = esc($pConvo['last_message_preview'] ?? 'Belum ada riwayat pesan');
                    $pUnreadCount = intval($pConvo['unread_by_admin_count'] ?? 0);

                    // Avatar klien proyek
                    $pAvatarUrl = '';
                    if (!empty($pConvo['client_avatar'])) {
                        if (strpos($pConvo['client_avatar'], 'http') === 0) {
                            $pAvatarUrl = $pConvo['client_avatar'];
                        } else {
                            $pAvatarUrl = base_url('uploads/profile/' . $pConvo['client_avatar']);
                        }
                    } else {
                        $pAvatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($pConvo['client_name'] ?? 'Klien') . "&background=6366f1&color=fff";
                    }

                    // Badge tipe proyek
                    $projectType = $pConvo['project_type'] ?? 'design';
                    if ($projectType === 'design') {
                        $projectTypeBadge = 'Desain';
                        $projectTypeBadgeStyle = 'background-color: #ede9fe; color: #7c3aed; border: 1px solid #ddd6fe;';
                    } elseif ($projectType === 'construction') {
                        $projectTypeBadge = 'Konstruksi';
                        $projectTypeBadgeStyle = 'background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a;';
                    } else {
                        $projectTypeBadge = 'Renovasi';
                        $projectTypeBadgeStyle = 'background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;';
                    }
                    ?>
                    <li class="d-flex chat-list-user p-3 align-items-center border-bottom"
                        data-id="<?= $pConvo['id'] ?>"
                        data-chat-type="project"
                        data-name="<?= esc($pConvo['client_name'] ?? 'Klien') ?>"
                        data-type="client"
                        data-avatar="<?= esc($pConvo['client_avatar'] ?? '') ?>"
                        data-status="<?= esc($pConvo['status'] ?? 'open') ?>"
                        data-title="<?= esc($pConvo['project_name'] ?? 'Proyek') ?>"
                        data-category="project"
                        data-project-type="<?= esc($projectType) ?>"
                        data-project-id="<?= esc($pConvo['project_id'] ?? '') ?>"
                        data-project-name="<?= esc($pConvo['project_name'] ?? '') ?>"
                        data-supplier-id="">
                        <img class="me-3 rounded-circle border shadow-sm" width="48" height="48" src="<?= $pAvatarUrl ?>"
                            alt="avatar">
                        <div class="flex-grow-1" style="overflow: hidden;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mt-0 mb-0 fw-bold text-truncate text-dark chat-client-name"
                                    style="font-size: 0.92rem; max-width: 65%;">
                                    <?= esc($pConvo['client_name'] ?? 'Klien') ?>
                                    <?php if (($pConvo['status'] ?? 'open') === 'closed'): ?>
                                        <i class="fas fa-lock text-danger ms-1" style="font-size: 0.75rem;" title="Tertutup"></i>
                                    <?php endif; ?>
                                </h6>
                                <span class="text-small text-muted chat-item-time"
                                    style="font-size: 0.72rem; font-weight: 500;"><?= $pTimeStr ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-small text-muted text-truncate mb-0 chat-item-preview"
                                    style="max-width: 70%; font-size: 0.8rem;"><?= $pLastMsg ?></p>
                                <div class="d-flex align-items-center" style="gap: 4px;">
                                    <span class="unread-badge me-2" id="unread-badge-project-<?= $pConvo['id'] ?>" <?= $pUnreadCount > 0 ? '' : 'style="display: none;"' ?>><?= $pUnreadCount ?></span>
                                    <span class="badge px-2 py-1 text-uppercase"
                                        style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px; background-color: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe;">Proyek</span>
                                    <span class="badge px-2 py-1 text-uppercase"
                                        style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px; <?= $projectTypeBadgeStyle ?>"><?= $projectTypeBadge ?></span>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (empty($conversations) && empty($projectConversations)): ?>
                <li class="text-center p-5 text-muted">
                    <i class="fas fa-inbox fa-2x mb-3 text-light"></i>
                    <p class="mb-0">Belum ada percakapan masuk.</p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>