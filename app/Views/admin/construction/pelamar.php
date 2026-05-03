<style>
    /* ===== PELAMAR PREMIUM STYLES ===== */
    .pelamar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .pelamar-title {
        font-size: 1rem;
        font-weight: 700;
        color: #34395e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .pelamar-count-badge {
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        border-radius: 50px;
        padding: 3px 12px;
        font-size: 0.78rem;
        font-weight: 700;
        animation: countPulse 2s ease-in-out infinite;
    }

    @keyframes countPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(103, 119, 239, 0.4);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(103, 119, 239, 0);
        }
    }

    /* ===== APPLICANT CARD ===== */
    .applicant-card {
        border: 1px solid #f0f2f5;
        border-radius: 14px;
        padding: 16px 20px;
        background: #fff;
        margin-bottom: 14px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .applicant-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #e9ecef;
        border-radius: 4px 0 0 4px;
        transition: background 0.25s ease;
    }

    .applicant-card:hover {
        box-shadow: 0 8px 28px rgba(103, 119, 239, 0.12);
        transform: translateY(-2px);
        border-color: #d5daff;
    }

    .applicant-card:hover::before {
        background: #6777ef;
    }

    .applicant-card.status-approved::before {
        background: #47c363;
    }

    .applicant-card.status-rejected::before {
        background: #fc544b;
    }

    .applicant-card.status-processing::before {
        background: #ffa426;
    }

    .applicant-card.status-siapkerja::before {
        background: #47c363;
    }

    /* ===== AVATAR ===== */
    .applicant-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6777ef, #9fa8ff);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.3);
    }

    /* ===== STATUS PILLS ===== */
    .status-pill-sm {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 50px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .status-pill-sm .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.75;
    }

    .pill-siapkerja {
        background: #d1e7dd;
        color: #0a5c36;
    }

    .pill-ditolak {
        background: #f8d7da;
        color: #842029;
    }

    .pill-proses {
        background: #fff3cd;
        color: #7d5a00;
    }

    .pill-berkas {
        background: #cff4fc;
        color: #055160;
    }

    .pill-default {
        background: #e2e3e5;
        color: #41464b;
    }

    /* ===== SELECT INLINE ===== */
    .status-select-inline {
        border: 1.5px solid #e0e4ff;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #34395e;
        background: #f8f9ff;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s ease;
        min-width: 160px;
    }

    .status-select-inline:focus {
        border-color: #6777ef;
        box-shadow: 0 0 0 3px rgba(103, 119, 239, 0.15);
    }

    .btn-update-status {
        background: linear-gradient(135deg, #6777ef, #7e8ef5);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 6px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-update-status:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(103, 119, 239, 0.4);
        color: #fff;
    }

    /* ===== EMPTY STATE ===== */
    .empty-pelamar {
        text-align: center;
        padding: 48px 24px;
        animation: fadeInUp 0.5s ease;
    }

    .empty-pelamar .empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f0f3ff, #e0e4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem;
        color: #6777ef;
        opacity: 0.7;
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .applicant-card {
        animation: fadeInUp 0.35s ease both;
    }

    .applicant-card:nth-child(1) {
        animation-delay: 0.05s;
    }

    .applicant-card:nth-child(2) {
        animation-delay: 0.10s;
    }

    .applicant-card:nth-child(3) {
        animation-delay: 0.15s;
    }

    .applicant-card:nth-child(4) {
        animation-delay: 0.20s;
    }

    .applicant-card:nth-child(5) {
        animation-delay: 0.25s;
    }

    /* ===== MOBILE ===== */
    @media (max-width: 767px) {
        .pelamar-header {
            flex-wrap: wrap;
            gap: 10px;
        }

        .applicant-card-body {
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        .applicant-actions {
            width: 100%;
            border-top: 1px solid #f0f2f5;
            padding-top: 12px;
            margin-top: 8px;
        }

        .applicant-actions form {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
        }

        .applicant-actions .status-select-inline {
            width: 100%;
        }

        .applicant-actions .btn-update-status {
            width: 100%;
            text-align: center;
            padding: 8px 0;
        }
    }
</style>

<?php
// Helper mapping
$statusConfig = [
    'Siap Kerja' => ['pill' => 'pill-siapkerja', 'card' => 'status-siapkerja', 'icon' => 'fas fa-check-circle'],
    'Ditolak' => ['pill' => 'pill-ditolak', 'card' => 'status-rejected', 'icon' => 'fas fa-times-circle'],
    'Proses Test' => ['pill' => 'pill-proses', 'card' => 'status-processing', 'icon' => 'fas fa-flask'],
    'Proses Aktivasi' => ['pill' => 'pill-proses', 'card' => 'status-processing', 'icon' => 'fas fa-user-check'],
    'Berkas Diproses' => ['pill' => 'pill-berkas', 'card' => 'status-processing', 'icon' => 'fas fa-folder-open'],
    'Approved' => ['pill' => 'pill-siapkerja', 'card' => 'status-approved', 'icon' => 'fas fa-thumbs-up'],
];

$totalApplicants = !empty($applicants) ? count($applicants) : 0;
?>

<div class="pelamar-header">
    <p class="pelamar-title">
        <i class="fas fa-hard-hat text-primary"></i>
        Daftar Pelamar Proyek
    </p>
    <span class="pelamar-count-badge">
        <?= $totalApplicants ?> Pelamar
    </span>
</div>

<?php if (!empty($applicants)): ?>
    <?php foreach ($applicants as $idx => $app):
        $statusKey = $app['status'] ?? '';
        $cfg = $statusConfig[$statusKey] ?? ['pill' => 'pill-default', 'card' => '', 'icon' => 'fas fa-circle'];
        $initials = strtoupper(substr($app['tukang_name'] ?? 'T', 0, 1));
        $whatsapp = preg_replace('/[^0-9]/', '', $app['phone'] ?? '');
        ?>
        <div class="applicant-card <?= $cfg['card'] ?>">
            <div class="d-flex align-items-center gap-3 flex-wrap applicant-card-body">

                <!-- Avatar -->
                <div class="applicant-avatar flex-shrink-0"><?= $initials ?></div>

                <!-- Info (kiri) -->
                <div class="flex-grow-1 d-flex flex-column gap-1">
                    <!-- Nama & status pill -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="font-weight-bold text-dark"
                            style="font-size:0.95rem;"><?= esc($app['tukang_name'] ?? '-') ?></span>
                        <span class="status-pill-sm <?= $cfg['pill'] ?>">
                            <i class="<?= $cfg['icon'] ?>"></i>
                            <?= esc($statusKey ?: 'Belum ada status') ?>
                        </span>
                    </div>
                    <!-- Detail: HP + Chat WA berdampingan, spesialisasi, tanggal -->
                    <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                        <?php if (!empty($app['phone'])): ?>
                            <span class="text-muted" style="font-size:0.8rem;">
                                <i class="fas fa-phone-alt mr-1 text-success"></i>
                                <?= esc($app['phone']) ?>
                            </span>
                            <?php if (!empty($whatsapp)): ?>
                                <a href="https://wa.me/<?= $whatsapp ?>" target="_blank" class="btn btn-sm btn-success px-2 py-0"
                                    style="border-radius:6px; font-size:0.78rem; line-height:1.6;" title="Chat WhatsApp">
                                    <i class="fab fa-whatsapp mr-1"></i>Chat WA
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($app['specialization'])): ?>
                            <span class="text-muted" style="font-size:0.8rem;">
                                <i class="fas fa-tools mr-1 text-primary"></i>
                                <?= esc($app['specialization']) ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($app['created_at'])): ?>
                            <span class="text-muted" style="font-size:0.78rem;">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                <?= date('d M Y', strtotime($app['created_at'])) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions (kanan): select + submit -->
                <div class="applicant-actions d-flex align-items-center gap-2 flex-shrink-0">
                    <form action="<?= base_url('admin/construction/update_applicant_status') ?>" method="post"
                        class="d-flex align-items-center gap-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $app['id'] ?>">
                        <select name="status" class="status-select-inline">
                            <?php $statuses = ['Berkas Diproses', 'Proses Test', 'Proses Aktivasi', 'Siap Kerja', 'Ditolak']; ?>
                            <?php foreach ($statuses as $s): ?>
                                <option value="<?= $s ?>" <?= $statusKey === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-update-status ladda-button" data-style="zoom-in">
                            <i class="fas fa-check mr-1"></i>Simpan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <div class="empty-pelamar">
        <div class="empty-icon">
            <i class="fas fa-user-hard-hat"></i>
        </div>
        <h6 class="font-weight-bold text-dark mb-2">Belum Ada Pelamar</h6>
        <p class="text-muted mb-0" style="font-size:0.85rem;">
            Belum ada tukang yang mendaftar untuk proyek ini.<br>
            Lowongan proyek akan muncul setelah tahap RAB selesai.
        </p>
    </div>
<?php endif; ?>