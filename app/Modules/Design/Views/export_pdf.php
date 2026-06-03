<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }

        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
            text-transform: uppercase;
        }
        .company-address {
            font-size: 10px;
            color: #666;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .report-meta {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Tabel Data */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th, .table-data td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            vertical-align: middle;
        }
        .table-data th {
            background-color: #f0f6ff;
            color: #0d6efd;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            text-align: center;
        }
        .table-data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }

        /* Badge Status */
        .status-badge {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0d6efd;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <table class="kop-table">
        <tr>
            <td class="text-left" style="width: 70%;">
                <div class="company-name">PT. Pendowo Tiga Construction</div>
                <div class="company-address">
                    Jalan Ki Ageng Getas Pendowo, RT 02 RW 12, Kel. Kuripan, Kec. Purwodadi, Kab. Grobogan<br>
                    Email: info@pendowotigaconstruction.com | Web: www.pendowotigaconstruction.com
                </div>
            </td>
            <td class="text-right" style="width: 30%; vertical-align: bottom; font-size: 10px; color: #555;">
                Tanggal Cetak: <?= tanggal_indo($tanggal_cetak) ?>
            </td>
        </tr>
    </table>

    <?php
    $total_rab = 0;
    $total_invoice = 0;
    $total_anggaran = 0;
    if (!empty($requests)) {
        foreach ($requests as $r) {
            $total_rab += (int) ($r['survey_fee'] ?? 0);
            $total_invoice += (int) ($r['total_invoice'] ?? 0);
            $total_anggaran += (int) ($r['total_payment'] ?? 0);
        }
    }
    ?>

    <!-- Judul -->
    <div class="report-title">Laporan Permintaan Desain Proyek</div>
    <div class="report-meta">Total Proyek: <?= count($requests) ?> Permintaan</div>

    <!-- Ringkasan Anggaran -->
    <table style="width: 100%; margin-bottom: 20px; border-spacing: 15px; border-collapse: separate; margin-left: -15px; margin-right: -15px;">
        <tr>
            <td style="width: 33.33%; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #6c757d; font-weight: bold; margin-bottom: 5px;">Total RAB (Survey)</div>
                <div style="font-size: 16px; font-weight: bold; color: #333;">Rp <?= number_format($total_rab, 0, ',', '.') ?></div>
            </td>
            <td style="width: 33.33%; background-color: #f0f6ff; border: 1px solid #bce0fd; border-radius: 8px; padding: 12px; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #0d6efd; font-weight: bold; margin-bottom: 5px;">Total Invoice Terbit</div>
                <div style="font-size: 16px; font-weight: bold; color: #0d6efd;">Rp <?= number_format($total_invoice, 0, ',', '.') ?></div>
            </td>
            <td style="width: 33.33%; background-color: #e6f9ed; border: 1px solid #b7ebc6; border-radius: 8px; padding: 12px; text-align: center;">
                <div style="font-size: 10px; text-transform: uppercase; color: #198754; font-weight: bold; margin-bottom: 5px;">Total Anggaran Kontrak</div>
                <div style="font-size: 16px; font-weight: bold; color: #198754;">Rp <?= number_format($total_anggaran, 0, ',', '.') ?></div>
            </td>
        </tr>
    </table>

    <!-- Data -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Nama Client</th>
                <th style="width: 10%;">Tanggal Pengajuan</th>
                <th style="width: 12%;">Konsep Desain</th>
                <th style="width: 14%;">Estimasi Pengerjaan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 11%;">RAB (Survey)</th>
                <th style="width: 11%;">Total Invoice</th>
                <th style="width: 12%;">Total Anggaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $key => $row): ?>
                    <tr>
                        <td class="text-center"><?= $key + 1 ?></td>
                        <td><span class="fw-bold"><?= esc($row['full_name']) ?></span></td>
                        <td class="text-center"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td class="text-center fw-bold" style="color: #0d6efd;"><?= esc($row['design_concept']) ?></td>
                        <td class="text-center">
                            <?php if (!empty($row['start_date']) && !empty($row['target_date'])): ?>
                                <?= date('d M Y', strtotime($row['start_date'])) ?> - <?= date('d M Y', strtotime($row['target_date'])) ?>
                            <?php else: ?>
                                <span style="font-style: italic; color: #888;">Belum dijadwalkan</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <span class="status-badge">
                                <?= esc(str_replace('_', ' ', $row['status'])) ?>
                            </span>
                        </td>
                        <td class="text-right">Rp <?= number_format($row['survey_fee'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_invoice'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-right">Rp <?= number_format($row['total_payment'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <!-- Row Total -->
                <tr style="background-color: #f0f6ff; font-weight: bold;">
                    <td colspan="6" class="text-right" style="padding: 10px;">GRAND TOTAL:</td>
                    <td class="text-right">Rp <?= number_format($total_rab, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_invoice, 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($total_anggaran, 0, ',', '.') ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px; color: #888;">Tidak ada data permohonan desain.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
