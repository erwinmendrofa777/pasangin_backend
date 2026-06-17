<script>
    $(document).ready(function () {
        let bahanIndex = 0;
        let tenagaIndex = 0;

        // Formats currency to Indonesian Rupiah standard format
        function formatRupiah(value) {
            let val = parseFloat(value);
            if (isNaN(val)) return 'Rp 0,00';
            return 'Rp ' + val.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function formatDecimal(value) {
            let val = parseFloat(value);
            if (isNaN(val)) return '0.0000';
            return val.toLocaleString('id-ID', { minimumFractionDigits: 4, maximumFractionDigits: 4 });
        }

        // Add a row to Bahan form table
        function addBahanRow(data = {}) {
            let row = `
                <tr class="bahan-row">
                    <td>
                        <input type="text" name="bahan[${bahanIndex}][kode]" class="form-control form-control-sm" value="${data.kode || ''}" placeholder="Kode (Opsional)" style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="text" name="bahan[${bahanIndex}][uraian]" class="form-control form-control-sm" value="${data.uraian || ''}" placeholder="Nama Bahan" required style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="text" name="bahan[${bahanIndex}][satuan]" class="form-control form-control-sm" value="${data.satuan || ''}" placeholder="Satuan" required style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="number" step="0.0001" min="0" name="bahan[${bahanIndex}][koefisien]" class="form-control form-control-sm text-end" value="${data.koefisien || ''}" placeholder="0.0000" required style="border-radius: 6px;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="border-radius: 6px;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#table-bahan-form tbody').append(row);
            bahanIndex++;
        }

        // Add a row to Tenaga Kerja form table
        function addTenagaRow(data = {}) {
            let row = `
                <tr class="tenaga-row">
                    <td>
                        <input type="text" name="tenaga_kerja[${tenagaIndex}][kode]" class="form-control form-control-sm" value="${data.kode || ''}" placeholder="Kode (Opsional)" style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="text" name="tenaga_kerja[${tenagaIndex}][uraian]" class="form-control form-control-sm" value="${data.uraian || ''}" placeholder="Klasifikasi" required style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="text" name="tenaga_kerja[${tenagaIndex}][satuan]" class="form-control form-control-sm" value="${data.satuan || ''}" placeholder="Satuan" required style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="number" step="0.0001" min="0" name="tenaga_kerja[${tenagaIndex}][koefisien]" class="form-control form-control-sm text-end" value="${data.koefisien || ''}" placeholder="0.0000" required style="border-radius: 6px;">
                    </td>
                    <td>
                        <input type="number" step="0.01" min="0" name="tenaga_kerja[${tenagaIndex}][harga_satuan]" class="form-control form-control-sm text-end" value="${data.harga_satuan || ''}" placeholder="0.00" required style="border-radius: 6px;">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" style="border-radius: 6px;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#table-tenaga-form tbody').append(row);
            tenagaIndex++;
        }

        <?php if (!empty($ahsp)): ?>
            // Extract detail row HTML content and remove them from the DOM before DataTable initialization
            var detailMap = {};
            $('#table-ahsp tbody tr.ahsp-detail-row').each(function () {
                var id = $(this).attr('id');
                detailMap[id] = $(this).find('.detail-container').html();
                $(this).remove();
            });

            // DataTables configuration
            var table = $('#table-ahsp').DataTable({
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
                "columnDefs": [{
                    "sortable": false,
                    "targets": [0, 5]
                }],
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "dom": 'rt<"dt-footer d-flex justify-content-between align-items-center"ip>',
                "drawCallback": function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            // Connect search input with DataTables
            $('#searchInput').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Clear search on input empty
            $('#searchInput').on('search', function () {
                if (this.value === '') {
                    table.search('').draw();
                }
            });

            // Tooltips initialization
            $('[data-toggle="tooltip"]').tooltip();

            // Toggle details click handler
            $('#table-ahsp tbody').on('click', '.toggle-details', function (e) {
                e.stopPropagation();
                var tr = $(this).closest('tr');
                var id = tr.data('id');
                var detailId = 'detail-ahsp-' + id;
                var row = table.row(tr);
                var icon = $(this).find('.toggle-icon');

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    icon.css('transform', 'none');
                } else {
                    var content = `<div class="ahsp-detail-wrapper" style="background: #f8fafc; padding: 15px 20px; border-radius: 8px;">${detailMap[detailId]}</div>`;
                    row.child(content).show();
                    tr.addClass('shown');
                    icon.css('transform', 'rotate(90deg)');
                }
            });
        <?php endif; ?>

        // Row removal handler
        $(document).on('click', '.btn-remove-row', function () {
            $(this).closest('tr').remove();
        });

        // "+ Tambah Bahan" button click
        $('#btn-add-bahan').on('click', function () {
            addBahanRow();
        });

        // "+ Tambah Tenaga Kerja" button click
        $('#btn-add-tenaga').on('click', function () {
            addTenagaRow();
        });

        // Open Modal for Create (TAMBAH)
        $('.btn-tambah-ahsp').on('click', function () {
            $('#ahspModalTitle').html('<i class="fas fa-clipboard-list me-2 text-primary"></i>Tambah AHSP Baru');
            $('#ahspForm').attr('action', '<?= site_url("admin/ahsp/store") ?>');
            
            // Clear inputs
            $('#kode').val('');
            $('#uraian').val('');
            
            // Reset tables
            $('#table-bahan-form tbody').empty();
            $('#table-tenaga-form tbody').empty();
            
            bahanIndex = 0;
            tenagaIndex = 0;

            // Activate first tab
            var firstTab = new bootstrap.Tab(document.querySelector('#bahan-tab'));
            firstTab.show();
        });

        // Open Modal for EDIT (AJAX fetch)
        $(document).on('click', '.btn-edit-ahsp', function () {
            var id = $(this).data('id');
            $('#ahspModalTitle').html('<i class="fas fa-clipboard-list me-2 text-primary"></i>Edit Data AHSP');
            $('#ahspForm').attr('action', '<?= site_url("admin/ahsp/update") ?>/' + id);

            // Clear inputs & tables
            $('#kode').val('');
            $('#uraian').val('');
            $('#table-bahan-form tbody').empty();
            $('#table-tenaga-form tbody').empty();
            bahanIndex = 0;
            tenagaIndex = 0;

            // Activate first tab
            var firstTab = new bootstrap.Tab(document.querySelector('#bahan-tab'));
            firstTab.show();

            // Load data via AJAX
            $.ajax({
                url: '<?= site_url("admin/ahsp/show") ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        let data = res.data;
                        $('#kode').val(data.kode);
                        $('#uraian').val(data.uraian);

            // Populate materials
                        if (data.bahan && data.bahan.length > 0) {
                            data.bahan.forEach(function (b) {
                                addBahanRow(b);
                            });
                        }

                        // Populate labor
                        if (data.tenaga_kerja && data.tenaga_kerja.length > 0) {
                            data.tenaga_kerja.forEach(function (t) {
                                addTenagaRow(t);
                            });
                        }
                    } else {
                        iziToast.error({
                            title: 'Gagal',
                            message: res.message || 'Gagal memuat data AHSP.',
                            position: 'topCenter'
                        });
                        $('#ahspModal').modal('hide');
                    }
                },
                error: function (xhr, status, error) {
                    iziToast.error({
                        title: 'Gagal',
                        message: 'Terjadi kesalahan sistem saat memuat data.',
                        position: 'topCenter'
                    });
                    $('#ahspModal').modal('hide');
                }
            });
        });

        // Open Modal for DETAIL (AJAX fetch)
        $(document).on('click', '.btn-detail-ahsp', function () {
            var id = $(this).data('id');

            // Reset labels and tables
            $('#detail-kode').text('-');
            $('#detail-uraian').text('-');
            $('#table-bahan-detail tbody').empty();
            $('#table-tenaga-detail tbody').empty();

            // Activate first tab
            var firstTab = new bootstrap.Tab(document.querySelector('#detail-bahan-tab'));
            firstTab.show();

            // Load data via AJAX
            $.ajax({
                url: '<?= site_url("admin/ahsp/show") ?>/' + id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status) {
                        let data = res.data;
                        $('#detail-kode').text(data.kode);
                        $('#detail-uraian').text(data.uraian);

                        // Populate materials table
                        if (data.bahan && data.bahan.length > 0) {
                            let idx = 1;
                            data.bahan.forEach(function (b) {
                                let row = `
                                    <tr>
                                        <td class="text-center">${idx++}</td>
                                        <td class="text-primary fw-semibold">${b.kode || '-'}</td>
                                        <td class="text-dark fw-semibold">${b.uraian}</td>
                                        <td><span class="badge bg-light-secondary text-muted">${b.satuan || '-'}</span></td>
                                        <td class="text-end fw-bold">${formatDecimal(b.koefisien)}</td>
                                    </tr>
                                `;
                                $('#table-bahan-detail tbody').append(row);
                            });
                        } else {
                            $('#table-bahan-detail tbody').append('<tr><td colspan="5" class="text-center text-muted">Tidak ada rincian bahan.</td></tr>');
                        }

                        // Populate labor table with dynamic multiplication
                        if (data.tenaga_kerja && data.tenaga_kerja.length > 0) {
                            let idx = 1;
                            data.tenaga_kerja.forEach(function (t) {
                                let koef = parseFloat(t.koefisien) || 0;
                                let price = parseFloat(t.harga_satuan) || 0;
                                let total = koef * price;

                                let row = `
                                    <tr>
                                        <td class="text-center">${idx++}</td>
                                        <td class="text-primary fw-semibold">${t.kode || '-'}</td>
                                        <td class="text-dark fw-semibold">${t.uraian}</td>
                                        <td><span class="badge bg-light-secondary text-muted">${t.satuan || '-'}</span></td>
                                        <td class="text-end fw-bold">${formatDecimal(t.koefisien)}</td>
                                        <td class="text-end">${formatRupiah(t.harga_satuan)}</td>
                                        <td class="text-end fw-bold text-success">${formatRupiah(total)}</td>
                                    </tr>
                                `;
                                $('#table-tenaga-detail tbody').append(row);
                            });
                        } else {
                            $('#table-tenaga-detail tbody').append('<tr><td colspan="7" class="text-center text-muted">Tidak ada rincian tenaga kerja.</td></tr>');
                        }
                    } else {
                        iziToast.error({
                            title: 'Gagal',
                            message: res.message || 'Gagal memuat detail AHSP.',
                            position: 'topCenter'
                        });
                        $('#ahspDetailModal').modal('hide');
                    }
                },
                error: function (xhr, status, error) {
                    iziToast.error({
                        title: 'Gagal',
                        message: 'Terjadi kesalahan sistem saat memuat detail.',
                        position: 'topCenter'
                    });
                    $('#ahspDetailModal').modal('hide');
                }
            });
        });

        /* ===== Flash Messages ===== */
        <?php if (session()->getFlashdata('success')): ?>
            iziToast.success({
                timeout: 5000,
                title: 'Berhasil',
                message: '<?= session()->getFlashdata('success') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            iziToast.error({
                timeout: 5000,
                title: 'Gagal',
                message: '<?= session()->getFlashdata('error') ?>',
                position: 'topCenter'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                iziToast.error({
                    timeout: 6000,
                    title: 'Gagal',
                    message: '<?= esc($error) ?>',
                    position: 'topCenter'
                });
            <?php endforeach; ?>
        <?php endif; ?>
    });
</script>
