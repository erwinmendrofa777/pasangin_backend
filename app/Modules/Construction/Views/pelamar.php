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