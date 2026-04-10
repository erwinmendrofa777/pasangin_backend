<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>
Manajemen Produk
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
  <div class="section-header">
    <h1></h1> 
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></div>
      <div class="breadcrumb-item">Produk Supplier</div>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>Daftar Produk Supplier</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-md image-gallery">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Tgl Dibuat</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $key => $product) : ?>
                      <tr>
                        <td><?= $key + 1 ?></td>
                        <td>
                          <?php if (!empty($product['photo']) && file_exists('./uploads/products/' . $product['photo'])) : ?>
                            <a href="<?= base_url('uploads/products/' . $product['photo']) ?>" class="image-link">
                              <img src="<?= base_url('uploads/products/' . $product['photo']) ?>" alt="<?= esc($product['name']) ?>" width="80" style="cursor: pointer;">
                            </a>
                          <?php else : ?>
                            <img src="<?= base_url('assets/img/news/img05.jpg') ?>" alt="No Image" width="80">
                          <?php endif; ?>
                        </td>
                        <td><?= esc($product['name']) ?></td>
                        <td><?= esc($product['supplier_name']) ?></td>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                        <td><?= esc($product['stock']) ?></td>
                        <td>
                          <?php if ($product['status'] === 'aktif' && $product['stock'] > 0) : ?>
                            <div class="badge badge-success">Tersedia</div>
                          <?php else : ?>
                            <div class="badge badge-warning">Habis / Tidak Aktif</div>
                          <?php endif; ?>
                        </td>
                        <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                        <td class="text-center">
                          <a href="#" class="btn btn-info btn-sm" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                          <a href="#" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <tr>
                      <td colspan="9" class="text-center">Tidak ada data produk yang ditemukan.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?= $this->endSection() ?>

<!-- INI ADALAH BAGIAN SCRIPT YANG SUDAH DIPERBAIKI -->
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
  $('.image-gallery').magnificPopup({
    delegate: 'a.image-link',
    type: 'image',
    gallery: {
      enabled: true
    },
    mainClass: 'mfp-with-zoom', 
    zoom: {
      enabled: true,
      duration: 300,
      easing: 'ease-in-out'
    },

    // Kunci untuk mengatur ukuran zoom dengan presisi
    callbacks: {
      open: function() {
        // Ambil elemen gambar di dalam popup
        var image = this.content.find('img');
        
        // Atur style-nya secara langsung dengan JavaScript
        image.css({
          'max-width': '60%',   // Atur lebar maksimal (misal: 60% dari layar)
          'max-height': '75vh', // Atur tinggi maksimal (misal: 75% dari tinggi layar)
          'margin': 'auto'      // Pusatkan gambar
        });
      }
    }
  });
});
</script>
<?= $this->endSection() ?>
