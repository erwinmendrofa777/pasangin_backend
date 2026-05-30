<?php
$invoiceList = $invoice_list ?? [];
$totalPaid = array_sum(array_map(fn($i) => $i['status'] == 'PAID' ? (float) $i['amount'] : 0, $invoiceList));
$grandTotal = array_sum(array_column($list_tagihan, 'total_price'));
$paidPct = $grandTotal > 0 ? min(100, round($totalPaid / $grandTotal * 100)) : 0;
$countUnpaid = count(array_filter($invoiceList, fn($i) => $i['status'] != 'PAID'));
$isLocked = !empty($rab_list) && $rab_list[0]['is_locked'] == 1;

// Group RAB by roman + group_name
$groupedRabs = [];
foreach ($list_tagihan as $r) {
    $key = $r['roman_number'] . '_' . $r['group_name'];
    if (!isset($groupedRabs[$key])) {
        $groupedRabs[$key] = [
            'label' => $r['roman_number'] . '. ' . $r['group_name'],
            'total' => 0,
            'items' => []
        ];
    }
    $groupedRabs[$key]['total'] += (float) $r['total_price'];
    $groupedRabs[$key]['items'][] = $r;
}

// Kumpulkan deskripsi tagihan yang sudah dibuat (untuk cegah duplikat)
$invoicedDescs = array_map('strtolower', array_column($invoiceList, 'description'));
?>

<div class="animate-up">
    <?php if ($isLocked): ?>
        <div class="card bg-primary text-white border-0 mb-4 shadow-sm">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <small class="text-white-50 text-uppercase" style="letter-spacing:.8px;font-size:11px;">Nilai
                            Kontrak Renovasi</small>
                        <h3 class="font-weight-bold mb-1">Rp <?= number_format($grandTotal) ?></h3>
                        <small class="text-white-50"><?= count($list_tagihan) ?> item pekerjaan &bull; RAB terkunci</small>
                        <div class="mt-3">
                            <small class="text-white-50"><?= $paidPct ?>% terbayar</small>
                            <div class="progress mt-1"
                                style="height:6px;background:rgba(255,255,255,.2);border-radius:10px;">
                                <div class="progress-bar bg-white shadow-sm"
                                    style="width:<?= $paidPct ?>%;border-radius:10px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 mt-3 mt-md-0">
                        <div class="row text-center">
                            <div class="col-12 col-sm-4 px-2 mb-2 mb-sm-0">
                                <div class="py-2 px-1 text-center"
                                    style="background:rgba(255,255,255,.15);border-radius:10px;">
                                    <div class="font-weight-bold" style="font-size:1.1rem;">Rp
                                        <?= number_format($totalPaid) ?>
                                    </div>
                                    <small class="text-white-50 d-block" style="font-size:0.75rem;">Terbayar</small>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 px-2">
                                <div class="py-2 px-1 text-center"
                                    style="background:rgba(255,255,255,.15);border-radius:10px;">
                                    <div class="font-weight-bold" style="font-size:1.1rem;"><?= $countUnpaid ?></div>
                                    <small class="text-white-50 d-block" style="font-size:0.75rem;">Tagihan Aktif</small>
                                </div>
                            </div>
                            <div class="col-6 col-sm-4 px-2">
                                <div class="py-2 px-1 text-center"
                                    style="background:rgba(255,255,255,.15);border-radius:10px;">
                                    <div class="font-weight-bold" style="font-size:1.1rem;"><?= count($invoiceList) ?></div>
                                    <small class="text-white-50 d-block" style="font-size:0.75rem;">Total Tagihan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning d-flex align-items-start mb-4 shadow-sm">
            <div class="mr-3">
                <i class="fas fa-lock fa-lg"></i>
            </div>
            <div>
                <strong>RAB Belum Dikunci</strong><br>
                <small>Kunci RAB di tab <strong>Kelola RAB</strong> terlebih dahulu untuk mengaktifkan referensi nilai
                    kontrak. Anda tetap bisa membuat tagihan manual.</small>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- ===== DAFTAR TAGIHAN ===== -->
        <div class="col-md-12">
            <h6 class="font-weight-bold text-primary mb-3">
                <i class="fas fa-receipt mr-1"></i> Tagihan Terkirim
                <span class="badge badge-primary ml-1 px-2"><?= count($invoiceList) ?></span>
            </h6>

            <?php if (!empty($invoiceList)): ?>
                <div class="table-responsive mb-4 shadow-sm" style="border-radius: 8px; overflow: hidden;">
                    <table class="table table-hover table-bordered align-middle text-nowrap mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="py-3">Keterangan</th>
                                <th class="text-center py-3">Nominal</th>
                                <th class="text-center py-3">Jatuh Tempo</th>
                                <th class="text-center py-3">Status</th>
                                <th class="text-center py-3" style="width:60px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoiceList as $inv):
                                $originalAmount = (int) $inv['amount'];
                                $discount = (int) ($inv['discount_nominal'] ?? 0);
                                $finalAmount = max(0, $originalAmount - $discount);
                                ?>
                                <tr>
                                    <td class="font-weight-bold"><?= esc($inv['description']) ?></td>
                                    <td class="text-center text-primary font-weight-bold">
                                        <div class="d-flex flex-column align-items-center">
                                            <?php if ($discount > 0): ?>
                                                <span class="text-muted text-decoration-line-through" style="font-size: 0.75rem;">Rp
                                                    <?= number_format($originalAmount, 0, ',', '.') ?></span>
                                                <span class="fw-bold">Rp <?= number_format($finalAmount, 0, ',', '.') ?></span>
                                                <small class="text-success fw-bold" style="font-size: 0.7rem;"><i
                                                        class="fas fa-tag me-1"></i>Hemat Rp
                                                    <?= number_format($discount, 0, ',', '.') ?></small>
                                            <?php else: ?>
                                                <span>Rp <?= number_format($originalAmount, 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center text-muted small">
                                        <?= $inv['due_date'] ? date('d M Y', strtotime($inv['due_date'])) : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($inv['status'] == 'PAID'): ?>
                                            <span class="badge badge-success px-3 py-2" style="border-radius: 6px;">LUNAS</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-white px-3 py-2" style="border-radius: 6px;">BELUM
                                                BAYAR</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/renovation/delete_invoice/' . $inv['id'] . '/' . $renovation['id']) ?>"
                                            class="btn btn-sm btn-danger ladda-button shadow-sm" data-style="zoom-in"
                                            onclick="if(confirm('Hapus tagihan ini?')){Ladda.create(this).start();return true;}return false;">
                                            <span class="ladda-label"><i class="fas fa-trash"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-5 mb-4 border rounded shadow-sm bg-white">
                    <i class="fas fa-file-invoice fa-3x mb-3 d-block text-primary opacity-25"></i>
                    <p class="mb-0">Belum ada tagihan. Klik item RAB di bawah untuk membuat tagihan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- ===== RINCIAN RAB ===== -->
        <div class="col-md-8">
            <h6 class="font-weight-bold text-primary mb-3">
                <i class="fas fa-list-alt mr-1"></i> Rincian Nilai Kontrak (RAB)
            </h6>

            <?php if ($isLocked): ?>
                <?php $gi = 0;
                foreach ($groupedRabs as $group):
                    $gi++; ?>
                    <div class="card mb-2 border-primary shadow-sm">
                        <div class="card-header bg-primary text-white py-2 px-3 d-flex justify-content-between align-items-center"
                            style="cursor:pointer;" onclick="toggleRabGroup('rabg<?= $gi ?>', this)">
                            <span class="font-weight-bold small"><?= esc($group['label']) ?></span>
                            <span class="small font-weight-bold">
                                Rp <?= number_format($group['total']) ?>
                                <i class="fas fa-chevron-down ml-2" style="transition:transform .25s;"></i>
                            </span>
                        </div>
                        <div id="rabg<?= $gi ?>" class="collapse">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0 text-nowrap">
                                    <thead class="thead-light">
                                        <tr class="small text-uppercase">
                                            <th class="py-2 pl-3">Pekerjaan</th>
                                            <th class="text-center py-2">Vol</th>
                                            <th class="text-center py-2">Sat</th>
                                            <th class="text-right py-2">Harga</th>
                                            <th class="text-right py-2">Total</th>
                                            <th class="py-2 pr-3" style="width:40px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($group['items'] as $rab):
                                            $desc = trim(($rab['sub_group_name'] ? $rab['sub_group_name'] . ' — ' : '') . $rab['activity_name']);
                                            $alreadyBilled = in_array(strtolower($desc), $invoicedDescs);
                                            ?>
                                            <?php if ($alreadyBilled): ?>
                                                <tr class="table-secondary" style="opacity:.65;">
                                                    <td class="pl-3">
                                                        <span class="font-weight-500 small"><?= esc($rab['activity_name']) ?></span>
                                                        <span class="badge badge-success ml-1" style="font-size:9px;">DITAGIHKAN</span>
                                                    </td>
                                                    <td class="text-center text-muted small">
                                                        <?= number_format((float) $rab['volume'], 1) ?>
                                                    </td>
                                                    <td class="text-center text-muted small"><?= esc($rab['unit']) ?></td>
                                                    <td class="text-right text-muted font-weight-bold small">Rp
                                                        <?= number_format((int) $rab['current_unit_price']) ?>
                                                    </td>
                                                    <td class="text-right text-muted font-weight-bold small">Rp
                                                        <?= number_format((int) $rab['total_price']) ?>
                                                    </td>
                                                    <td class="text-center pr-3 text-success"><i class="fas fa-check-circle small"></i>
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <tr style="cursor:pointer;" class="rab-click-row"
                                                    onclick="fillInvoiceForm('<?= addslashes($desc) ?>', <?= (int) $rab['total_price'] ?>, this)">
                                                    <td class="pl-3">
                                                        <span class="font-weight-500 small"><?= esc($rab['activity_name']) ?></span>
                                                    </td>
                                                    <td class="text-center small"><?= number_format((float) $rab['volume'], 1) ?></td>
                                                    <td class="text-center text-muted small"><?= esc($rab['unit']) ?></td>
                                                    <td class="text-right text-primary font-weight-bold small">Rp
                                                        <?= number_format((int) $rab['total_price']) ?>
                                                    </td>
                                                    <td class="text-center pr-3 text-primary"><i class="fas fa-plus-circle small"></i>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="alert alert-primary d-flex justify-content-between align-items-center mt-3 shadow-sm border-0">
                    <span class="font-weight-bold text-uppercase small">Total Nilai Kontrak Renovasi</span>
                    <span class="font-weight-bold" style="font-size:1.1rem;">Rp <?= number_format($grandTotal) ?></span>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm text-center p-5 bg-white" style="border-radius: 12px;">
                    <i class="fas fa-lock fa-3x text-warning opacity-25 mb-3"></i>
                    <h6 class="font-weight-bold">RAB Belum Terkunci</h6>
                    <p class="text-muted small mb-0">Silakan kunci RAB di tab Kelola RAB untuk melihat rincian.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- ===== FORM TAGIHAN ===== -->
        <div class="col-md-4">
            <div class="card border-primary shadow-lg mt-3 mt-md-0"
                style="position:sticky; top:20px; border-radius: 12px; overflow: hidden;">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="mb-0 font-weight-bold"><i class="fas fa-plus-circle mr-1"></i> Buat Tagihan Baru</h6>
                </div>
                <div class="card-body p-4">
                    <div id="selectedRabInfo" class="alert alert-info py-2 px-3 small mb-3 shadow-sm"
                        style="display:none; border-radius: 8px;">
                        <i class="fas fa-check-circle mr-1"></i> <strong>Referensi:</strong>
                        <div id="selectedRabName" class="mt-1 font-weight-bold"></div>
                    </div>

                    <form action="<?= base_url('admin/renovation/create_invoice') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="renovation_id" value="<?= $renovation['id'] ?>">

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Keterangan Tagihan</label>
                            <input type="text" id="invoice_description" name="description" class="form-control"
                                placeholder="Contoh: Termin 1 / Material..." required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">Nominal Tagihan</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span
                                        class="input-group-text bg-primary text-white border-primary font-weight-bold">Rp</span>
                                </div>
                                <input type="text" id="invoice_amount_visible" class="form-control font-weight-bold"
                                    required onkeyup="formatCurrencyInput(this)" placeholder="0">
                            </div>
                            <input type="hidden" id="invoice_amount" name="amount" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold">Batas Waktu Pembayaran</label>
                            <input type="date" name="due_date" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg ladda-button shadow-primary"
                            data-style="zoom-in" style="font-weight: 700;">
                            <span class="ladda-label"><i class="fas fa-paper-plane mr-2"></i> Kirim Tagihan</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-up {
        animation: pembayaranFadeUp 0.4s ease both;
    }

    @keyframes pembayaranFadeUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .rab-click-row:hover {
        background-color: #f0f7ff !important;
    }

    .rab-click-row.rab-selected {
        background-color: #cfe2ff !important;
        border-left: 4px solid #6777ef !important;
    }

    .shadow-primary {
        box-shadow: 0 4px 15px rgba(103, 119, 239, 0.4);
    }
</style>

<script>
    function fillInvoiceForm(description, amount, el) {
        $('#invoice_description').val(description);
        $('#invoice_amount').val(amount);
        $('#invoice_amount_visible').val(amount.toLocaleString('id-ID'));
        document.querySelectorAll('.rab-click-row').forEach(r => r.classList.remove('rab-selected'));
        if (el) el.classList.add('rab-selected');
        $('#selectedRabInfo').fadeIn(250);
        $('#selectedRabName').text(description);
        const target = document.getElementById('invoice_description');
        target.focus();
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function toggleRabGroup(id, headerEl) {
        const el = document.getElementById(id);
        const icon = headerEl.querySelector('i.fa-chevron-down');
        const isOpen = el.classList.contains('show');
        if (isOpen) {
            $(el).collapse('hide');
            if (icon) icon.style.transform = 'rotate(0deg)';
        } else {
            $(el).collapse('show');
            if (icon) icon.style.transform = 'rotate(180deg)';
        }
    }

    function clearInvoiceForm() {
        $('#invoice_description').val('');
        $('#invoice_amount').val('');
        $('#invoice_amount_visible').val('');
        document.querySelectorAll('.rab-click-row').forEach(r => r.classList.remove('rab-selected'));
        $('#selectedRabInfo').fadeOut(250);
    }

    function formatCurrencyInput(el) {
        let raw = el.value.replace(/\D/g, '');
        el.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
        document.getElementById('invoice_amount').value = raw;
    }
</script>