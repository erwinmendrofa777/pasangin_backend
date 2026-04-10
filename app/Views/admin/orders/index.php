<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Pesanan
<?= $this->endSection() ?>

<?= $this->section('page_title') ?>
Manajemen Pesanan
<?= $this->endSection() ?>

<?= $this->section('style') ?>
<style>
    /* Custom styling for search input */
    #searchInput {
        border-radius: 5px 0 0 5px;
        border-right: none;
    }
    
    #searchInput:focus {
        box-shadow: none;
        border-color: #6777ef;
    }
    
    .input-group-text {
        border-radius: 0 5px 5px 0;
        border-left: none;
        background-color: #6777ef;
        color: white;
        border-color: #6777ef;
    }
    
    /* Highlight search results */
    mark {
        background-color: #fffbdd;
        color: #856404;
        padding: 1px 2px;
        border-radius: 2px;
    }
    
    /* DataTables custom styling */
    .dataTables_length select {
        background-color: #fff;
        border: 1px solid #e4e6fc;
        border-radius: 5px;
        padding: 5px 10px;
    }
    
    .dataTables_info {
        color: #6c757d;
        font-size: 14px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header-action {
            flex-direction: column;
            gap: 10px;
        }
        
        .card-header-action .input-group {
            width: 100% !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">

    <div class="card shadow">
      <div class="card-header">
        <h4>Daftar Semua Pesanan</h4>
        <div class="card-header-action d-flex gap-2">
            <div class="input-group" style="width: 300px;">
                <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, email, telepon, role...">
                <div class="input-group-append">
                    <span class="input-group-text" style="height: 32px;">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
            </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="table-responsive">
          <table class="table table-striped table-md table-hover" id="table-1">
            <thead class="text-center">
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">ID Order</th>
                <th class="text-center">Pelanggan</th>
                <th class="text-center">Item & Supplier</th>
                <th class="text-center">Total Harga</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($orders)) : ?>
                <?php foreach ($orders as $key => $order) : ?>
                  <tr class="text-center align-middle">
                    <td><?= $key + 1 ?></td>
                    <td><strong><?= esc($order['order_id']) ?></strong></td>
                    <td>
                        <?= esc($order['recipient_name']) ?><br>
                        <small class="text-muted"><?= esc($order['recipient_phone']) ?></small>
                    </td>
                    <td>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($order['items'] as $item) : ?>
                                <li class="mb-2 pb-1 border-bottom">
                                    <span class="badge badge-info mb-1" style="font-size: 9px;"><?= esc($item['supplier_name']) ?></span><br>
                                    <small><strong><?= esc($item['product_name']) ?></strong> (x<?= $item['quantity'] ?>)</small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td><strong>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></strong></td>
                    <td>
                      <?php 
                        $statusClass = '';
                        if (in_array($order['status'], ['PAID', 'SETTLEMENT', 'SHIPPED', 'COMPLETED'])) $statusClass = 'badge-success';
                        if (in_array($order['status'], ['CANCELLED', 'EXPIRED'])) $statusClass = 'badge-danger';
                        if (in_array($order['status'], ['PENDING', 'UNPAID'])) $statusClass = 'text-bg-warning text-white';
                      ?>
                      <div class="badge <?= $statusClass ?>"><?= esc($order['status']) ?></div>
                    </td>
                    <td><?= date('d M Y', strtotime($order['created_at'])) ?><br><small><?= date('H:i', strtotime($order['created_at'])) ?></small></td>
                    <td class="text-center">
                      <form action="<?= site_url('admin/orders/update/' . $order['id']) ?>" method="POST" class="d-inline">
                        <div class="input-group">
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="PENDING" <?= $order['status'] == 'PENDING' ? 'selected' : '' ?>>PENDING</option>
                                <option value="UNPAID" <?= $order['status'] == 'UNPAID' ? 'selected' : '' ?>>UNPAID</option>
                                <option value="PAID" <?= $order['status'] == 'PAID' ? 'selected' : '' ?>>PAID</option>
                                <option value="SETTLEMENT" <?= $order['status'] == 'SETTLEMENT' ? 'selected' : '' ?>>SETTLEMENT</option>
                                <option value="SHIPPED" <?= $order['status'] == 'SHIPPED' ? 'selected' : '' ?>>SHIPPED</option>
                                <option value="COMPLETED" <?= $order['status'] == 'COMPLETED' ? 'selected' : '' ?>>COMPLETED</option>
                                <option value="CANCELLED" <?= $order['status'] == 'CANCELLED' ? 'selected' : '' ?>>CANCELLED</option>
                            </select>
                        </div>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="8" class="text-center">Tidak ada data pesanan yang ditemukan.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
// Konfigurasi Trigger Otomatis dari Flashdata (Server Side)
<?php if (session()->getFlashdata('success')) : ?>
    iziToast.success({
        timeout: 20000,
        title: 'Berhasil',
        message: '<?= session()->getFlashdata('success') ?>',
        position: 'topCenter'
    });
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    iziToast.error({
        timeout: 20000,
        title: 'Gagal',
        message: '<?= session()->getFlashdata('error') ?>',
        position: 'topCenter'
    });
<?php endif; ?>
// end konfigurasi

$(document).ready(function() {
    // Konfigurasi DataTables dengan fitur search yang enhanced
    var table = $('#table-1').DataTable({
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya", 
                "previous": "Sebelumnya"
            },
            "emptyTable": "Tidak ada data yang tersedia",
            "zeroRecords": "Tidak ada data yang cocok ditemukan"
        },
        "columnDefs": [
            { "sortable": false, "targets": [1,2,3,4,5,6,7] } // Foto dan Aksi tidak bisa di-sort
        ],
        "pageLength": 10,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "dom": 'rt<"d-flex justify-content-between mt-3"ip>', // Hide default search, show only table, length, pagination
        "drawCallback": function(settings) {
            // Re-initialize tooltips after table redraw
            $('[data-toggle="tooltip"]').tooltip();
        }
    });
    
    // Hubungkan search input custom dengan DataTables search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });
    
    // Clear search when input is cleared
    $('#searchInput').on('search', function() {
        if (this.value === '') {
            table.search('').draw();
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<?= $this->endSection() ?>